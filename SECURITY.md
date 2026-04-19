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

## 4. How Each Attack Is Prevented in the Secure App

The secure app blocks all demonstrated attacks primarily through prepared statements, plus password hashing, validation, and safer error handling.

### 4.1 Authentication Bypass

Typical payload:

```sql
admin' --
```

How it is prevented:

- The login query is parameterized (prepared statement with bound parameters), so the input is never merged into SQL syntax.
- The payload is treated as a literal username string, not as SQL control text.
- Result: query logic is not changed, so bypass does not happen.

### 4.2 Union-Based Injection

Typical payload:

```sql
' UNION SELECT username, password FROM users --
```

How it is prevented:

- In a prepared statement, UNION text in input cannot be executed as part of the SQL command.
- The database receives one fixed query shape and one username value.
- Result: no extra rows are appended and credentials are not leaked.

### 4.3 Blind SQL Injection

Typical payload:

```sql
admin' AND SUBSTRING(password,1,1)='a' --
```

How it is prevented:

- Conditional SQL fragments from input are not parsed as query logic when parameters are used.
- The true/false probing behavior is removed because the condition is never executed by the SQL engine.
- Result: attacker cannot infer password characters from response differences.

### 4.4 Database Modification Attack (UPDATE / INSERT)

Typical payloads:

```sql
admin' OR '1'='1'; UPDATE users SET password='pwned123' WHERE username='admin'; --
```

```sql
x' OR '1'='1'; INSERT INTO users VALUES('hacker','hack123'); --
```

How it is prevented:

- In this implementation, username validation blocks this payload early: input length is capped (50 chars) and only `[a-zA-Z0-9_]` is accepted, so characters like `'`, `;`, and spaces are rejected.
- Even without that validation layer, prepared statements separate SQL structure from user input, so injected UPDATE/INSERT text is treated as data, not executable SQL.
- The authentication flow runs a fixed SELECT-only operation for login; it does not execute user-provided write queries.
- Result: attacker input cannot modify table contents through the login form.

## 5. Supporting Security Controls

1. Password hashing:
   Passwords in `users_secure` are stored as hashes via `password_hash()` and checked with `password_verify()`. Even if data is exposed, plaintext passwords are not directly revealed.

2. Input validation:
   Required fields, username format restrictions, and length caps block many malicious payloads before query execution.

3. Error handling:
   Detailed SQL errors are not shown to users, reducing feedback useful for attack refinement.
