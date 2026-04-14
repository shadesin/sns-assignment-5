<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "connection.php";

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

// Intentionally vulnerable query required by the assignment.
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$resultRows = [];
$querySucceeded = false;
$errorMessage = "";

// Using multi_query keeps the same vulnerable SQL string while also allowing
// stacked-statement attacks for DB modification demonstration.
if ($conn->multi_query($sql)) {
    $querySucceeded = true;

    do {
        $result = $conn->store_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $resultRows[] = $row;
            }
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
} else {
    $errorMessage = "Query Error: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable Authentication Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Vulnerable Authentication Result</h1>

        <?php if ($errorMessage): ?>
            <p class="error"><strong>Error:</strong> <?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <?php if ($querySucceeded && count($resultRows) > 0): ?>
            <p class="success">Login succeeded.</p>
            <p>Rows returned by query (useful for UNION demo):</p>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultRows as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["username"]); ?></td>
                            <td><?php echo htmlspecialchars($row["password"]); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="error">Invalid username or password.</p>
        <?php endif; ?>

        <p><strong>Executed query:</strong></p>
        <pre><?php echo htmlspecialchars($sql); ?></pre>

        <a href="index.php">Back to login</a>
    </div>
</body>
</html>
