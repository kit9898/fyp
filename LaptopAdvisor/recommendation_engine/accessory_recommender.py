"""
LaptopAdvisor Accessory Recommendation Engine
AI-powered accessory recommendations using Content-Based, Collaborative, and Hybrid algorithms
"""

import pandas as pd
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.preprocessing import StandardScaler
import mysql.connector
from sqlalchemy import create_engine
from config import Config
import pickle
import os
from datetime import datetime

class AccessoryRecommender:
    """AI-powered accessory recommendation system"""
    
    def __init__(self):
        self.config = Config()
        self.conn = None
        self.laptops_df = None
        self.accessories_df = None
        self.orders_df = None
        self.laptop_features = None
        self.accessory_features = None
        self.purchase_patterns = None
        
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
        """Load product and order data from database"""
        if not self.connect_db():
            return False
        
        try:
            # Load laptops
            laptops_query = """
                SELECT product_id, product_name, brand, price, ram_gb, storage_gb, 
                       display_size, cpu, gpu, primary_use_case
                FROM products
                WHERE product_category = 'laptop'
            """
            self.laptops_df = pd.read_sql(laptops_query, self.conn)
            
            # Load accessories
            accessories_query = """
                SELECT product_id, product_name, product_category, related_to_category,
                       brand, price, primary_use_case
                FROM products
                WHERE product_category != 'laptop'
            """
            self.accessories_df = pd.read_sql(accessories_query, self.conn)
            
            # Load order history for collaborative filtering
            orders_query = """
                SELECT o.user_id, oi.product_id, p.product_category, p.primary_use_case,
                       o.order_date
                FROM order_items oi
                JOIN orders o ON oi.order_id = o.order_id
                JOIN products p ON oi.product_id = p.product_id
                ORDER BY o.order_date DESC
            """
            self.orders_df = pd.read_sql(orders_query, self.conn)
            
            self.close_db()
            return True
        except Exception as e:
            print(f"Data loading error: {e}")
            self.close_db()
            return False
    
    def prepare_content_features(self):
        """Prepare features for content-based filtering"""
        if self.laptops_df is None or self.accessories_df is None:
            return False
        
        # Create feature vectors for laptops
        laptop_features = pd.DataFrame()
        
        # Normalize numerical features
        scaler = StandardScaler()
        numerical_cols = ['price', 'ram_gb', 'storage_gb', 'display_size']
        laptop_features[numerical_cols] = scaler.fit_transform(
            self.laptops_df[numerical_cols].fillna(0)
        )
        
        # One-hot encode categorical features
        laptop_features = pd.concat([
            laptop_features,
            pd.get_dummies(self.laptops_df['brand'], prefix='brand'),
            pd.get_dummies(self.laptops_df['primary_use_case'], prefix='use_case')
        ], axis=1)
        
        # GPU/CPU features
        laptop_features['has_high_end_cpu'] = self.laptops_df['cpu'].str.contains(
            'i7|i9|Ryzen 7|Ryzen 9|M2 Pro|M3', case=False, na=False
        ).astype(int)
        
        laptop_features['has_gaming_gpu'] = self.laptops_df['gpu'].str.contains(
            'RTX 40|RTX 30|RX 7|RX 6', case=False, na=False
        ).astype(int)
        
        self.laptop_features = laptop_features
        
        # Create accessory features
        accessory_features = pd.DataFrame()
        
        # Price normalization - flatten to 1D array
        accessory_features['price_normalized'] = scaler.fit_transform(
            self.accessories_df[['price']].fillna(0)
        ).ravel()  # Convert 2D array to 1D
        
        # Category and use case encoding
        accessory_features = pd.concat([
            accessory_features,
            pd.get_dummies(self.accessories_df['product_category'], prefix='category'),
            pd.get_dummies(self.accessories_df['related_to_category'], prefix='use_case')
        ], axis=1)
        
        self.accessory_features = accessory_features
        
        return True
    
    def get_content_based_recommendations(self, laptop_id, user_use_case=None, n=6):
        """
        Content-based filtering: Recommend accessories based on laptop features
        """
        try:
            # Get laptop details
            laptop = self.laptops_df[self.laptops_df['product_id'] == laptop_id].iloc[0]
            laptop_use_case = laptop['primary_use_case']
            laptop_price = laptop['price']
            
            # Filter accessories by use case
            matching_accessories = self.accessories_df[
                (self.accessories_df['related_to_category'] == laptop_use_case) |
                (self.accessories_df['related_to_category'] == 'General Use')
            ].copy()
            
            if len(matching_accessories) == 0:
                return []
            
            # Calculate content-based scores
            scores = []
            for _, accessory in matching_accessories.iterrows():
                score = 0.0
                
                # Use case match (highest weight)
                if accessory['related_to_category'] == laptop_use_case:
                    score += 0.5
                
                # Price range compatibility (laptops with higher price get premium accessories)
                if laptop_price > 2000 and accessory['price'] > 100:
                    score += 0.3
                elif 1000 < laptop_price <= 2000 and 50 < accessory['price'] <= 150:
                    score += 0.3
                elif laptop_price <= 1000 and accessory['price'] <= 80:
                    score += 0.3
                else:
                    score += 0.1
                
                # Brand synergy (same brand gets bonus)
                if accessory['brand'] == laptop['brand']:
                    score += 0.2
                
                scores.append({
                    'product_id': int(accessory['product_id']),
                    'score': score,
                    'method': 'content_based',
                    'category': accessory['product_category']
                })
            
            # Sort by score and diversify by category
            scores.sort(key=lambda x: x['score'], reverse=True)
            
            # Ensure diversity: max 2 items per category
            diverse_recommendations = []
            category_count = {}
            
            for item in scores:
                cat = item['category']
                if category_count.get(cat, 0) < 2:
                    diverse_recommendations.append(item)
                    category_count[cat] = category_count.get(cat, 0) + 1
                
                if len(diverse_recommendations) >= n:
                    break
            
            return diverse_recommendations
        
        except Exception as e:
            print(f"Content-based recommendation error: {e}")
            return []
    
    def get_collaborative_recommendations(self, user_id, laptop_id, n=6):
        """
        Collaborative filtering: Recommend accessories based on what users with similar purchases bought
        """
        if self.orders_df is None or len(self.orders_df) < 5:
            return []
        
        try:
            # Find users who bought the same laptop
            users_with_same_laptop = self.orders_df[
                self.orders_df['product_id'] == laptop_id
            ]['user_id'].unique()
            
            if len(users_with_same_laptop) == 0:
                # Fallback: users who bought laptops in the same use case
                laptop_use_case = self.laptops_df[
                    self.laptops_df['product_id'] == laptop_id
                ]['primary_use_case'].iloc[0]
                
                users_with_same_laptop = self.orders_df[
                    (self.orders_df['primary_use_case'] == laptop_use_case) &
                    (self.orders_df['product_category'] == 'laptop')
                ]['user_id'].unique()
            
            # Get accessories purchased by these users
            accessories_purchased = self.orders_df[
                (self.orders_df['user_id'].isin(users_with_same_laptop)) &
                (self.orders_df['product_category'] != 'laptop')
            ]
            
            # Count frequency of each accessory
            accessory_counts = accessories_purchased['product_id'].value_counts()
            
            # Calculate collaborative scores
            max_count = accessory_counts.max() if len(accessory_counts) > 0 else 1
            
            recommendations = []
            for product_id, count in accessory_counts.items():
                recommendations.append({
                    'product_id': int(product_id),
                    'score': float(count / max_count),
                    'method': 'collaborative',
                    'purchase_count': int(count)
                })
            
            # Sort by score
            recommendations.sort(key=lambda x: x['score'], reverse=True)
            
            return recommendations[:n]
        
        except Exception as e:
            print(f"Collaborative filtering error: {e}")
            return []
    
    def get_hybrid_recommendations(self, user_id, laptop_id, user_use_case=None, n=6):
        """
        Hybrid algorithm: Combines content-based and collaborative filtering
        """
        # Get recommendations from both methods
        content_recs = self.get_content_based_recommendations(laptop_id, user_use_case, n=n*2)
        collab_recs = self.get_collaborative_recommendations(user_id, laptop_id, n=n*2)
        
        # Combine scores
        combined_scores = {}
        
        # Add content-based recommendations (weight: 0.6)
        for rec in content_recs:
            pid = rec['product_id']
            combined_scores[pid] = {
                'score': rec['score'] * 0.6,
                'product_id': pid,
                'methods': ['content_based']
            }
        
        # Add collaborative recommendations (weight: 0.4)
        for rec in collab_recs:
            pid = rec['product_id']
            if pid in combined_scores:
                combined_scores[pid]['score'] += rec['score'] * 0.4
                combined_scores[pid]['methods'].append('collaborative')
            else:
                combined_scores[pid] = {
                    'score': rec['score'] * 0.4,
                    'product_id': pid,
                    'methods': ['collaborative']
                }
        
        # Sort by combined score
        final_recommendations = sorted(
            combined_scores.values(),
            key=lambda x: x['score'],
            reverse=True
        )[:n]
        
        # Add method information
        for rec in final_recommendations:
            if len(rec['methods']) > 1:
                rec['method'] = 'hybrid'
            else:
                rec['method'] = rec['methods'][0]
            del rec['methods']
        
        return final_recommendations
    
    def get_popular_accessories_by_category(self, use_case, n=6):
        """Get popular accessories for a specific laptop category"""
        if self.accessories_df is None:
            return []
        
        # Filter by use case
        filtered = self.accessories_df[
            (self.accessories_df['related_to_category'] == use_case) |
            (self.accessories_df['related_to_category'] == 'General Use')
        ]
        
        if len(filtered) == 0:
            return []
        
        # If we have order data, sort by popularity
        if self.orders_df is not None and len(self.orders_df) > 0:
            # Get purchase counts
            purchase_counts = self.orders_df[
                self.orders_df['product_id'].isin(filtered['product_id'])
            ]['product_id'].value_counts()
            
            # Merge with accessory data
            filtered = filtered.copy()
            filtered['purchase_count'] = filtered['product_id'].map(purchase_counts).fillna(0)
            filtered = filtered.sort_values(['purchase_count', 'price'], ascending=[False, True])
        else:
            # Sort by price if no order data
            filtered = filtered.sort_values('price')
        
        # Get top N
        recommendations = []
        for _, row in filtered.head(n).iterrows():
            recommendations.append({
                'product_id': int(row['product_id']),
                'score': 0.5,
                'method': 'popular'
            })
        
        return recommendations


if __name__ == "__main__":
    # Test the accessory recommendation engine
    print("Testing Accessory Recommendation Engine...")
    
    recommender = AccessoryRecommender()
    
    if recommender.load_data():
        print(f"✓ Loaded {len(recommender.laptops_df)} laptops")
        print(f"✓ Loaded {len(recommender.accessories_df)} accessories")
        
        recommender.prepare_content_features()
        print("✓ Features prepared")
        
        # Test recommendations for a gaming laptop (product_id=3)
        print("\n--- Testing Hybrid Recommendations for Gaming Laptop ---")
        recommendations = recommender.get_hybrid_recommendations(
            user_id=1,
            laptop_id=3,
            user_use_case='Gaming',
            n=6
        )
        
        print(f"Found {len(recommendations)} recommendations:")
        for i, rec in enumerate(recommendations, 1):
            print(f"{i}. Product ID: {rec['product_id']}, Score: {rec['score']:.3f}, Method: {rec['method']}")
    else:
        print("✗ Failed to load data")
