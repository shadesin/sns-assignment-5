<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eef2f7;
        }
        .container {
            width: 90%;
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border: 1px solid #d8dde5;
            border-radius: 8px;
            padding: 24px;
        }
        form {
            display: grid;
            gap: 10px;
        }
        input {
            padding: 10px;
            border: 1px solid #b8c2d1;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background: #00695c;
            color: #fff;
            border: 0;
            border-radius: 4px;
            cursor: pointer;
        }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .password-wrapper input {
            flex: 1;
            padding: 10px;
            border: 1px solid #b8c2d1;
            border-radius: 4px;
            margin: 0;
        }
        .toggle-password {
            background: #e0e0e0;
            border: 1px solid #b8c2d1;
            border-radius: 4px;
            cursor: pointer;
            color: #333;
            font-size: 16px;
            padding: 10px 8px;
            margin: 0;
        }
        .toggle-password:hover {
            background: #d0d0d0;
        }
        .password-toggle-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
            color: #1f2a37;
            font-size: 14px;
        }
        .password-toggle-row input {
            margin: 0;
            padding: 0;
            width: 16px;
            height: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Secure App Login</h1>
        <form action="authentication.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" maxlength="50" required>

            <label for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" maxlength="100" required>
            </div>
            <label class="password-toggle-row" for="showPassword">
                <input type="checkbox" id="showPassword" onchange="togglePassword()">
                Show password
            </label>

            <button type="submit">Login</button>
        </form>
        <script>
            function togglePassword() {
                const passwordField = document.getElementById('password');
                const showPassword = document.getElementById('showPassword');
                passwordField.type = showPassword.checked ? 'text' : 'password';
            }
        </script>
    </div>
</body>
</html>
