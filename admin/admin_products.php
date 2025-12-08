<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Export CSV
if (isset($_GET['action']) && $_GET['action'] == 'export_csv') {
    $query = "SELECT * FROM products ORDER BY product_id DESC";
    $result = $conn->query($query);
    
    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=products_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    
    // Add CSV headers (Updated with product_category and related_to_category)
    fputcsv($output, ['ID', 'Name', 'Brand', 'Price', 'Product Type', 'Related To', 'Use Case', 'CPU', 'GPU', 'RAM (GB)', 'Storage (GB)', 'Storage Type', 'Display', 'Description']);
    
    // Add data rows
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['product_id'],
            $row['product_name'],
            $row['brand'],
            $row['price'],
            $row['product_category'] ?? 'laptop',
            $row['related_to_category'] ?? '',
            $row['primary_use_case'] ?? '',
            $row['cpu'] ?? '',
            $row['gpu'] ?? '',
            $row['ram_gb'] ?? '',
            $row['storage_gb'] ?? '',
            $row['storage_type'] ?? 'SSD',
            $row['display_size'] ?? '',
            $row['description'] ?? ''
        ]);
    }
    
    fclose($output);
    exit();
}

// Handle Bulk CSV Upload
if (isset($_POST['bulk_upload_csv']) && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];
    
    if ($file['error'] == 0 && ($file['type'] == 'text/csv' || pathinfo($file['name'], PATHINFO_EXTENSION) == 'csv')) {
        $handle = fopen($file['tmp_name'], 'r');
        $header = fgetcsv($handle); // Skip header row
        
        $imported = 0;
        $errors = [];
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            // Expected format: Name, Brand, Price, Product_Type, Related_To, Use_Case, CPU, GPU, RAM_GB, Storage_GB, Storage_Type, Display, Description
            if (count($data) >= 4) {
                $name = $conn->real_escape_string($data[0]);
                $brand = $conn->real_escape_string($data[1]);
                $price = floatval($data[2]);
                $product_category = $conn->real_escape_string($data[3] ?? 'laptop');
                $related_to_category = $conn->real_escape_string($data[4] ?? '');
                $category = $data[5] ?? ''; // primary_use_case
                $cpu = $data[6] ?? '';
                $gpu = $data[7] ?? '';
                $ram_gb = intval($data[8] ?? 0);
                $storage_gb = intval($data[9] ?? 0);
                $storage_type = $conn->real_escape_string($data[10] ?? 'SSD');
                $display = $data[11] ?? '';
                $description = $data[12] ?? '';
                
                $sql = "INSERT INTO products (product_name, brand, price, product_category, related_to_category, primary_use_case, cpu, gpu, ram_gb, storage_gb, storage_type, display_size, description) 
                        VALUES ('$name', '$brand', $price, '$product_category', " . ($related_to_category ? "'$related_to_category'" : "NULL") . ", '$category', '$cpu', '$gpu', $ram_gb, $storage_gb, '$storage_type', '$display', '$description')";
                
                if ($conn->query($sql)) {
                    $imported++;
                } else {
                    $errors[] = "Error importing: $name";
                }
            }
        }
        
        fclose($handle);
        
        if ($imported > 0) {
            $success_message = "$imported product(s) imported successfully!";
            logActivity($conn, $_SESSION['admin_id'], 'create', 'products', "Bulk imported $imported products via CSV");
        }
        if (!empty($errors)) {
            $error_message = implode(', ', $errors);
        }
    } else {
        $error_message = "Please upload a valid CSV file.";
    }
}

// Handle Add New Product (Phase 1-3: Complete with validation + Multiple Media)
if (isset($_POST['add_product'])) {
    $name = $conn->real_escape_string($_POST['product_name']);
    $brand = $conn->real_escape_string($_POST['brand']);
    $price = floatval($_POST['price']);
    $product_category = $conn->real_escape_string($_POST['product_category'] ?? 'laptop');
    $related_to_category = $conn->real_escape_string($_POST['related_to_category'] ?? '');
    $category = $conn->real_escape_string($_POST['category'] ?? ''); // primary_use_case
    $cpu = $conn->real_escape_string($_POST['cpu'] ?? '');
    $gpu = $conn->real_escape_string($_POST['gpu'] ?? '');
    $ram_gb = intval($_POST['ram'] ?? 0);
    $storage_gb = intval($_POST['storage'] ?? 0);
    $storage_type = $conn->real_escape_string($_POST['storage_type'] ?? 'SSD');
    $display = $conn->real_escape_string($_POST['display'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $battery_life = $conn->real_escape_string($_POST['battery_life'] ?? '');
    $video_url = trim($_POST['video_url'] ?? '');
    
    // Phase 2: Handle primary image upload with validation
    $image_url = null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $file = $_FILES['product_image'];
        
        // Validate file type (MIME type check)
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/avif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime_type, $allowed_types)) {
            $error_message = "Invalid image type. Only JPG, PNG, WEBP, and AVIF are allowed.";
        } else {
            // Validate file size (5MB maximum)
            $max_size = 5 * 1024 * 1024; // 5MB in bytes
            if ($file['size'] > $max_size) {
                $error_message = "Image file size exceeds 5MB limit. Your file is " . round($file['size'] / 1024 / 1024, 2) . "MB";
            } else {
                // Validate image dimensions (min 400x300) - RESTRICTION REMOVED
                $image_info = getimagesize($file['tmp_name']);
                if ($image_info !== false) {
                    // Restriction removed per user request
                    if (false) { 
                        $error_message = "Image dimensions too small...";
                    } else {
                        // Use LaptopAdvisor/images directory
                        $upload_dir = '../LaptopAdvisor/images/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        
                        // Sanitize filename and use safe extension
                        $extension_map = [
                            'image/jpeg' => 'jpg',
                            'image/jpg' => 'jpg',
                            'image/png' => 'png',
                            'image/webp' => 'webp',
                            'image/avif' => 'avif'
                        ];
                        $safe_extension = $extension_map[$mime_type];
                        $new_filename = uniqid('product_') . '.' . $safe_extension;
                        $upload_path = $upload_dir . $new_filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                            // Store relative path from root
                            $image_url = 'LaptopAdvisor/images/' . $new_filename;
                        } else {
                            $error_message = "Failed to upload image. Please try again.";
                        }
                    }
                } else {
                    $error_message = "Invalid image file. Could not read image data.";
                }
            }
        }
    }
    
    // Only insert if no image upload errors
    if (!isset($error_message)) {
        $sql = "INSERT INTO products (product_name, brand, price, product_category, related_to_category, primary_use_case, cpu, gpu, ram_gb, storage_gb, storage_type, display_size, battery_life, description" . ($image_url ? ", image_url" : "") . ") 
                VALUES ('$name', '$brand', $price, '$product_category', " . ($related_to_category ? "'$related_to_category'" : "NULL") . ", '$category', '$cpu', '$gpu', $ram_gb, $storage_gb, '$storage_type', '$display', '$battery_life', '$description'" . ($image_url ? ", '$image_url'" : "") . ")";
        
        if ($conn->query($sql)) {
            $new_product_id = $conn->insert_id;
            
            // Handle additional images upload
            $additional_images_uploaded = 0;
            if (isset($_FILES['additional_images'])) {
                $upload_dir = '../LaptopAdvisor/images/';
                $display_order = 1; // Primary image is 0, additional start at 1
                
                foreach ($_FILES['additional_images']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['additional_images']['error'][$key] == 0) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime_type = finfo_file($finfo, $tmp_name);
                        finfo_close($finfo);
                        
                        if (in_array($mime_type, ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/avif'])) {
                            $extension_map = [
                                'image/jpeg' => 'jpg',
                                'image/jpg' => 'jpg',
                                'image/png' => 'png',
                                'image/webp' => 'webp',
                                'image/avif' => 'avif'
                            ];
                            $safe_extension = $extension_map[$mime_type];
                            $new_filename = uniqid('product_' . $new_product_id . '_') . '.' . $safe_extension;
                            $upload_path = $upload_dir . $new_filename;
                            
                            if (move_uploaded_file($tmp_name, $upload_path)) {
                                $media_url = 'LaptopAdvisor/images/' . $new_filename;
                                $media_stmt = $conn->prepare("INSERT INTO product_media (product_id, media_type, media_url, display_order) VALUES (?, 'image', ?, ?)");
                                $media_stmt->bind_param("isi", $new_product_id, $media_url, $display_order);
                                if ($media_stmt->execute()) {
                                    $additional_images_uploaded++;
                                    $display_order++;
                                }
                                $media_stmt->close();
                            }
                        }
                    }
                }
            }
            
            // Handle video URL
            if (!empty($video_url) && filter_var($video_url, FILTER_VALIDATE_URL)) {
                $video_stmt = $conn->prepare("INSERT INTO product_media (product_id, media_type, media_url, display_order) VALUES (?, 'video', ?, ?)");
                $video_order = $display_order ?? 100;
                $video_stmt->bind_param("isi", $new_product_id, $video_url, $video_order);
                $video_stmt->execute();
                $video_stmt->close();
            }
            
            $success_message = "Product added successfully!";
            if ($additional_images_uploaded > 0) {
                $success_message .= " ($additional_images_uploaded additional image(s) uploaded)";
            }
            logActivity($conn, $_SESSION['admin_id'], 'create', 'products', "Added new product: $name", 'product', $new_product_id);
        } else {
            $error_message = "Error adding product: " . $conn->error;
        }
    }
}

// Handle Edit Product (with image replacement logic)
if (isset($_POST['edit_product'])) {
    $product_id = intval($_POST['product_id']);
    $name = $conn->real_escape_string($_POST['product_name']);
    $brand = $conn->real_escape_string($_POST['brand']);
    $price = floatval($_POST['price']);
    $product_category = $conn->real_escape_string($_POST['product_category'] ?? 'laptop');
    $related_to_category = $conn->real_escape_string($_POST['related_to_category'] ?? '');
    $category = $conn->real_escape_string($_POST['category'] ?? ''); // primary_use_case
    $cpu = $conn->real_escape_string($_POST['cpu'] ?? '');
    $gpu = $conn->real_escape_string($_POST['gpu'] ?? '');
    $ram_gb = intval($_POST['ram'] ?? 0);
    $storage_gb = intval($_POST['storage'] ?? 0);
    $storage_type = $conn->real_escape_string($_POST['storage_type'] ?? 'SSD');
    $display = $conn->real_escape_string($_POST['display'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $battery_life = $conn->real_escape_string($_POST['battery_life'] ?? '');
    
    // Get current image URL
    $stmt = $conn->prepare("SELECT image_url FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_product = $result->fetch_assoc();
    $stmt->close();
    
    $image_url = $current_product['image_url']; // Keep current image by default
    
    // Handle new image upload if provided
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $file = $_FILES['product_image'];
        
        // Validate file type (MIME type check)
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/avif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime_type, $allowed_types)) {
            $error_message = "Invalid image type. Only JPG, PNG, WEBP, and AVIF are allowed.";
        } else {
            // Validate file size (5MB maximum)
            $max_size = 5 * 1024 * 1024;
            if ($file['size'] > $max_size) {
                $error_message = "Image file size exceeds 5MB limit.";
            } else {
                // Validate image dimensions - RESTRICTION REMOVED
                $image_info = getimagesize($file['tmp_name']);
                if ($image_info !== false) {
                    // Restriction removed per user request
                    if (false) {
                        $error_message = "Image dimensions too small...";
                    } else {
                        // Upload new image
                        $upload_dir = '../LaptopAdvisor/images/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        
                        $extension_map = [
                            'image/jpeg' => 'jpg',
                            'image/jpg' => 'jpg',
                            'image/png' => 'png',
                            'image/webp' => 'webp',
                            'image/avif' => 'avif'
                        ];
                        $safe_extension = $extension_map[$mime_type];
                        $new_filename = uniqid('product_') . '.' . $safe_extension;
                        $upload_path = $upload_dir . $new_filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                            // Delete old image if it exists
                            if (!empty($current_product['image_url'])) {
                                $old_image_path = '../' . $current_product['image_url'];
                                if (file_exists($old_image_path)) {
                                    unlink($old_image_path);
                                }
                            }
                            // Update to new image path
                            $image_url = 'LaptopAdvisor/images/' . $new_filename;
                        } else {
                            $error_message = "Failed to upload new image.";
                        }
                    }
                } else {
                    $error_message = "Invalid image file.";
                }
            }
        }
    }
    
    // Only update if no image upload errors
    if (!isset($error_message)) {
        $sql = "UPDATE products SET 
                product_name = '$name', 
                brand = '$brand', 
                price = $price, 
                product_category = '$product_category', 
                related_to_category = " . ($related_to_category ? "'$related_to_category'" : "NULL") . ", 
                primary_use_case = '$category', 
                cpu = '$cpu', 
                gpu = '$gpu', 
                ram_gb = $ram_gb, 
                storage_gb = $storage_gb, 
                storage_type = '$storage_type', 
                display_size = '$display', 
                battery_life = '$battery_life',
                description = '$description', 
                image_url = '$image_url' 
                WHERE product_id = $product_id";
        
        if ($conn->query($sql)) {
            // Handle Video URL Update
            $video_url = trim($_POST['video_url'] ?? '');
            $check_video = $conn->query("SELECT media_id FROM product_media WHERE product_id = $product_id AND media_type = 'video'");
            
            if (!empty($video_url) && filter_var($video_url, FILTER_VALIDATE_URL)) {
                if ($check_video && $check_video->num_rows > 0) {
                    $video_stmt = $conn->prepare("UPDATE product_media SET media_url = ? WHERE product_id = ? AND media_type = 'video'");
                    $video_stmt->bind_param("si", $video_url, $product_id);
                    $video_stmt->execute();
                    $video_stmt->close();
                } else {
                    $video_stmt = $conn->prepare("INSERT INTO product_media (product_id, media_type, media_url, display_order) VALUES (?, 'video', ?, 100)");
                    $video_stmt->bind_param("is", $product_id, $video_url);
                    $video_stmt->execute();
                    $video_stmt->close();
                }
            } elseif (empty($video_url) && $check_video && $check_video->num_rows > 0) {
                $conn->query("DELETE FROM product_media WHERE product_id = $product_id AND media_type = 'video'");
            }

            // Handle Additional Images Upload
            if (isset($_FILES['additional_images'])) {
                $upload_dir = '../LaptopAdvisor/images/';
                $order_res = $conn->query("SELECT MAX(display_order) as max_order FROM product_media WHERE product_id = $product_id");
                $row = $order_res->fetch_assoc();
                $display_order = ($row['max_order'] ?? 0) + 1;

                foreach ($_FILES['additional_images']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['additional_images']['error'][$key] == 0) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime_type = finfo_file($finfo, $tmp_name);
                        finfo_close($finfo);
                        
                        if (in_array($mime_type, ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/avif'])) {
                            $extension_map = ['image/jpeg'=>'jpg', 'image/jpg'=>'jpg', 'image/png'=>'png', 'image/webp'=>'webp', 'image/avif'=>'avif'];
                            $safe_extension = $extension_map[$mime_type];
                            $new_filename = uniqid('product_' . $product_id . '_') . '.' . $safe_extension;
                            
                            if (move_uploaded_file($tmp_name, $upload_dir . $new_filename)) {
                                $media_url = 'LaptopAdvisor/images/' . $new_filename;
                                $media_stmt = $conn->prepare("INSERT INTO product_media (product_id, media_type, media_url, display_order) VALUES (?, 'image', ?, ?)");
                                $media_stmt->bind_param("isi", $product_id, $media_url, $display_order);
                                $media_stmt->execute();
                                $media_stmt->close();
                                $display_order++;
                            }
                        }
                    }
                }
            }

            $success_message = "Product updated successfully!";
            logActivity($conn, $_SESSION['admin_id'], 'update', 'products', "Updated product: $name", 'product', $product_id);
        } else {
            $error_message = "Error updating product: " . $conn->error;
        }
    }
}

// Handle Bulk Delete Action
if (isset($_POST['bulk_delete'])) {
    $product_ids = $_POST['product_ids'] ?? [];
    if (!empty($product_ids)) {
        // Check for products in orders
        $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
        $types = str_repeat('i', count($product_ids));
        
        $check_stmt = $conn->prepare("SELECT DISTINCT product_id FROM order_items WHERE product_id IN ($placeholders)");
        $check_stmt->bind_param($types, ...$product_ids);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        $ordered_ids = [];
        while ($row = $check_result->fetch_assoc()) {
            $ordered_ids[] = $row['product_id'];
        }
        $check_stmt->close();
        
        // Filter out products that are in orders
        $ids_to_delete = array_values(array_diff($product_ids, $ordered_ids));
        
        if (empty($ids_to_delete)) {
            $error_message = "Cannot delete selected products. All selected products are part of existing orders.";
        } else {
            // Phase 2: Get image URLs for deletion
            $placeholders_del = str_repeat('?,', count($ids_to_delete) - 1) . '?';
            $types_del = str_repeat('i', count($ids_to_delete));
            
            $stmt = $conn->prepare("SELECT image_url FROM products WHERE product_id IN ($placeholders_del)");
            $stmt->bind_param($types_del, ...$ids_to_delete);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $image_paths = [];
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['image_url'])) {
                    $image_paths[] = '../' . $row['image_url'];
                }
            }
            $stmt->close();
            
            // Delete products from database
            $stmt = $conn->prepare("DELETE FROM products WHERE product_id IN ($placeholders_del)");
            $stmt->bind_param($types_del, ...$ids_to_delete);
            
            if ($stmt->execute()) {
                // Delete associated image files
                foreach ($image_paths as $image_path) {
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }
                
                $success_message = count($ids_to_delete) . " product(s) deleted successfully.";
                if (count($ordered_ids) > 0) {
                    $success_message .= " " . count($ordered_ids) . " product(s) were skipped because they are in orders.";
                }
                
                logActivity($conn, $_SESSION['admin_id'], 'delete', 'products', "Bulk deleted " . count($ids_to_delete) . " products");
            } else {
                $error_message = "Error deleting products.";
            }
            $stmt->close();
        }
    }
}

// Handle Single Delete Action (Phase 2: with image deletion)
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    
    // Check if product is in any orders
    $check_stmt = $conn->prepare("SELECT product_id FROM order_items WHERE product_id = ? LIMIT 1");
    $check_stmt->bind_param("i", $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $is_ordered = $check_result->num_rows > 0;
    $check_stmt->close();

    if ($is_ordered) {
        $error_message = "Cannot delete product. It is part of existing orders and cannot be removed.";
    } else {
        // Get the image path before deleting the product
        $stmt = $conn->prepare("SELECT image_url FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        
        // Delete the product from database
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        
        if ($stmt->execute()) {
            // Delete the image file if it exists
            if (!empty($product['image_url'])) {
                $image_path = '../' . $product['image_url'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            $success_message = "Product deleted successfully.";
            logActivity($conn, $_SESSION['admin_id'], 'delete', 'products', "Deleted product ID: $product_id", 'product', $product_id);
        } else {
            $error_message = "Error deleting product.";
        }
        $stmt->close();
    }
}

// Handle Brand & Category Filtering
$filter_brand = $_GET['filter_brand'] ?? '';
$filter_category = $_GET['filter_category'] ?? '';

// Fetch unique brands and categories for dropdowns
$brands_query = "SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL AND brand != '' ORDER BY brand";
$brands_result = $conn->query($brands_query);
$brands = [];
while ($row = $brands_result->fetch_assoc()) {
    $brands[] = $row['brand'];
}

$categories_query = "SELECT DISTINCT primary_use_case FROM products WHERE primary_use_case IS NOT NULL AND primary_use_case != '' ORDER BY primary_use_case";
$categories_result = $conn->query($categories_query);
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row['primary_use_case'];
}

// Fetch Personas for Dropdowns
$persona_query = "SELECT * FROM personas ORDER BY name ASC";
$persona_result = $conn->query($persona_query);
$personas = [];
if ($persona_result->num_rows > 0) {
    while ($p = $persona_result->fetch_assoc()) {
        $personas[] = $p['name'];
    }
}

// Build dynamic query with filters
$query = "SELECT * FROM products WHERE is_active = 1";
$params = [];
$types = '';

if (!empty($filter_brand)) {
    $query .= " AND brand = ?";
    $params[] = $filter_brand;
    $types .= 's';
}

if (!empty($filter_category)) {
    $query .= " AND primary_use_case = ?";
    $params[] = $filter_category;
    $types .= 's';
}

$query .= " ORDER BY product_id DESC";

// Execute query with prepared statement if filters are set
if (!empty($params)) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}

$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Mock missing fields for UI
        $row['stock'] = $row['stock_quantity'];
        $row['min_stock'] = 10;
        $row['sku'] = 'SKU-' . str_pad($row['product_id'], 5, '0', STR_PAD_LEFT);
        
        // Determine status based on mocked stock
        if ($row['stock'] == 0) {
            $row['status_badge'] = '<span class="badge bg-danger">Out of Stock</span>';
            $row['stock_class'] = 'text-danger';
        } elseif ($row['stock'] < $row['min_stock']) {
            $row['status_badge'] = '<span class="badge bg-warning">Low Stock</span>';
            $row['stock_class'] = 'text-warning';
        } else {
            $row['status_badge'] = '<span class="badge bg-success">In Stock</span>';
            $row['stock_class'] = 'text-success';
        }
        
        $products[] = $row;
    }
}

// Calculate Stats
$total_products = count($products);
$in_stock = count(array_filter($products, function($p) { return $p['stock'] >= 10; }));
$low_stock = count(array_filter($products, function($p) { return $p['stock'] > 0 && $p['stock'] < 10; }));
$out_of_stock = count(array_filter($products, function($p) { return $p['stock'] == 0; }));



$page_title = "Product Management";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Smart Laptop Advisor Admin</title>
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="source/assets/css/bootstrap.css">
    <link rel="stylesheet" href="source/assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="source/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="source/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="source/assets/vendors/simple-datatables/style.css">
    <link rel="stylesheet" href="source/assets/css/app.css">
    <link rel="shortcut icon" href="source/assets/images/favicon.svg" type="image/x-icon">
    <style>
.modal { 
    z-index: 1055 !important; 
    position: fixed !important;
}
.modal-backdrop { 
    z-index: 1050 !important; 
    position: fixed !important;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5) !important;
}
.modal.show { 
    opacity: 1 !important; 
    display: block !important;
    visibility: visible !important;
}
/* Clean modal styling - no debug colors */
/* Custom CSS removed as it conflicts with Bootstrap and root placement */
</style>
</head>

<body>
    <div id="app">
        <?php require_once 'includes/admin_header.php'; ?>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Product Management</h3>
                <p class="text-subtitle text-muted">Manage your product catalog, inventory, and pricing</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Product Management</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Product Statistics -->
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon purple">
                                <i class="iconly-boldBag"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Total Products</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $total_products; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon green">
                                <i class="iconly-boldTick-Square"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">In Stock</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $in_stock; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon red">
                                <i class="iconly-boldDanger"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Low Stock</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $low_stock; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon blue">
                                <i class="iconly-boldBuy"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Out of Stock</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $out_of_stock; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-funnel me-2"></i>Brand <?php if ($filter_brand): ?><span class="badge bg-primary"><?= htmlspecialchars($filter_brand) ?></span><?php endif; ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?= empty($filter_brand) ? 'active' : '' ?>" href="admin_products.php<?= !empty($filter_category) ? '?filter_category=' . urlencode($filter_category) : '' ?>">All Brands</a></li>
                        <?php foreach ($brands as $brand): ?>
                            <li><a class="dropdown-item <?= $filter_brand === $brand ? 'active' : '' ?>" href="admin_products.php?filter_brand=<?= urlencode($brand) ?><?= !empty($filter_category) ? '&filter_category=' . urlencode($filter_category) : '' ?>"><?= htmlspecialchars($brand) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-tag me-2"></i>Category <?php if ($filter_category): ?><span class="badge bg-primary"><?= htmlspecialchars($filter_category) ?></span><?php endif; ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?= empty($filter_category) ? 'active' : '' ?>" href="admin_products.php<?= !empty($filter_brand) ? '?filter_brand=' . urlencode($filter_brand) : '' ?>">All Categories</a></li>
                        <?php foreach ($categories as $category): ?>
                            <li><a class="dropdown-item <?= $filter_category === $category ? 'active' : '' ?>" href="admin_products.php?filter_category=<?= urlencode($category) ?><?= !empty($filter_brand) ? '&filter_brand=' . urlencode($filter_brand) : '' ?>"><?= htmlspecialchars($category) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php if ($filter_brand || $filter_category): ?>
                <a href="admin_products.php" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle me-2"></i>Clear Filters
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                    <i class="bi bi-upload me-2"></i>Bulk Upload (CSV)
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus-circle me-2"></i>Add New Product
                </button>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Product List</h4>
                <div class="d-flex gap-2">
                    <a href="?action=export_csv<?= !empty($filter_brand) ? '&filter_brand=' . urlencode($filter_brand) : '' ?><?= !empty($filter_category) ? '&filter_category=' . urlencode($filter_category) : '' ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-download"></i> Export CSV
                    </a>
                    <button class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn" disabled onclick="bulkDeleteProducts()">
                        <i class="bi bi-trash"></i> Delete Selected
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" id="bulkDeleteForm">
                    <input type="hidden" name="bulk_delete" value="1">
                    <div class="table-responsive">
                        <table class="table table-striped" id="productsTable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><input type="checkbox" class="product-checkbox" name="product_ids[]" value="<?php echo $product['product_id']; ?>"></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md me-3">
                                                <?php 
                                                $image_src = 'source/assets/images/samples/placeholder.png'; // Default fallback
                                                if (!empty($product['image_url'])) {
                                                    $img_url = $product['image_url'];
                                                    if (strpos($img_url, 'http') === 0) {
                                                        $image_src = $img_url;
                                                    } elseif (strpos($img_url, 'LaptopAdvisor/') === 0) {
                                                        $image_src = '../' . $img_url;
                                                    } elseif (strpos($img_url, 'images/') === 0) {
                                                        $image_src = '../LaptopAdvisor/' . $img_url;
                                                    } else {
                                                        $image_src = '../LaptopAdvisor/images/' . basename($img_url);
                                                    }
                                                }
                                                ?>
                                                <img src="<?php echo htmlspecialchars($image_src); ?>" alt="Product" onerror="this.src='source/assets/images/samples/1.png'">
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($product['product_name']); ?></h6>
                                                <small class="text-muted"><?php echo htmlspecialchars($product['brand']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo $product['sku']; ?></td>
                                    <td><?php echo htmlspecialchars($product['primary_use_case']); ?></td>
                                    <td><span class="currency-price" data-base-price="<?php echo $product['price']; ?>">$<?php echo number_format($product['price'], 2); ?></span></td>
                                    <td class="<?php echo $product['stock_class']; ?> font-bold"><?php echo $product['stock']; ?></td>
                                    <td><?php echo $product['status_badge']; ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="openEditModal(<?php echo $product['product_id']; ?>)" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteProduct(<?php echo $product['product_id']; ?>)" 
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>







<?php include 'includes/admin_footer.php'; ?>

<script src="source/assets/vendors/simple-datatables/simple-datatables.js"></script>
<script>
    // Initialize DataTable with all features
    let table1 = document.querySelector('#productsTable');
    let dataTable = new simpleDatatables.DataTable(table1, {
        searchable: true,
        fixedHeight: false,
        perPage: 10,
        perPageSelect: [5, 10, 25, 50, 100],
        labels: {
            placeholder: "Search products...",
            noRows: "No products found",
            info: "Showing {start} to {end} of {rows} entries"
        },
        layout: {
            top: "{select}{search}",
            bottom: "{info}{pager}"
        }
    });

    // Re-initialize event listeners after DataTable renders
        // Re-initialize event listeners after DataTable renders
    function initializeEventListeners() {
        // 1. Select All functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            // Clone to remove old listeners to prevent duplicates
            const newSelectAll = selectAllCheckbox.cloneNode(true);
            selectAllCheckbox.parentNode.replaceChild(newSelectAll, selectAllCheckbox);
            
            newSelectAll.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.product-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkDeleteButton();
            });
        }

        // 2. Individual checkbox functionality
        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkDeleteButton();
                updateSelectAllCheckbox();
            });
        });

        // 3. FIX: Re-initialize ONLY the table row action dropdowns
        // Dispose any existing dropdown instances first to prevent conflicts
    }

    // Initialize after DataTable is ready
    initializeEventListeners();

    // Re-initialize after page changes
    dataTable.on('datatable.page', initializeEventListeners);
    dataTable.on('datatable.perpage', initializeEventListeners);
    dataTable.on('datatable.search', initializeEventListeners);

    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        bulkDeleteBtn.disabled = checkedBoxes.length === 0;
    }

    function updateSelectAllCheckbox() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const selectAllCheckbox = document.getElementById('selectAll');
        selectAllCheckbox.checked = checkboxes.length === checkedBoxes.length && checkboxes.length > 0;
    }

    // Bulk delete function
    function bulkDeleteProducts() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Please select at least one product to delete.');
            return;
        }
        
        if (confirm(`Are you sure you want to delete ${checkedBoxes.length} product(s)? This action cannot be undone.`)) {
            document.getElementById('bulkDeleteForm').submit();
        }
    }

    // Preview additional images in Add Product form
    function previewAdditionalImages(input) {
        const container = document.getElementById('imagePreviewContainer');
        container.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            Array.from(input.files).forEach((file, index) => {
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 col-6';
                        col.innerHTML = `
                            <div class="card">
                                <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover;" alt="Preview">
                                <div class="card-body p-2 text-center">
                                    <small class="text-muted">Image ${index + 1}</small>
                                </div>
                            </div>
                        `;
                        container.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    // Toggle laptop-only fields in Add Product form
    function toggleLaptopFields() {
        const productType = document.getElementById('productType').value;
        const laptopFields = document.querySelectorAll('.laptop-only-field');
        const displayFields = document.querySelectorAll('.display-field');
        const relatedCategoryGroup = document.getElementById('relatedCategoryGroup');
        
        // Hide all initially
        laptopFields.forEach(field => field.style.display = 'none');
        displayFields.forEach(field => field.style.display = 'none');
        relatedCategoryGroup.style.display = 'block';

        if (productType === 'laptop') {
            laptopFields.forEach(field => field.style.display = 'block');
            displayFields.forEach(field => field.style.display = 'block');
            relatedCategoryGroup.style.display = 'none';
        } else if (productType === 'monitor') {
            displayFields.forEach(field => field.style.display = 'block');
        }
    }

    // Toggle laptop-only fields in Edit Product form
    function toggleEditLaptopFields() {
        const productType = document.getElementById('editProductType').value;
        const laptopFields = document.querySelectorAll('.edit-laptop-only-field');
        const displayFields = document.querySelectorAll('.edit-display-field');
        const relatedCategoryGroup = document.getElementById('editRelatedCategoryGroup');
        
        // Hide all initially
        laptopFields.forEach(field => field.style.display = 'none');
        displayFields.forEach(field => field.style.display = 'none');
        if (relatedCategoryGroup) relatedCategoryGroup.style.display = 'block';

        if (productType === 'laptop' || productType === '') {
            laptopFields.forEach(field => field.style.display = 'block');
            displayFields.forEach(field => field.style.display = 'block');
            if (relatedCategoryGroup) relatedCategoryGroup.style.display = 'none';
        } else if (productType === 'monitor') {
            displayFields.forEach(field => field.style.display = 'block');
        }
    }

    // Product actions - Simplified without AJAX
    
    function deleteProduct(id) {
        if(confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            document.getElementById('deleteProductId').value = id;
            document.getElementById('deleteForm').submit();
        }
    }
    
        function openEditModal(productId) {
        // Reset form
        document.getElementById("editProductForm").reset();
        
        // Fetch product details
        fetch(`ajax/get_product_details.php?id=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const product = data.product;
                    
                    // Populate fields
                    document.getElementById("editProductId").value = product.product_id;
                    document.getElementById("editProductName").value = product.product_name;
                    document.getElementById("editProductBrand").value = product.brand;
                    document.getElementById("editProductType").value = product.product_category || "laptop";
                    document.getElementById("editRelatedCategory").value = product.related_to_category || "";
                    document.getElementById("editProductCategory").value = product.primary_use_case || "";
                    document.getElementById("editPrice").value = product.price;
                    document.getElementById("editProductCpu").value = product.cpu || "";
                    document.getElementById("editProductGpu").value = product.gpu || "";
                    document.getElementById("editProductRam").value = product.ram_gb || "";
                    document.getElementById("editProductStorage").value = product.storage_gb || "";
                    document.getElementById("editProductStorageType").value = product.storage_type || "SSD";
                    document.getElementById("editProductDisplay").value = product.display_size || "";
                    document.getElementById("editProductDescription").value = product.description || "";
                    
                    // Handle Image Preview
                    const imagePreview = document.querySelector("#editProductModal .img-thumbnail");
                    const imageContainer = document.querySelector("#editProductModal .mb-2");
                    
                    if (product.image_url) {
                        let imgSrc = product.image_url;
                        if (imgSrc.startsWith("http")) {
                            // Keep as is
                        } else if (imgSrc.startsWith("LaptopAdvisor/")) {
                            imgSrc = "../" + imgSrc;
                        } else if (imgSrc.startsWith("images/")) {
                            imgSrc = "../LaptopAdvisor/" + imgSrc;
                        } else {
                            imgSrc = "../LaptopAdvisor/images/" + imgSrc;
                        }
                        if (imagePreview) {
                            imagePreview.src = imgSrc;
                            imagePreview.parentElement.style.display = "block";
                        } else {
                            imageContainer.innerHTML = `<img src="${imgSrc}" class="img-thumbnail" style="max-height: 150px;" alt="Current product image">`;
                        }
                    } else {
                        if (imagePreview) {
                            imagePreview.parentElement.style.display = "none";
                        } else {
                             imageContainer.innerHTML = "<p class=\"text-muted\">No image available</p>";
                        }
                    }

                    // Handle Video URL
                    let videoUrl = '';
                    if (data.media && data.media.length > 0) {
                        const video = data.media.find(m => m.media_type === 'video');
                        if (video) videoUrl = video.media_url;
                    }
                    const videoInput = document.getElementById('editProductVideoUrl');
                    if (videoInput) videoInput.value = videoUrl;

                    // Handle Additional Images Display
                    const existingImagesContainer = document.getElementById('editExistingImages');
                    if (existingImagesContainer) {
                        existingImagesContainer.innerHTML = '';
                        if (data.media && data.media.length > 0) {
                            const images = data.media.filter(m => m.media_type === 'image');
                            if (images.length > 0) {
                                let imagesHtml = '<label class="form-label d-block mt-2">Existing Additional Images:</label><div class="d-flex flex-wrap gap-3 mb-3">';
                                images.forEach(img => {
                                    let url = img.media_url;
                                    if (url.startsWith('http')) {
                                        // Keep as is
                                    } else if (url.startsWith('LaptopAdvisor/')) {
                                        url = '../' + url;
                                    } else if (url.startsWith('images/')) {
                                        url = '../LaptopAdvisor/' + url;
                                    } else if (!url.startsWith('../')) {
                                        url = '../LaptopAdvisor/images/' + url;
                                    }
                                    
                                    imagesHtml += `
                                        <div class="position-relative" id="media-${img.media_id}" style="width: 100px; height: 100px;">
                                            <img src="${url}" class="img-thumbnail w-100 h-100" style="object-fit: cover;">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 d-flex justify-content-center align-items-center shadow-sm" 
                                                    style="width: 22px; height: 22px; border-radius: 50%; transform: translate(30%, -30%); border: 2px solid white;"
                                                    onclick="deleteProductMedia(${img.media_id})" title="Delete Image">
                                                <i class="bi bi-x" style="font-size: 16px; line-height: 1;"></i>
                                            </button>
                                        </div>
                                    `;
                                });
                                imagesHtml += '</div>';
                                existingImagesContainer.innerHTML = imagesHtml;
                            }
                        }
                    }

                    // Toggle fields based on type
                    toggleEditLaptopFields();
                    
                    // Show Modal
                    const modal = new bootstrap.Modal(document.getElementById("editProductModal"));
                    modal.show();
                } else {
                    alert("Error fetching product details: " + data.error);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Failed to load product details.");
            });
    }

    // CSV Upload - Now handled by form submission directly
    // The bulkUploadForm will submit via POST and the backend handler at lines 42-88 will process it

    // Phase 3: Add Product Form Validation
    const addProductForm = document.getElementById('addProductForm');
    if (addProductForm) {
        addProductForm.addEventListener('submit', function(e) {
            // Get required fields
            const productName = document.getElementById('productName').value.trim();
            const brand = document.getElementById('productBrand').value.trim();
            const category = document.getElementById('productCategory').value;
            const price = parseFloat(document.getElementById('price').value);
            
            // Validation
            if (!productName || productName.length < 3) {
                e.preventDefault();
                alert('Product name must be at least 3 characters long.');
                document.getElementById('productName').focus();
                return false;
            }
            
            if (!brand || brand.length < 2) {
                e.preventDefault();
                alert('Please enter a valid brand name.');
                document.getElementById('productBrand').focus();
                return false;
            }
            
            if (!category) {
                e.preventDefault();
                alert('Please select a category.');
                document.getElementById('productCategory').focus();
                return false;
            }
            
            if (!price || price <= 0) {
                e.preventDefault();
                alert('Please enter a valid price greater than 0.');
                document.getElementById('price').focus();
                return false;
            }
            
            // Optional: Validate image size
            const imageInput = document.getElementById('image');
            if (imageInput.files.length > 0) {
                const fileSize = imageInput.files[0].size / 1024 / 1024; // in MB
                const fileName = imageInput.files[0].name;
                const allowedExtensions = /\.(jpg|jpeg|png|webp)$/i;
                
                if (!allowedExtensions.test(fileName)) {
                    e.preventDefault();
                    alert('Please upload a valid image file (JPG, PNG, or WEBP).');
                    imageInput.value = '';
                    return false;
                }
                
                if (fileSize > 5) {
                    e.preventDefault();
                    alert('Image file size must be less than 5MB. Your file is ' + fileSize.toFixed(2) + 'MB');
                    imageInput.value = '';
                    return false;
                }
            }
            
            // Show loading state
            // Show loading state
            const submitBtn = document.querySelector('button[form="addProductForm"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            }
            
            return true;
        });
        
        // Reset form when modal is closed
        const addProductModal = document.getElementById('addProductModal');
        if (addProductModal) {
            addProductModal.addEventListener('hidden.bs.modal', function () {
                addProductForm.reset();
                const submitBtn = document.querySelector('button[form="addProductForm"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-save me-2"></i>Save Product';
                }
            });
        }
    }

    function deleteProductMedia(mediaId) {
        if (confirm('Are you sure you want to delete this image?')) {
            fetch('ajax/delete_product_media.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ media_id: mediaId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const element = document.getElementById(`media-${mediaId}`);
                    if (element) element.remove();
                } else {
                    alert('Failed to delete image: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the image.');
            });
        }
    }
</script>

        </div>
    </div>
<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="addProductForm">
                    <input type="hidden" name="add_product" value="1">

                                    <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="productName">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="productName" name="product_name" placeholder="e.g., Dell XPS 15" required>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="productBrand">Brand <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="productBrand" name="brand" placeholder="e.g., Dell, HP, Apple, Lenovo" required>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="productType">Product Type <span class="text-danger">*</span></label>
                                            <select class="form-select" id="productType" name="product_category" required onchange="toggleLaptopFields()">
                                                <option value="">Select Product Type</option>
                                                <option value="laptop" selected>Laptop</option>
                                                <option value="mouse">Mouse</option>
                                                <option value="keyboard">Keyboard</option>                    
                                                <option value="headset">Headset</option>
                                                <option value="monitor">Monitor</option>
                                                <option value="bag">Laptop Bag</option>
                                                <option value="mousepad">Mousepad</option>
                                                <option value="webcam">Webcam</option>
                                                <option value="other">Other</option>
                                        </select>
                                <small class="text-muted">Choose whether this is a laptop or an accessory</small>
                            </div>
                            
                                    <div class="form-group mb-3" id="relatedCategoryGroup" style="display: none;">
                                        <label for="relatedCategory">Related To (For Accessories)</label>
                                        <select class="form-select" id="relatedCategory" name="related_to_category">
                                            <option value="">Select Category</option>
                                            <?php foreach ($personas as $persona): ?>
                                            <option value="<?= htmlspecialchars($persona) ?>"><?= htmlspecialchars($persona) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">What type of users is this accessory designed for?</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="productCategory">Use Case / Target Audience</label>
                                        <select class="form-select" id="productCategory" name="category" required>
                                            <option value="">Select Use Case</option>
                                            <?php foreach ($personas as $persona): ?>
                                            <option value="<?= htmlspecialchars($persona) ?>"><?= htmlspecialchars($persona) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">Primary use case or target audience</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="price">Price ($) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="price" name="price" placeholder="1299.99" step="0.01" min="0" required>
                                    </div>
                                    
                                    <div class="form-group mb-3 laptop-only-field">
                                        <label for="productCpu">CPU / Processor</label>
                                        <input type="text" class="form-control" id="productCpu" name="cpu" placeholder="e.g., Intel Core i7-13700H">
                                    </div>
                                    
                                    <div class="form-group mb-3 laptop-only-field">
                                        <label for="productGpu">GPU / Graphics Card</label>
                                        <input type="text" class="form-control" id="productGpu" name="gpu" placeholder="e.g., NVIDIA RTX 4060">
                                    </div>
                                </div>
                                
                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3 laptop-only-field">
                                        <label for="productRam">RAM (GB)</label>
                                        <input type="number" class="form-control" id="productRam" name="ram" placeholder="16" min="1" max="128">
                                        <small class="text-muted">Enter RAM capacity in gigabytes</small>
                                    </div>
                                    
                                    <div class="form-group mb-3 laptop-only-field">
                                        <label for="productStorage">Storage Capacity (GB)</label>
                                        <input type="number" class="form-control" id="productStorage" name="storage" placeholder="512" min="1" max="8192">
                                        <small class="text-muted">Enter storage size in gigabytes</small>
                                    </div>
                                    
                                    <div class="form-group mb-3 laptop-only-field">
                                        <label for="productStorageType">Storage Type</label>
                                        <select class="form-select" id="productStorageType" name="storage_type">
                                            <option value="SSD" selected>SSD (Solid State Drive)</option>
                                            <option value="HDD">HDD (Hard Disk Drive)</option>
                                            <option value="Hybrid">Hybrid (SSD + HDD)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-3 display-field">
                                        <label for="productDisplay">Display Size (inches)</label>
                                        <input type="number" class="form-control" id="productDisplay" name="display" placeholder="15.6" step="0.1">
                                    </div>

                                    <div class="form-group mb-3 laptop-only-field">
                                        <label for="productBattery">🔋 Battery Life</label>
                                        <input type="text" class="form-control" id="productBattery" name="battery_life" placeholder="e.g., Up to 10 hours">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="image">Primary Product Image</label>
                                        <input type="file" class="form-control" id="image" name="product_image" accept="image/png,image/jpeg,image/jpg,image/webp,image/avif">
                                        <small class="text-muted">Accepted formats: JPG, PNG, WEBP, AVIF (Max 5MB)</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label>Additional Product Images (Optional)</label>
                                        <input type="file" class="form-control" id="additionalImages" name="additional_images[]" multiple accept="image/png,image/jpeg,image/jpg,image/webp,image/avif" onchange="previewAdditionalImages(this)">
                                        <small class="text-muted">Upload multiple images (Max 5MB each)</small>
                                        <div id="imagePreviewContainer" class="row g-2 mt-2"></div>
                                    </div>
                                </div>
                                
                                <!-- Full Width Description -->
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="productDescription">Product Description</label>
                                        <textarea class="form-control" id="productDescription" name="description" rows="3" placeholder="Enter detailed product information, features, and specifications..."></textarea>
                                    </div>
                                    
                                    <!-- Video URL Section -->
                                    <div class="form-group mb-3">
                                        <label for="productVideoUrl">Product Video URL (Optional)</label>
                                        <input type="url" class="form-control" id="productVideoUrl" name="video_url" placeholder="https://youtube.com/watch?v=... or direct video link">
                                        <small class="text-muted">YouTube, Vimeo, or direct video URL</small>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="addProductForm" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Product
                        </button>
                    </div>
                </form> <!-- Correctly close form here -->
            </div>
        </div>
    </div>
</div>


<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="editProductForm" action="admin_products.php">
                    <input type="hidden" name="edit_product" value="1">
                    <input type="hidden" name="product_id" id="editProductId">
                    
                        <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="editProductName">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editProductName" name="product_name" placeholder="e.g., Dell XPS 15" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="editProductBrand">Brand <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editProductBrand" name="brand" placeholder="e.g., Dell, HP, Apple, Lenovo" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="editProductType">Product Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="editProductType" name="product_category" required onchange="toggleEditLaptopFields()">
                                    <option value="">Select Product Type</option>
                                    <option value="laptop">Laptop</option>
                                    <option value="mouse">Mouse</option>
                                    <option value="keyboard">Keyboard</option>
                                    <option value="headset">Headset</option>
                                    <option value="monitor">Monitor</option>
                                    <option value="bag">Laptop Bag</option>
                                    <option value="mousepad">Mousepad</option>
                                    <option value="webcam">Webcam</option>
                                    <option value="other">Other</option>
                                </select>
                                <small class="text-muted">Choose whether this is a laptop or an accessory</small>
                            </div>
                            
                            <div class="form-group mb-3" id="editRelatedCategoryGroup">
                                <label for="editRelatedCategory">Related To (For Accessories)</label>
                                <select class="form-select" id="editRelatedCategory" name="related_to_category">
                                    <option value="">Select Category</option>
                                    <?php foreach ($personas as $persona): ?>
                                    <option value="<?= htmlspecialchars($persona) ?>"><?= htmlspecialchars($persona) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">What type of users is this accessory designed for?</small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="editProductCategory">Use Case / Target Audience</label>
                                <select class="form-select" id="editProductCategory" name="category" required>
                                    <option value="">Select Use Case</option>
                                    <?php foreach ($personas as $persona): ?>
                                    <option value="<?= htmlspecialchars($persona) ?>"><?= htmlspecialchars($persona) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Primary use case or target audience</small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="editPrice">Price ($) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editPrice" name="price" placeholder="1299.99" step="0.01" min="0" required>
                            </div>
                            
                            <div class="form-group mb-3 edit-laptop-only-field">
                                <label for="editProductCpu">CPU / Processor</label>
                                <input type="text" class="form-control" id="editProductCpu" name="cpu" placeholder="e.g., Intel Core i7-13700H">
                            </div>
                            
                            <div class="form-group mb-3 edit-laptop-only-field">
                                <label for="editProductGpu">GPU / Graphics Card</label>
                                <input type="text" class="form-control" id="editProductGpu" name="gpu" placeholder="e.g., NVIDIA RTX 4060">
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="form-group mb-3 edit-laptop-only-field">
                                <label for="editProductRam">RAM (GB)</label>
                                <input type="number" class="form-control" id="editProductRam" name="ram" placeholder="16" min="1" max="128">
                                <small class="text-muted">Enter RAM capacity in gigabytes</small>
                            </div>
                            
                            <div class="form-group mb-3 edit-laptop-only-field">
                                <label for="editProductStorage">Storage Capacity (GB)</label>
                                <input type="number" class="form-control" id="editProductStorage" name="storage" placeholder="512" min="1" max="8192">
                                <small class="text-muted">Enter storage size in gigabytes</small>
                            </div>
                            
                            <div class="form-group mb-3 edit-laptop-only-field">
                                <label for="editProductStorageType">Storage Type</label>
                                <select class="form-select" id="editProductStorageType" name="storage_type">
                                    <option value="SSD">SSD (Solid State Drive)</option>
                                    <option value="HDD">HDD (Hard Disk Drive)</option>
                                    <option value="Hybrid">Hybrid (SSD + HDD)</option>
                                </select>
                            </div>
                            
                            <div class="form-group mb-3 edit-display-field">
                                <label for="editProductDisplay">Display Size (inches)</label>
                                <input type="number" class="form-control" id="editProductDisplay" name="display" placeholder="15.6" step="0.1">
                            </div>

                            <div class="form-group mb-3 edit-laptop-only-field">
                                <label for="editProductBattery">🔋 Battery Life</label>
                                <input type="text" class="form-control" id="editProductBattery" name="battery_life" placeholder="e.g., Up to 10 hours">
                            </div>
                            
                            <div class="form-group mb-3">
                                <label>Current Primary Image</label>
                                <div class="mb-2">
                                    <img src="" class="img-thumbnail" style="max-height: 150px; display: none;" alt="Current product image">
                                </div>
                                <label for="editImage">Replace Primary Image (optional)</label>
                                <input type="file" class="form-control" id="editImage" name="product_image" accept="image/png,image/jpeg,image/jpg,image/webp,image/avif">
                                <small class="text-muted">Leave empty to keep current image. Accepted formats: JPG, PNG, WEBP, AVIF (Max 5MB)</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="editAdditionalImages">Add More Images (Optional)</label>
                                <input type="file" class="form-control" id="editAdditionalImages" name="additional_images[]" multiple accept="image/png,image/jpeg,image/jpg,image/webp,image/avif">
                                <small class="text-muted">Upload new images to add to the gallery</small>
                            </div>
                            <div id="editExistingImages"></div>
                        </div>
                        
                        <!-- Full Width Description -->
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="editProductDescription">Product Description</label>
                                <textarea class="form-control" id="editProductDescription" name="description" rows="3" placeholder="Enter detailed product information, features, and specifications..."></textarea>
                            </div>

                            <!-- Video URL Section -->
                            <div class="form-group mb-3">
                                <label for="editProductVideoUrl">Product Video URL (Optional)</label>
                                <input type="url" class="form-control" id="editProductVideoUrl" name="video_url" placeholder="https://youtube.com/watch?v=... or direct video link">
                                <small class="text-muted">YouTube, Vimeo, or direct video URL</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="admin_products.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" form="editProductForm" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Update Product
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload Products (CSV)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="bulkUploadForm">
                    <input type="hidden" name="bulk_upload_csv" value="1">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="csvFile" class="form-label">Upload CSV File <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="csv_file" id="csvFile" accept=".csv" required>
                                <small class="text-muted">Please ensure your CSV follows the required format below</small>
                            </div>
                            
                            <div class="alert alert-info mb-3">
                                <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>CSV Format Requirements</h6>
                                <p class="mb-2"><strong>Your CSV should include these columns (in order):</strong></p>
                                <div class="bg-white p-2 rounded mb-3">
                                    <code class="text-dark">Name, Brand, Price, Product_Type, Related_To, Use_Case, CPU, GPU, RAM_GB, Storage_GB, Storage_Type, Display, Description</code>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-4 mb-2">
                                        <strong>Product_Type:</strong>
                                        <p class="small mb-0">laptop, mouse, keyboard, headset, monitor, bag, mousepad, webcam, other</p>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <strong>Related_To:</strong>
                                        <p class="small mb-0">(For accessories only) <?php echo implode(', ', $personas); ?></p>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <strong>Use_Case:</strong>
                                        <p class="small mb-0"><?php echo implode(', ', $personas); ?></p>
                                    </div>
                                </div>
                                
                                <hr class="my-3">
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-0 small text-muted"><i class="bi bi-lightbulb me-1"></i>Need an example? Download our sample template:</p>
                                    <a href="sample_products.csv" class="btn btn-sm btn-outline-primary" download>
                                        <i class="bi bi-download me-2"></i>Download Sample CSV
                                    </a>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning">
                                <h6 class="mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Important Notes</h6>
                                <ul class="mb-0 small">
                                    <li>Ensure all required fields (Name, Brand, Price, Product_Type) are filled</li>
                                    <li>For laptops, include CPU, GPU, RAM, Storage, and Display specifications</li>
                                    <li>For accessories, specify the Related_To category</li>
                                    <li>Price should be in decimal format (e.g., 1299.99)</li>
                                    <li>RAM and Storage should be in GB (e.g., 16, 512)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="bulkUploadForm" class="btn btn-primary">
                    <i class="bi bi-upload me-2"></i>Upload Products
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form (Hidden) -->
<form method="POST" id="deleteForm" style="display: none;">
    <input type="hidden" name="product_id" id="deleteProductId">
    <input type="hidden" name="delete_product" value="1">
</form>
</body>
</html> 
