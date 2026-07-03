-- Create Database
CREATE DATABASE IF NOT EXISTS edueval_db;
USE edueval_db;

-- Departments Table
CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Faculty Table
CREATE TABLE faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    department_id INT,
    designation VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Students Table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    roll_no VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    department_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Evaluations Table
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    faculty_id INT NOT NULL,
    quality_material INT CHECK (quality_material BETWEEN 1 AND 5),
    punctuality INT CHECK (punctuality BETWEEN 1 AND 5),
    engagement INT CHECK (engagement BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluation (student_id, faculty_id)
);

-- Admin Table (single admin for simplicity)
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Insert Sample Data
INSERT INTO departments (name) VALUES 
('Computer Science'),
('Software Engineering'),
('Electrical Engineering'),
('Mathematics');

INSERT INTO faculty (name, email, department_id, designation) VALUES
('Dr. Ahmed Raza', 'ahmed.raza@university.edu', 1, 'Professor'),
('Sir Engr Muhammad Humayun', 'humayun@university.edu', 2, 'Senior Lecturer'),
('Dr. Sarah Khan', 'sarah.khan@university.edu', 1, 'Associate Professor'),
('Prof. Usman Ali', 'usman.ali@university.edu', 3, 'Professor'),
('Ms. Ayesha Siddiqui', 'ayesha@university.edu', 2, 'Lecturer');

-- Insert Admin (password: admin123 - in production use password_hash)
INSERT INTO admin (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert Sample Student (password: student123)
INSERT INTO students (name, email, roll_no, password, department_id) VALUES
('Test Student', 'student@test.com', 'CS-001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);