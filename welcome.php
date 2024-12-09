<?php
// Start session and include config
global $pdo;
require 'config.php';
session_start();

// Check if the user is logged in; if not, redirect to login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

try {
    // Fetch all quizzes from the database
    $stmt = $pdo->query("SELECT * FROM quizzes");
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle potential database errors
    echo "Error fetching quizzes: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Welcome to the Quiz Website</h1>
    <p>Choose a quiz from the list below:</p>
    <ul class="list-group">
        <?php foreach ($quizzes as $quiz): ?>
            <li class="list-group-item">
                <a href="quiz.php?quiz_id=<?= htmlspecialchars($quiz['id']) ?>">
                    <?= htmlspecialchars($quiz['name']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="mt-4">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>
</body>
</html>