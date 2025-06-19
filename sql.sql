-- Creating database for Hotel Management System
CREATE DATABASE hotel_management_system;
USE hotel_management_system;

-- Table for Admins
CREATE TABLE Admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Hotels
CREATE TABLE Hotels (
    hotel_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    hotel_logo VARCHAR(255) NOT  NULL , 
    city VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES Admins(admin_id)
);

-- Table for Managers
CREATE TABLE Managers (
    manager_id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES Hotels(hotel_id)
);

-- Table for Users (Customers)
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Room Types
CREATE TABLE Room_Types (
    room_type_id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT,
    type_name VARCHAR(50) NOT NULL,
    description TEXT,
    base_price DECIMAL(10, 2) NOT NULL,
    capacity INT NOT NULL,
    FOREIGN KEY (hotel_id) REFERENCES Hotels(hotel_id)
);

-- Table for Rooms
CREATE TABLE Rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT,
    room_type_id INT,
    room_number VARCHAR(10) NOT NULL,
    floor INT,
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    FOREIGN KEY (hotel_id) REFERENCES Hotels(hotel_id),
    FOREIGN KEY (room_type_id) REFERENCES Room_Types(room_type_id)
);

-- Table for Reservations
CREATE TABLE Reservations (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    hotel_id INT,
    room_id INT,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (hotel_id) REFERENCES Hotels(hotel_id),
    FOREIGN KEY (room_id) REFERENCES Rooms(room_id)
);

-- Table for Payments
CREATE TABLE Payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    user_id INT,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('credit_card', 'debit_card', 'cash', 'online') NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES Reservations(reservation_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Table for Reviews
CREATE TABLE Reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    hotel_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (hotel_id) REFERENCES Hotels(hotel_id)
);

-- Table for Staff
CREATE TABLE Staff (
    staff_id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT,
    manager_id INT,
    full_name VARCHAR(100) NOT NULL,
    role VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    hire_date DATE,
    FOREIGN KEY (hotel_id) REFERENCES Hotels(hotel_id),
    FOREIGN KEY (manager_id) REFERENCES Managers(manager_id)
);

-- Table for Staff Tasks
CREATE TABLE Staff_Tasks (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT,
    manager_id INT,
    hotel_id INT,
    task_description TEXT NOT NULL,
    assigned_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    due_date DATE,
    status ENUM('assigned', 'in_progress', 'completed', 'overdue') DEFAULT 'assigned',
    FOREIGN KEY (staff_id) REFERENCES Staff(staff_id),
    FOREIGN KEY (manager_id) REFERENCES Managers(manager_id),
    FOREIGN KEY (hotel_id) REFERENCES Hotels(hotel_id)
);

-- Table for Booking History
CREATE TABLE Booking_History (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    user_id INT,
    hotel_id INT,
    action ENUM('created', 'updated', 'cancelled', 'completed') NOT NULL,
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES Reservations(reservation_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (hotel_id) REFERENCES Hotels(hotel_id)
);