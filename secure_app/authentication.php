<?php
require_once "connection.php";

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

if ($username === "" || $password === "") {
    exit("Invalid input.");
}

if (strlen($username) > 50 || strlen($password) > 100) {
    exit("Invalid input.");
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    exit("Invalid input.");
}

initializeSecureUsers($conn);

$stmt = $conn->prepare("SELECT password_hash FROM users_secure WHERE username = ?");
if (!$stmt) {
    http_response_code(500);
    exit("Authentication unavailable.");
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result ? $result->fetch_assoc() : null;

$isAuthenticated = false;
if ($row && isset($row["password_hash"])) {
    $isAuthenticated = password_verify($password, $row["password_hash"]);
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Authentication Result</title>
</head>
<body>
    <h1>Secure Authentication Result</h1>
    <?php if ($isAuthenticated): ?>
        <p>Login succeeded.</p>
    <?php else: ?>
        <p>Invalid username or password.</p>
    <?php endif; ?>
    <a href="index.php">Back to login</a>
</body>
</html>
