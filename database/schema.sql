-- Drop the database if it exists, then create a new one
DROP DATABASE IF EXISTS hospital_management;
CREATE DATABASE hospital_management;
USE hospital_management;

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'doctor', 'nurse') NOT NULL
);

-- Create the patients table
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female'),
    `address` TEXT,
    phone VARCHAR(20) NOT NULL,
    notes TEXT,
    doctor_id INT, 
    FOREIGN KEY (doctor_id) REFERENCES users(id) 
);

-- Create the appointments table
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    doctor_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('in_progress', 'completed'),
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);
