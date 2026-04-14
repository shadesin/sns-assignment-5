<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = "localhost";
$user = "root";
$pass = "";
$db = "lab5";

$conn = @new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    exit("Service temporarily unavailable.");
}

function initializeSecureUsers(mysqli $conn): void
{
    $createTableSql = "CREATE TABLE IF NOT EXISTS users_secure (
        username VARCHAR(50) PRIMARY KEY,
        password_hash VARCHAR(255) NOT NULL
    )";

    if (!$conn->query($createTableSql)) {
        http_response_code(500);
        exit("Service temporarily unavailable.");
    }

    $countResult = $conn->query("SELECT COUNT(*) AS total FROM users_secure");
    if (!$countResult) {
        http_response_code(500);
        exit("Service temporarily unavailable.");
    }

    $countRow = $countResult->fetch_assoc();
    $total = (int)($countRow["total"] ?? 0);
    if ($total > 0) {
        return;
    }

    $usersResult = $conn->query("SELECT username, password FROM users");
    if (!$usersResult) {
        http_response_code(500);
        exit("Service temporarily unavailable.");
    }

    $insertStmt = $conn->prepare("INSERT INTO users_secure (username, password_hash) VALUES (?, ?)");
    if (!$insertStmt) {
        http_response_code(500);
        exit("Service temporarily unavailable.");
    }

    while ($row = $usersResult->fetch_assoc()) {
        $username = $row["username"];
        $passwordHash = password_hash($row["password"], PASSWORD_DEFAULT);
        $insertStmt->bind_param("ss", $username, $passwordHash);
        $insertStmt->execute();
    }

    $insertStmt->close();
}
