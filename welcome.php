<?php
global $pdo;
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->query("SELECT * FROM quizzes");
$quizzes = $stmt->fetchAll();
?>

<h1>Welcome to the Quiz Website</h1>
<ul>
    <?php foreach ($quizzes as $quiz): ?>
        <li><a href="quiz.php?quiz_id=<?= $quiz['id'] ?>"><?= htmlspecialchars($quiz['name']) ?></a></li>
    <?php endforeach; ?>
</ul>

<a href="logout.php">Logout</a>
