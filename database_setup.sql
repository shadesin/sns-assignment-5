CREATE DATABASE IF NOT EXISTS lab5;
USE lab5;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    username VARCHAR(50),
    password VARCHAR(50)
);

INSERT INTO users VALUES ('user1', 'pass1');
INSERT INTO users VALUES ('admin', 'admin123');
