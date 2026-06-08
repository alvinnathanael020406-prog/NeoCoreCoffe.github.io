CREATE DATABASE IF NOT EXISTS neocore_cafe;
USE neocore_cafe;

-- Tabel Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'staff'
);

-- Tabel Menu Items
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('Coffee', 'Non-Coffee', 'Food') NOT NULL,
    stock_status INT DEFAULT 0
);

-- Tabel Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_price DECIMAL(10,2) NOT NULL,
    payment_status ENUM('Pending', 'Paid') DEFAULT 'Paid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Order Details
CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    menu_item_id INT,
    quantity INT,
    subtotal DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
);

-- Tabel Inventories
CREATE TABLE IF NOT EXISTS inventories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    unit VARCHAR(20) NOT NULL,
    low_stock_threshold INT NOT NULL
);

-- Insert Dummy Data untuk Testing
INSERT INTO users (name, email, password, role) VALUES ('Admin NeoCore', 'admin@neocore.com', '123456', 'admin');
INSERT INTO menu_items (name, price, category, stock_status) VALUES 
('Espresso', 20000, 'Coffee', 50),
('Matcha Latte', 25000, 'Non-Coffee', 30),
('Croissant', 18000, 'Food', 15);

INSERT INTO inventories (item_name, quantity, unit, low_stock_threshold) VALUES 
('Biji Kopi Arabica', 5000, 'gram', 1000),
('Susu UHT', 24, 'liter', 5),
('Matcha Powder', 1000, 'gram', 200);