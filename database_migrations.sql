-- Add missing columns and tables for POS functionality

-- Add barcode column to products table
ALTER TABLE products ADD COLUMN IF NOT EXISTS barcode VARCHAR(100) DEFAULT NULL;
CREATE INDEX IF NOT EXISTS idx_barcode ON products(barcode);

-- Add created_by column to orders table
ALTER TABLE orders ADD COLUMN IF NOT EXISTS created_by INT(11) DEFAULT NULL;

-- Modify users table to include cashier role
ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'vendor', 'admin', 'cashier') DEFAULT 'customer';

-- Create transactions table
CREATE TABLE IF NOT EXISTS transactions (
  id INT(11) NOT NULL AUTO_INCREMENT,
  order_id INT(11) DEFAULT NULL,
  transaction_type ENUM('sale', 'refund', 'void') DEFAULT 'sale',
  amount DECIMAL(10,2) NOT NULL,
  payment_method VARCHAR(100) DEFAULT NULL,
  cashier_id INT(11) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY order_id (order_id),
  KEY cashier_id (cashier_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create inventory_logs table
CREATE TABLE IF NOT EXISTS inventory_logs (
  id INT(11) NOT NULL AUTO_INCREMENT,
  product_id INT(11) NOT NULL,
  user_id INT(11) DEFAULT NULL,
  action_type ENUM('purchase', 'sale', 'adjustment', 'return') DEFAULT 'adjustment',
  quantity_change INT(11) NOT NULL,
  notes TEXT DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY product_id (product_id),
  KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
