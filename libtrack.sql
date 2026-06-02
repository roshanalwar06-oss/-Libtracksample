-- Lib Track Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS libtrack;
USE libtrack;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    role ENUM('admin','member') NOT NULL
);

-- Books table
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    author VARCHAR(100) NOT NULL,
    location VARCHAR(50) NOT NULL
);

-- Issued Books table
CREATE TABLE issued_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    member_id INT NOT NULL,
    issue_date DATE NOT NULL,
    return_date DATE DEFAULT NULL,
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (member_id) REFERENCES users(id)
);

-- Fines table
CREATE TABLE fines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    issue_id INT NOT NULL,
    amount INT NOT NULL,
    FOREIGN KEY (issue_id) REFERENCES issued_books(id)
);

-- Sample admin user
INSERT INTO users (username, password, name, role) VALUES ('admin', 'admin123', 'Admin User', 'admin');
-- Sample member users
INSERT INTO users (username, password, name, role) VALUES ('member1', 'member123', 'John Doe', 'member');
INSERT INTO users (username, password, name, role) VALUES ('member2', 'member123', 'Jane Smith', 'member');
-- Sample books
INSERT INTO books (title, author, location) VALUES ('The Great Gatsby', 'F. Scott Fitzgerald', 'A1-S1-SectionA');
INSERT INTO books (title, author, location) VALUES ('1984', 'George Orwell', 'A2-S3-SectionB');
INSERT INTO books (title, author, location) VALUES ('To Kill a Mockingbird', 'Harper Lee', 'A3-S2-SectionC');
-- Sample issued book
INSERT INTO issued_books (book_id, member_id, issue_date) VALUES (1, 2, '2026-02-01');
-- Sample fine
INSERT INTO fines (issue_id, amount) VALUES (1, 10);
