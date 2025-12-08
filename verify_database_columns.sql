-- Run this in phpMyAdmin to verify all required columns exist

-- Check products table for stock_quantity
SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'products' 
AND TABLE_SCHEMA = 'laptop_advisor_db'
AND COLUMN_NAME = 'stock_quantity';

-- Check orders table for shipping columns
SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'orders' 
AND TABLE_SCHEMA = 'laptop_advisor_db'
AND COLUMN_NAME IN ('shipping_name', 'shipping_address', 'shipping_city', 
                     'shipping_state', 'shipping_zip', 'shipping_country', 'shipping_phone');

-- Count how many shipping columns exist (should be 7)
SELECT COUNT(*) as shipping_columns_count
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'orders' 
AND TABLE_SCHEMA = 'laptop_advisor_db'
AND COLUMN_NAME LIKE 'shipping%';





