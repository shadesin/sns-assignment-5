# SECURITY.md

## 1. How SQL Injection Works

SQL Injection happens when user input is directly concatenated into SQL queries. If input is not sanitized or parameterized, an attacker can inject SQL syntax and change query logic.

In this lab's vulnerable app, the query is:

```php
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
```

Because `$username` and `$password` are inserted directly, attacker input can break out of quotes and append SQL commands.

## 2. Types of Attacks Performed

1. Authentication bypass
2. Union-based data extraction
3. Blind SQL injection using logical conditions
4. Database modification (update or insert)

## 3. How Attacks Modified the Database

The vulnerable app allows injected statements in input. Example attacks:

- Update admin password:

```sql
UPDATE users SET password='pwned123' WHERE username='admin';
```

- Insert malicious user:

```sql
INSERT INTO users VALUES('hacker','hack123');
```

These changes are verified in phpMyAdmin with BEFORE and AFTER screenshots.

## 4. How Fixes Prevent Attacks

The secure app prevents SQL injection using:

1. Prepared statements:
   SQL code and user data are separated, so injected strings are treated only as data.

2. Password hashing:
   Passwords are stored using `password_hash()` and verified with `password_verify()`. Plain-text passwords are not stored in the secure table.

3. Input validation:
   Username format, length, and required fields are validated.

4. Error handling:
   SQL errors are not displayed to users, reducing information leakage.

Together, these controls make bypass, union extraction, blind condition attacks, and database modification payloads fail in the secure app.
