-- Add shipping address columns to orders table
-- Run this in phpMyAdmin to fix the checkout error

ALTER TABLE `orders` 
ADD COLUMN `shipping_name` VARCHAR(255) DEFAULT NULL AFTER `order_status`,
ADD COLUMN `shipping_address` VARCHAR(500) DEFAULT NULL AFTER `shipping_name`,
ADD COLUMN `shipping_city` VARCHAR(100) DEFAULT NULL AFTER `shipping_address`,
ADD COLUMN `shipping_state` VARCHAR(100) DEFAULT NULL AFTER `shipping_city`,
ADD COLUMN `shipping_zip` VARCHAR(20) DEFAULT NULL AFTER `shipping_state`,
ADD COLUMN `shipping_country` VARCHAR(100) DEFAULT NULL AFTER `shipping_zip`,
ADD COLUMN `shipping_phone` VARCHAR(20) DEFAULT NULL AFTER `shipping_country`;

-- Verify the changes
DESCRIBE orders;

-- Check if the columns were added successfully
SELECT COLUMN_NAME, COLUMN_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'orders' 
AND TABLE_SCHEMA = 'laptop_advisor_db';





