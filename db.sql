-- Create the wanderlust_travel database
CREATE DATABASE IF NOT EXISTS wanderlust_travel CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Use the database
USE wanderlust_travel;

-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create bookings table
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    package_name VARCHAR(255) NOT NULL,
    package_file VARCHAR(255) NOT NULL,
    departure_date DATE NOT NULL,
    room_type VARCHAR(100) NOT NULL,
    room_price DECIMAL(10,2) NOT NULL,
    adults INT NOT NULL DEFAULT 1,
    children INT NOT NULL DEFAULT 0,
    base_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    special_requests TEXT,
    booking_status ENUM('Pending Payment', 'Confirmed', 'Cancelled', 'Completed') DEFAULT 'Pending Payment',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create payments table
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    user_id INT NOT NULL,
    transaction_id VARCHAR(100) UNIQUE NOT NULL,
    payment_method ENUM('tng', 'visa', 'mastercard', 'amex') NOT NULL,
    payment_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('Pending', 'Successful', 'Failed') DEFAULT 'Pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cardholder_name VARCHAR(100),
    card_last_four VARCHAR(4),
    tng_phone VARCHAR(20),
    billing_email VARCHAR(100) NOT NULL,
    billing_first_name VARCHAR(50) NOT NULL,
    billing_last_name VARCHAR(50) NOT NULL,
    billing_phone VARCHAR(20) NOT NULL,
    billing_address VARCHAR(255) NOT NULL,
    billing_city VARCHAR(100) NOT NULL,
    billing_postal_code VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE travel_payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,                 -- who paid (link to users table later)
    package_id INT NOT NULL,              -- which travel package
    amount DECIMAL(10,2) NOT NULL,        -- price paid
    payment_status ENUM('pending','completed','failed') DEFAULT 'pending',
    payment_method VARCHAR(50),           -- e.g., Credit Card, PayPal
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES packages(package_id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS `flight_payments`;
CREATE TABLE IF NOT EXISTS `flight_payments` (
    `payment_id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `flight_name` varchar(120) NOT NULL,
    `origin` varchar(80) DEFAULT NULL,
    `destination` varchar(80) DEFAULT NULL,
    `depart_date` date DEFAULT NULL,
    `return_date` date DEFAULT NULL,
    `total_price` decimal(10,2) NOT NULL,
    `payment_method` varchar(50) DEFAULT NULL,
    `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
    `special_requests` text,
    `transaction_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`payment_id`),
    KEY `idx_user_id` (`user_id`)
);

DROP TABLE IF EXISTS `cruise_payments`;
CREATE TABLE IF NOT EXISTS `cruise_payments` (
    `payment_id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `cruise_name` varchar(120) NOT NULL,
    `departure_date` date DEFAULT NULL,
    `total_price` decimal(10,2) NOT NULL,
    `payment_method` varchar(50) DEFAULT NULL,
    `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
    `special_requests` text,
    `transaction_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`payment_id`),
    KEY `user_id` (`user_id`)
);

CREATE TABLE IF NOT EXISTS feedback (
  feedback_id  INT AUTO_INCREMENT PRIMARY KEY,
  user_id      INT NULL,
  name         VARCHAR(80)  NULL,
  email        VARCHAR(120) NULL,
  subject      VARCHAR(150) NOT NULL,
  message      TEXT NOT NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user_id (user_id),
  CONSTRAINT fk_feedback_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE SET NULL
);
