"""
LaptopAdvisor Recommendation Engine
Machine Learning-based product recommendations using collaborative and content-based filtering
"""

import pandas as pd
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity, linear_kernel
from sklearn.preprocessing import StandardScaler, MinMaxScaler
from scipy.sparse import csr_matrix
from sklearn.neighbors import NearestNeighbors
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.decomposition import TruncatedSVD
from sklearn.decomposition import TruncatedSVD
import mysql.connector
from sqlalchemy import create_engine
from config import Config
import pickle
import os
from datetime import datetime, timedelta

class RecommendationEngine:
    """Main recommendation engine class"""
    
    def __init__(self):
        self.config = Config()
        self.config.ensure_model_dir()
        self.conn = None
        self.products_df = None
        self.ratings_df = None
        self.orders_df = None
        self.product_features = None
        self.similarity_matrix = None
        self.svd_model = None
        self.user_features = None
        
    def connect_db(self):
        """Establish database connection"""
        try:
            # Create SQLAlchemy engine
            connection_string = f"mysql+mysqlconnector://{self.config.DB_USER}:{self.config.DB_PASSWORD}@{self.config.DB_HOST}/{self.config.DB_NAME}"
            self.conn = create_engine(connection_string)
            return True
        except Exception as e:
            print(f"Database connection error: {e}")
            return False
    
    def close_db(self):
        """Close database connection"""
        if self.conn:
            self.conn.dispose()
    
    def load_data(self):
        """Load product and rating data from database"""
        if not self.connect_db():
            return False
        
        try:
            # Load products
            products_query = """
                SELECT product_id, product_name, brand, price, ram_gb, storage_gb, 
                       display_size, cpu, gpu, battery_life, primary_use_case
                FROM products
                WHERE is_active = 1
            """
            self.products_df = pd.read_sql(products_query, self.conn)
            
            # Load ratings
            ratings_query = """
                SELECT user_id, product_id, rating
                FROM recommendation_ratings
                WHERE rating IN (-1, 1)
            """
            self.ratings_df = pd.read_sql(ratings_query, self.conn)
            
            # Load orders for weighted interactions
            orders_query = """
                SELECT o.user_id, oi.product_id
                FROM order_items oi
                JOIN orders o ON oi.order_id = o.order_id
            """
            self.orders_df = pd.read_sql(orders_query, self.conn)
            
            self.close_db()
            return True
        except Exception as e:
            print(f"Data loading error: {e}")
            self.close_db()
            return False
    
    def prepare_content_features(self):
        """Prepare product features using TF-IDF and metadata"""
        if self.products_df is None:
            return False
        
        # 1. Create Metadata Soup (Text representation)
        # Combine important text fields
        self.products_df['soup'] = (
            self.products_df['primary_use_case'] + " " + 
            self.products_df['brand'] + " " + 
            self.products_df['cpu'] + " " + 
            self.products_df['gpu'] + " " +
            self.products_df['battery_life'].fillna('') + " " +
            self.products_df['product_name']
        )
        
        # 2. TF-IDF Vectorization
        tfidf = TfidfVectorizer(stop_words='english')
        tfidf_matrix = tfidf.fit_transform(self.products_df['soup'].fillna(''))
        
        # 3. Numerical Features (Price, RAM, Storage)
        scaler = MinMaxScaler()
        numerical_features = scaler.fit_transform(
            self.products_df[['price', 'ram_gb', 'storage_gb', 'display_size']].fillna(0)
        )
        
        # 4. Combine Features? 
        # Actually, for content similarity, TF-IDF on rich text is often better than mixing scales.
        # But let's compute similarity on TF-IDF primarily, and boost with numerical closeness?
        # For simplicity and robustness, let's stick to a pure content similarity based on the "soup" 
        # which now includes specs as text. This captures "Gaming" + "RTX 4060" well.
        
        # Calculate Cosine Similarity Matrix
        self.similarity_matrix = linear_kernel(tfidf_matrix, tfidf_matrix)
        
        # Store features for potential other uses
        self.product_features = tfidf_matrix
        
        return True
    
    def get_content_based_recommendations(self, product_id, n=10):
        """Get recommendations based on product similarity"""
        if self.similarity_matrix is None:
            self.prepare_content_features()
        
        try:
            # Get index of the product
            idx = self.products_df[self.products_df['product_id'] == product_id].index[0]
            
            # Get similarity scores
            sim_scores = list(enumerate(self.similarity_matrix[idx]))
            
            # Sort by similarity
            sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)
            
            # Get top N similar products (excluding itself)
            sim_scores = sim_scores[1:n+1]
            
            # Get product indices
            product_indices = [i[0] for i in sim_scores]
            scores = [i[1] for i in sim_scores]
            
            # Return product IDs and scores
            recommendations = self.products_df.iloc[product_indices]['product_id'].tolist()
            
            return [
                {'product_id': int(pid), 'score': float(score), 'method': 'content_based'}
                for pid, score in zip(recommendations, scores)
            ]
        except Exception as e:
            print(f"Content-based recommendation error: {e}")
            return []
    
    def get_collaborative_recommendations(self, user_id, n=10):
        """Get recommendations using SVD (Matrix Factorization)"""
        if self.ratings_df is None:
            return []
            
        try:
            # 1. Prepare Interaction Matrix
            # Combine ratings and orders
            interactions = self.ratings_df.copy()
            interactions['weight'] = 1.0 # Base weight for ratings
            
            if self.orders_df is not None and not self.orders_df.empty:
                orders = self.orders_df.copy()
                orders['weight'] = 2.0 # Higher weight for purchases
                orders['rating'] = 1 # Implicit positive rating
                
                # Combine
                interactions = pd.concat([
                    interactions[['user_id', 'product_id', 'weight']], 
                    orders[['user_id', 'product_id', 'weight']]
                ])
            
            # Aggregate weights (if user rated AND bought)
            interactions = interactions.groupby(['user_id', 'product_id'])['weight'].sum().reset_index()
            
            # Create Pivot Table
            user_item_matrix = interactions.pivot(
                index='user_id', 
                columns='product_id', 
                values='weight'
            ).fillna(0)
            
            if user_id not in user_item_matrix.index:
                return []
                
            # 2. Apply SVD (Matrix Factorization)
            # Only if we have enough data
            if len(user_item_matrix) < 5:
                # Fallback to simple popularity if not enough users
                return []
                
            X = csr_matrix(user_item_matrix.values)
            
            # Number of latent factors
            n_components = min(20, len(user_item_matrix) - 1)
            svd = TruncatedSVD(n_components=n_components, random_state=42)
            user_factors = svd.fit_transform(X)
            item_factors = svd.components_
            
            # 3. Predict Scores
            # Reconstruct matrix: U * Sigma * Vt
            predicted_ratings = np.dot(user_factors, item_factors)
            
            # Get user's row index
            user_idx = user_item_matrix.index.get_loc(user_id)
            user_predictions = predicted_ratings[user_idx]
            
            # 4. Filter and Sort
            # Get products user hasn't interacted with? 
            # Actually, for "For You", we might want to recommend things they viewed but didn't buy too.
            # But let's exclude things they already have a high weight for (bought).
            
            user_actual = user_item_matrix.iloc[user_idx]
            
            recommendations = []
            for product_idx, score in enumerate(user_predictions):
                product_id = user_item_matrix.columns[product_idx]
                actual_weight = user_actual.iloc[product_idx]
                
                # If they haven't bought it (weight < 2), recommend it
                if actual_weight < 2.0:
                    recommendations.append({
                        'product_id': int(product_id),
                        'score': float(score),
                        'method': 'collaborative_svd'
                    })
            
            # Sort by score
            recommendations.sort(key=lambda x: x['score'], reverse=True)
            
            # Normalize scores to 0-1 range for hybrid mixing
            if recommendations:
                max_score = recommendations[0]['score']
                if max_score > 0:
                    for rec in recommendations:
                        rec['score'] /= max_score
            
            return recommendations[:n]

        except Exception as e:
            print(f"SVD Collaborative filtering error: {e}")
            return []
    
    def get_hybrid_recommendations(self, user_id, use_case=None, n=10):
        """Get hybrid recommendations combining collaborative and content-based"""
        # Get collaborative recommendations
        collab_recs = self.get_collaborative_recommendations(user_id, n=n*2)
        
        # If not enough collaborative recommendations, use content-based
        if len(collab_recs) < n:
            # Get user's liked products
            user_liked = self.ratings_df[
                (self.ratings_df['user_id'] == user_id) & 
                (self.ratings_df['rating'] == 1)
            ]['product_id'].tolist()
            
            content_recs = []
            if user_liked:
                # Get recommendations based on last liked product
                last_liked = user_liked[-1]
                content_recs = self.get_content_based_recommendations(last_liked, n=n*2)
            
            # If still not enough, get popular products in use case
            if len(content_recs) < n and use_case:
                popular_recs = self.get_popular_by_use_case(use_case, n=n)
                content_recs.extend(popular_recs)
            
            # Combine recommendations
            all_recs = {}
            
            # Add collaborative with higher weight
            for rec in collab_recs:
                all_recs[rec['product_id']] = rec['score'] * self.config.COLLABORATIVE_WEIGHT
            
            # Add content-based with lower weight
            for rec in content_recs:
                pid = rec['product_id']
                if pid in all_recs:
                    all_recs[pid] += rec['score'] * self.config.CONTENT_WEIGHT
                else:
                    all_recs[pid] = rec['score'] * self.config.CONTENT_WEIGHT
            
            # Sort by combined score
            sorted_recs = sorted(all_recs.items(), key=lambda x: x[1], reverse=True)[:n]
            
            return [
                {
                    'product_id': int(pid),
                    'score': float(score),
                    'method': 'hybrid'
                }
                for pid, score in sorted_recs
            ]
        
        return collab_recs[:n]
    
    def get_popular_by_use_case(self, use_case, n=10):
        """Get popular products for a specific use case"""
        if self.products_df is None:
            return []
        
        # Filter by use case
        filtered_products = self.products_df[
            self.products_df['primary_use_case'] == use_case
        ]
        
        # Get rating counts
        if self.ratings_df is not None and len(self.ratings_df) > 0:
            rating_counts = self.ratings_df[
                self.ratings_df['rating'] == 1
            ].groupby('product_id').size().reset_index(name='count')
            
            # Merge with products
            popular = filtered_products.merge(rating_counts, on='product_id', how='left')
            popular['count'] = popular['count'].fillna(0)
            
            # Sort by count and price (lower is better)
            popular = popular.sort_values(['count', 'price'], ascending=[False, True])
        else:
            # If no ratings, sort by price
            popular = filtered_products.sort_values('price')
        
        # Get top N
        top_products = popular.head(n)
        
        return [
            {
                'product_id': int(row['product_id']),
                'score': 0.5,  # Default score for popular items
                'method': 'popular'
            }
            for _, row in top_products.iterrows()
        ]
    
    def save_model(self):
        """Save the trained model components"""
        model_data = {
            'similarity_matrix': self.similarity_matrix,
            'product_features': self.product_features,
            'products_df': self.products_df,
            'timestamp': datetime.now()
        }
        
        model_path = os.path.join(self.config.MODEL_PATH, 'recommendation_model.pkl')
        with open(model_path, 'wb') as f:
            pickle.dump(model_data, f)
        
        print(f"Model saved to {model_path}")
    
    def load_model(self):
        """Load a previously trained model"""
        model_path = os.path.join(self.config.MODEL_PATH, 'recommendation_model.pkl')
        
        if not os.path.exists(model_path):
            return False
        
        try:
            with open(model_path, 'rb') as f:
                model_data = pickle.load(f)
            
            # Check if model is not too old (24 hours)
            if datetime.now() - model_data['timestamp'] > timedelta(hours=self.config.CACHE_EXPIRY_HOURS):
                return False
            
            self.similarity_matrix = model_data['similarity_matrix']
            self.product_features = model_data['product_features']
            self.products_df = model_data['products_df']
            
            print("Model loaded successfully")
            return True
        except Exception as e:
            print(f"Model loading error: {e}")
            return False
    
    def train(self):
        """Train/update the recommendation model"""
        print("Training recommendation model...")
        
        # Load data
        if not self.load_data():
            return False
        
        # Prepare features
        if not self.prepare_content_features():
            return False
        
        # Save model
        self.save_model()
        
        print("Model training complete!")
        return True


if __name__ == "__main__":
    # Test the recommendation engine
    engine = RecommendationEngine()
    
    # Train the model
    if engine.train():
        print("\n✓ Model trained successfully!")
        
        # Test recommendations
        print("\nTesting recommendations for user_id=1...")
        recommendations = engine.get_hybrid_recommendations(user_id=1, use_case='Gamer', n=5)
        
        print(f"\nFound {len(recommendations)} recommendations:")
        for i, rec in enumerate(recommendations, 1):
            print(f"{i}. Product ID: {rec['product_id']}, Score: {rec['score']:.3f}, Method: {rec['method']}")
    else:
        print("\n✗ Model training failed!")
