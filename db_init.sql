-- db_init.sql
CREATE DATABASE IF NOT EXISTS carrental CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE carrental;

-- customers
CREATE TABLE IF NOT EXISTS customers (
  CustomerID INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(100) NOT NULL,
  Email VARCHAR(100) UNIQUE NOT NULL,
  Contact VARCHAR(20),
  LicenseNo VARCHAR(50) UNIQUE NOT NULL,
  Password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- cars
CREATE TABLE IF NOT EXISTS cars (
  CarID INT AUTO_INCREMENT PRIMARY KEY,
  Model VARCHAR(100) NOT NULL,
  PlateNo VARCHAR(50) UNIQUE NOT NULL,
  Status ENUM('Available','Booked','Maintenance') DEFAULT 'Available',
  RentPrice DECIMAL(10,2) NOT NULL,
  Image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- bookings
CREATE TABLE IF NOT EXISTS bookings (
  BookingID INT AUTO_INCREMENT PRIMARY KEY,
  CustomerID INT NOT NULL,
  CarID INT NOT NULL,
  StartDate DATE NOT NULL,
  EndDate DATE NOT NULL,
  Amount DECIMAL(10,2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (CustomerID) REFERENCES customers(CustomerID) ON DELETE CASCADE,
  FOREIGN KEY (CarID) REFERENCES cars(CarID) ON DELETE CASCADE
);

-- payments (simple)
CREATE TABLE IF NOT EXISTS payments (
  PaymentID INT AUTO_INCREMENT PRIMARY KEY,
  BookingID INT NOT NULL,
  Mode ENUM('Cash','Card','UPI','NetBanking') DEFAULT 'Card',
  Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  Status ENUM('Pending','Completed','Failed') DEFAULT 'Pending',
  FOREIGN KEY (BookingID) REFERENCES bookings(BookingID) ON DELETE CASCADE
);

-- admin table
CREATE TABLE IF NOT EXISTS admins (
  AdminID INT AUTO_INCREMENT PRIMARY KEY,
  Email VARCHAR(100) UNIQUE,
  Password VARCHAR(255)
);

-- sample cars
INSERT INTO cars (Model, PlateNo, Status, RentPrice, Image) VALUES
('BMW 5 Series','MH01AA0001','Available',120.00,'assets/images/bmw.jpg'),
('Toyota Fortuner','MH01AA0002','Available',90.00,'assets/images/fortuner.jpg'),
('Ferrari 488','MH01AA0003','Available',300.00,'assets/images/ferrari.jpg'),
('Mercedes E-Class','MH01AA0004','Available',150.00,'assets/images/mercedes.jpg'),
('Audi A6','MH01AA0005','Available',140.00,'assets/images/audi.jpg'),
('Jeep Compass','MH01AA0006','Available',100.00,'assets/images/jeep.jpg');

-- Note: create admin using the provided PHP create_admin.php (it hashes the password properly).
