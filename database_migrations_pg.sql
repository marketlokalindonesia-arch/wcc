-- Add missing columns and tables for POS functionality (PostgreSQL)

-- Add barcode column to products table
ALTER TABLE products ADD COLUMN IF NOT EXISTS barcode VARCHAR(100) DEFAULT NULL;
CREATE INDEX IF NOT EXISTS idx_barcode ON products(barcode);

-- Add created_by column to orders table
ALTER TABLE orders ADD COLUMN IF NOT EXISTS created_by INTEGER DEFAULT NULL;

-- Modify users table to include cashier role (PostgreSQL doesn't support MODIFY, we need to drop and recreate)
DO $$ 
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM pg_type t
        JOIN pg_enum e ON t.oid = e.enumtypid
        WHERE t.typname = 'user_role_enum' AND e.enumlabel = 'cashier'
    ) THEN
        ALTER TYPE user_role_enum ADD VALUE 'cashier';
    END IF;
EXCEPTION
    WHEN undefined_object THEN
        -- Type doesn't exist, will be created when users table is created
        NULL;
END $$;

-- Create transactions table
CREATE TABLE IF NOT EXISTS transactions (
  id SERIAL PRIMARY KEY,
  order_id INTEGER DEFAULT NULL,
  transaction_type VARCHAR(20) CHECK (transaction_type IN ('sale', 'refund', 'void')) DEFAULT 'sale',
  amount DECIMAL(10,2) NOT NULL,
  payment_method VARCHAR(100) DEFAULT NULL,
  cashier_id INTEGER DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_transactions_order ON transactions(order_id);
CREATE INDEX IF NOT EXISTS idx_transactions_cashier ON transactions(cashier_id);

-- Create inventory_logs table
CREATE TABLE IF NOT EXISTS inventory_logs (
  id SERIAL PRIMARY KEY,
  product_id INTEGER NOT NULL,
  user_id INTEGER DEFAULT NULL,
  action_type VARCHAR(20) CHECK (action_type IN ('purchase', 'sale', 'adjustment', 'return')) DEFAULT 'adjustment',
  quantity_change INTEGER NOT NULL,
  notes TEXT DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_inventory_logs_product ON inventory_logs(product_id);
CREATE INDEX IF NOT EXISTS idx_inventory_logs_user ON inventory_logs(user_id);
