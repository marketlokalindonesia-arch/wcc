-- PostgreSQL Database Export: neondb
-- Generated on 2025-10-25 20:55:25

CREATE TABLE order_items (
    id integer,
    order_id integer,
    product_id integer,
    product_name character varying(255),
    product_price numeric(10,2),
    quantity integer,
    subtotal numeric(10,2)
);

INSERT INTO "order_items" ("id","order_id","product_id","product_name","product_price","quantity","subtotal") VALUES ('1','1','3','Samsung Smart TV 55"','7500000.00','1','7500000.00');
INSERT INTO "order_items" ("id","order_id","product_id","product_name","product_price","quantity","subtotal") VALUES ('2','1','8','Gaming Mouse Logitech','650000.00','2','1300000.00');
INSERT INTO "order_items" ("id","order_id","product_id","product_name","product_price","quantity","subtotal") VALUES ('3','2','1','Laptop Gaming ASUS ROG','14500000.00','1','14500000.00');
INSERT INTO "order_items" ("id","order_id","product_id","product_name","product_price","quantity","subtotal") VALUES ('4','3','6','Sony WH-1000XM5','5000000.00','1','5000000.00');
INSERT INTO "order_items" ("id","order_id","product_id","product_name","product_price","quantity","subtotal") VALUES ('5','4','4','Nike Air Max 270','2500000.00','1','2500000.00');
INSERT INTO "order_items" ("id","order_id","product_id","product_name","product_price","quantity","subtotal") VALUES ('6','4','5','Adidas Original Hoodie','750000.00','1','750000.00');
INSERT INTO "order_items" ("id","order_id","product_id","product_name","product_price","quantity","subtotal") VALUES ('7','5','7','MacBook Pro M3','25000000.00','1','25000000.00');
INSERT INTO "order_items" ("id","order_id","product_id","product_name","product_price","quantity","subtotal") VALUES ('8','5','9','Mechanical Keyboard','1200000.00','1','1200000.00');
INSERT INTO "order_items" ("id","order_id","product_id","product_name","product_price","quantity","subtotal") VALUES ('9','6','4','Nike Air Max 270','2500000.00','1','2500000.00');

CREATE TABLE product_categories (
    id integer,
    name character varying(255),
    slug character varying(255),
    description text,
    parent_id integer,
    created_at timestamp without time zone
);

INSERT INTO "product_categories" ("id","name","slug","description","parent_id","created_at") VALUES ('1','Electronics','electronics','Electronic devices and accessories',NULL,'2025-10-25 17:58:37.487239');
INSERT INTO "product_categories" ("id","name","slug","description","parent_id","created_at") VALUES ('2','Fashion','fashion','Clothing and accessories',NULL,'2025-10-25 17:58:37.487239');
INSERT INTO "product_categories" ("id","name","slug","description","parent_id","created_at") VALUES ('3','Home & Garden','home-garden','Home and garden products',NULL,'2025-10-25 17:58:37.487239');
INSERT INTO "product_categories" ("id","name","slug","description","parent_id","created_at") VALUES ('4','Sports','sports','Sports equipment and accessories',NULL,'2025-10-25 17:58:37.487239');
INSERT INTO "product_categories" ("id","name","slug","description","parent_id","created_at") VALUES ('5','Books','books','Books and magazines',NULL,'2025-10-25 17:58:37.487239');
INSERT INTO "product_categories" ("id","name","slug","description","parent_id","created_at") VALUES ('6','Toys','toys','Toys and games',NULL,'2025-10-25 17:58:37.487239');

CREATE TABLE users (
    id integer,
    username character varying(100),
    email character varying(255),
    password character varying(255),
    first_name character varying(100),
    last_name character varying(100),
    role character varying(20),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);

INSERT INTO "users" ("id","username","email","password","first_name","last_name","role","created_at","updated_at") VALUES ('1','admin','admin@example.com','$2y$10$wVgRhJS034rlplgiwtAAVuEKe4KmlDol5YZ49OGmrzFr9ZBfREWFe','Admin','User','admin','2025-10-25 17:35:38.446456','2025-10-25 17:35:38.446456');
INSERT INTO "users" ("id","username","email","password","first_name","last_name","role","created_at","updated_at") VALUES ('2','cashier','cashier@example.com','$2y$10$cSe9h13TZ62TDXDsAtAEvOsNIgZqDtoQXiZNdOAc9Idi.dhRw183S','Kasir','1','cashier','2025-10-25 17:35:38.533452','2025-10-25 17:35:38.533452');
INSERT INTO "users" ("id","username","email","password","first_name","last_name","role","created_at","updated_at") VALUES ('3','customer1','customer1@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','John','Doe','customer','2025-10-25 17:58:37.70624','2025-10-25 17:58:37.70624');
INSERT INTO "users" ("id","username","email","password","first_name","last_name","role","created_at","updated_at") VALUES ('4','customer2','customer2@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Jane','Smith','customer','2025-10-25 17:58:37.70624','2025-10-25 17:58:37.70624');
INSERT INTO "users" ("id","username","email","password","first_name","last_name","role","created_at","updated_at") VALUES ('5','customer3','customer3@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Bob','Johnson','customer','2025-10-25 17:58:37.70624','2025-10-25 17:58:37.70624');

CREATE TABLE products (
    id integer,
    name character varying(255),
    slug character varying(255),
    description text,
    short_description text,
    price numeric(10,2),
    sale_price numeric(10,2),
    stock_quantity integer,
    sku character varying(100),
    barcode character varying(100),
    weight numeric(8,2),
    length numeric(8,2),
    width numeric(8,2),
    height numeric(8,2),
    featured boolean,
    status character varying(20),
    vendor_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);

INSERT INTO "products" ("id","name","slug","description","short_description","price","sale_price","stock_quantity","sku","barcode","weight","length","width","height","featured","status","vendor_id","created_at","updated_at") VALUES ('1','Laptop Gaming ASUS ROG','laptop-gaming-asus-rog','High performance gaming laptop with RTX 3060','ASUS ROG gaming laptop','15000000.00','14500000.00','5','LAP-001','1234567890123',NULL,NULL,NULL,NULL,'f','publish',NULL,'2025-10-25 17:58:37.609923','2025-10-25 17:58:37.609923');
INSERT INTO "products" ("id","name","slug","description","short_description","price","sale_price","stock_quantity","sku","barcode","weight","length","width","height","featured","status","vendor_id","created_at","updated_at") VALUES ('2','iPhone 15 Pro Max','iphone-15-pro-max','Latest iPhone with A17 Pro chip','iPhone 15 Pro Max 256GB','18000000.00',NULL,'10','IPH-001','1234567890124',NULL,NULL,NULL,NULL,'f','publish',NULL,'2025-10-25 17:58:37.609923','2025-10-25 17:58:37.609923');
INSERT INTO "products" ("id","name","slug","description","short_description","price","sale_price","stock_quantity","sku","barcode","weight","length","width","height","featured","status","vendor_id","created_at","updated_at") VALUES ('3','Samsung Smart TV 55"','samsung-smart-tv-55','4K Ultra HD Smart TV','Samsung 55 inch 4K TV','8000000.00','7500000.00','8','TV-001','1234567890125',NULL,NULL,NULL,NULL,'f','publish',NULL,'2025-10-25 17:58:37.609923','2025-10-25 17:58:37.609923');
INSERT INTO "products" ("id","name","slug","description","short_description","price","sale_price","stock_quantity","sku","barcode","weight","length","width","height","featured","status","vendor_id","created_at","updated_at") VALUES ('4','Nike Air Max 270','nike-air-max-270','Comfortable running shoes','Nike Air Max running shoes','2500000.00',NULL,'15','SHOE-001','1234567890126',NULL,NULL,NULL,NULL,'f','publish',NULL,'2025-10-25 17:58:37.609923','2025-10-25 17:58:37.609923');
INSERT INTO "products" ("id","name","slug","description","short_description","price","sale_price","stock_quantity","sku","barcode","weight","length","width","height","featured","status","vendor_id","created_at","updated_at") VALUES ('5','Adidas Original Hoodie','adidas-original-hoodie','Comfortable cotton hoodie','Adidas hoodie black','850000.00','750000.00','20','CLOTH-001','1234567890127',NULL,NULL,NULL,NULL,'f','publish',NULL,'2025-10-25 17:58:37.609923','2025-10-25 17:58:37.609923');
INSERT INTO "products" ("id","name","slug","description","short_description","price","sale_price","stock_quantity","sku","barcode","weight","length","width","height","featured","status","vendor_id","created_at","updated_at") VALUES ('6','Sony WH-1000XM5','sony-wh-1000xm5','Premium noise cancelling headphones','Sony WH-1000XM5 headphones','5000000.00',NULL,'12','HEAD-001','1234567890128',NULL,NULL,NULL,NULL,'f','publish',NULL,'2025-10-25 17:58:37.609923','2025-10-25 17:58:37.609923');
INSERT INTO "products" ("id","name","slug","description","short_description","price","sale_price","stock_quantity","sku","barcode","weight","length","width","height","featured","status","vendor_id","created_at","updated_at") VALUES ('7','MacBook Pro M3','macbook-pro-m3','Apple MacBook Pro with M3 chip','MacBook Pro 14" M3','25000000.00',NULL,'3','LAP-002','1234567890129',NULL,NULL,NULL,NULL,'f','publish',NULL,'2025-10-25 17:58:37.609923','2025-10-25 17:58:37.609923');
INSERT INTO "products" ("id","name","slug","description","short_description","price","sale_price","stock_quantity","sku","barcode","weight","length","width","height","featured","status","vendor_id","created_at","updated_at") VALUES ('8','Gaming Mouse Logitech','gaming-mouse-logitech','RGB gaming mouse','Logitech G502 Hero','750000.00','650000.00','25','MOUSE-001','1234567890130',NULL,NULL,NULL,NULL,'f','publish',NULL,'2025-10-25 17:58:37.609923','2025-10-25 17:58:37.609923');
INSERT INTO "products" ("id","name","slug","description","short_description","price","sale_price","stock_quantity","sku","barcode","weight","length","width","height","featured","status","vendor_id","created_at","updated_at") VALUES ('9','Mechanical Keyboard','mechanical-keyboard','RGB mechanical keyboard','Keychron K2 keyboard','1200000.00',NULL,'18','KEY-001','1234567890131',NULL,NULL,NULL,NULL,'f','publish',NULL,'2025-10-25 17:58:37.609923','2025-10-25 17:58:37.609923');
INSERT INTO "products" ("id","name","slug","description","short_description","price","sale_price","stock_quantity","sku","barcode","weight","length","width","height","featured","status","vendor_id","created_at","updated_at") VALUES ('10','Smart Watch Samsung','smart-watch-samsung','Galaxy Watch 6 Classic','Samsung Galaxy Watch','4500000.00','4200000.00','7','WATCH-001','1234567890132',NULL,NULL,NULL,NULL,'f','publish',NULL,'2025-10-25 17:58:37.609923','2025-10-25 17:58:37.609923');

CREATE TABLE product_images (
    id integer,
    product_id integer,
    image_url character varying(500),
    alt_text character varying(255),
    is_featured boolean,
    sort_order integer,
    created_at timestamp without time zone
);

CREATE TABLE product_category_relationships (
    product_id integer,
    category_id integer
);

CREATE TABLE orders (
    id integer,
    order_number character varying(100),
    customer_id integer,
    status character varying(20),
    total_amount numeric(10,2),
    payment_method character varying(100),
    payment_status character varying(20),
    billing_address text,
    shipping_address text,
    customer_note text,
    created_by integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);

INSERT INTO "orders" ("id","order_number","customer_id","status","total_amount","payment_method","payment_status","billing_address","shipping_address","customer_note","created_by","created_at","updated_at") VALUES ('1','ORD-20251025-0001','1','completed','15750000.00','Cash','paid',NULL,NULL,NULL,'1','2025-10-20 17:58:37.787561','2025-10-25 17:58:37.787561');
INSERT INTO "orders" ("id","order_number","customer_id","status","total_amount","payment_method","payment_status","billing_address","shipping_address","customer_note","created_by","created_at","updated_at") VALUES ('2','ORD-20251025-0002','3','completed','8650000.00','Card','paid',NULL,NULL,NULL,'2','2025-10-21 17:58:37.787561','2025-10-25 17:58:37.787561');
INSERT INTO "orders" ("id","order_number","customer_id","status","total_amount","payment_method","payment_status","billing_address","shipping_address","customer_note","created_by","created_at","updated_at") VALUES ('3','ORD-20251025-0003','4','processing','5000000.00','Cash','paid',NULL,NULL,NULL,'1','2025-10-22 17:58:37.787561','2025-10-25 17:58:37.787561');
INSERT INTO "orders" ("id","order_number","customer_id","status","total_amount","payment_method","payment_status","billing_address","shipping_address","customer_note","created_by","created_at","updated_at") VALUES ('4','ORD-20251025-0004','5','completed','3350000.00','E-Wallet','paid',NULL,NULL,NULL,'2','2025-10-23 17:58:37.787561','2025-10-25 17:58:37.787561');
INSERT INTO "orders" ("id","order_number","customer_id","status","total_amount","payment_method","payment_status","billing_address","shipping_address","customer_note","created_by","created_at","updated_at") VALUES ('5','ORD-20251025-0005','1','completed','26200000.00','Card','paid',NULL,NULL,NULL,'1','2025-10-24 17:58:37.787561','2025-10-25 17:58:37.787561');
INSERT INTO "orders" ("id","order_number","customer_id","status","total_amount","payment_method","payment_status","billing_address","shipping_address","customer_note","created_by","created_at","updated_at") VALUES ('6','ORD-20251025-0006','3','pending','2500000.00','Cash','pending',NULL,NULL,NULL,'1','2025-10-25 17:58:37.787561','2025-10-25 17:58:37.787561');

CREATE TABLE cart (
    id integer,
    user_id integer,
    product_id integer,
    quantity integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);

CREATE TABLE coupons (
    id integer,
    code character varying(100),
    discount_type character varying(20),
    discount_value numeric(10,2),
    minimum_amount numeric(10,2),
    usage_limit integer,
    used_count integer,
    expiry_date date,
    status character varying(20),
    created_at timestamp without time zone
);

CREATE TABLE wishlist (
    id integer,
    user_id integer,
    product_id integer,
    created_at timestamp without time zone
);

CREATE TABLE reviews (
    id integer,
    product_id integer,
    user_id integer,
    rating integer,
    comment text,
    status character varying(20),
    created_at timestamp without time zone
);

CREATE TABLE transactions (
    id integer,
    order_id integer,
    transaction_type character varying(50),
    amount numeric(10,2),
    payment_method character varying(100),
    cashier_id integer,
    shift_id integer,
    notes text,
    created_at timestamp without time zone
);

INSERT INTO "transactions" ("id","order_id","transaction_type","amount","payment_method","cashier_id","shift_id","notes","created_at") VALUES ('1','1','sale','15750000.00','Cash','1',NULL,NULL,'2025-10-25 17:58:37.966206');
INSERT INTO "transactions" ("id","order_id","transaction_type","amount","payment_method","cashier_id","shift_id","notes","created_at") VALUES ('2','2','sale','8650000.00','Card','2',NULL,NULL,'2025-10-25 17:58:37.966206');
INSERT INTO "transactions" ("id","order_id","transaction_type","amount","payment_method","cashier_id","shift_id","notes","created_at") VALUES ('3','3','sale','5000000.00','Cash','1',NULL,NULL,'2025-10-25 17:58:37.966206');
INSERT INTO "transactions" ("id","order_id","transaction_type","amount","payment_method","cashier_id","shift_id","notes","created_at") VALUES ('4','4','sale','3350000.00','E-Wallet','2',NULL,NULL,'2025-10-25 17:58:37.966206');
INSERT INTO "transactions" ("id","order_id","transaction_type","amount","payment_method","cashier_id","shift_id","notes","created_at") VALUES ('5','5','sale','26200000.00','Card','1',NULL,NULL,'2025-10-25 17:58:37.966206');

CREATE TABLE cashier_shifts (
    id integer,
    cashier_id integer,
    start_time timestamp without time zone,
    end_time timestamp without time zone,
    starting_cash numeric(10,2),
    ending_cash numeric(10,2),
    total_sales numeric(10,2),
    status character varying(20),
    created_at timestamp without time zone
);

CREATE TABLE inventory_logs (
    id integer,
    product_id integer,
    user_id integer,
    action_type character varying(50),
    quantity_change integer,
    stock_before integer,
    stock_after integer,
    notes text,
    created_at timestamp without time zone
);

