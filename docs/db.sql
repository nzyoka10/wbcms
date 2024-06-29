
-- Database name
CREATE DATABASE wbcms_db;

-- use created db
USE wbcms_db;

-- Users table
CREATE TABLE Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Water Meters table
CREATE TABLE Meters (
    meter_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    meter_number VARCHAR(50) UNIQUE NOT NULL,
    installation_date DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Meater readings
CREATE TABLE Meter_Readings (
    reading_id INT PRIMARY KEY AUTO_INCREMENT,
    meter_id INT,
    reading_date DATE,
    reading_value DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (meter_id) REFERENCES Meters(meter_id)
);

-- Invoices table
CREATE TABLE Invoices (
    invoice_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    meter_id INT,
    billing_date DATE,
    due_date DATE,
    amount DECIMAL(10, 2),
    status ENUM('paid', 'unpaid') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (meter_id) REFERENCES Meters(meter_id)
);

-- Payments table
CREATE TABLE Payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT,
    payment_date DATE,
    amount DECIMAL(10, 2),
    payment_method VARCHAR(50),
    transaction_id VARCHAR(100) UNIQUE,
    status ENUM('confirmed', 'pending') DEFAULT 'pending',
    FOREIGN KEY (invoice_id) REFERENCES Invoices(invoice_id)
);

-- Notifications table
CREATE TABLE Notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    type ENUM('SMS', 'Email'),
    message TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('sent', 'failed') DEFAULT 'sent',
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Reports table
CREATE TABLE Reports (
    report_id INT PRIMARY KEY AUTO_INCREMENT,
    report_type VARCHAR(50),
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    report_data TEXT
);

-- Tarrifs table
CREATE TABLE TariffRates (
    tariff_id INT PRIMARY KEY AUTO_INCREMENT,
    tariff_name VARCHAR(100) UNIQUE NOT NULL,
    rate_per_unit DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



GO;