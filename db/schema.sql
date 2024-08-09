CREATE DATABASE todo_list;

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255) NOT NULL UNIQUE,
password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE tasks (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
title VARCHAR(255) NOT NULL,
description TEXT,
status ENUM('pending', 'in_progress', 'completed') NOT NULL DEFAULT 'pending',
FOREIGN KEY (user_id) REFERENCES users(id)
);
