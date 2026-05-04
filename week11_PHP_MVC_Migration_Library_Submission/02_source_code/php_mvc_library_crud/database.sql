CREATE DATABASE IF NOT EXISTS mvc_library_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mvc_library_db;

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    author VARCHAR(120) NOT NULL,
    category VARCHAR(80) DEFAULT '',
    status ENUM('Available', 'Borrowed', 'Maintenance') NOT NULL DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO books (title, author, category, status) VALUES
('Introduction to Information Systems', 'R. Kelly Rainer', 'MIS', 'Available'),
('Database System Concepts', 'Abraham Silberschatz', 'Database', 'Borrowed'),
('Clean Code', 'Robert C. Martin', 'Programming', 'Available');
