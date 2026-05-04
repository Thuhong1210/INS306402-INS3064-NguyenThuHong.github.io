-- ============================================================
-- database.sql
-- Author : Hoàng Cẩm Anh
-- Purpose: Create the database and items table for the MVC demo
-- ============================================================

-- Create the database if it does not already exist
CREATE DATABASE IF NOT EXISTS mvc_item_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mvc_item_db;

-- Create the items table
-- Each item has a name and a short description
CREATE TABLE IF NOT EXISTS items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    description TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample seed data so the list is not empty on first run
INSERT INTO items (name, description) VALUES
('Wireless Keyboard',  'Compact Bluetooth keyboard with a long battery life.'),
('USB-C Hub',          'Seven-in-one hub with HDMI, USB 3.0, and SD card slots.'),
('Mechanical Pencil',  'Automatic 0.5 mm pencil, ideal for drafting and notes.');
