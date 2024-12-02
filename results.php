<?php
global $pdo;
require 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['quiz_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: login.php');
    exit();
}

$quiz_id = $_SESSION['quiz_id'];
$user_id = $_SESSION['user_id'];
$score = 0;

$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = :quiz_id");
$stmt->execute(['quiz_id' => $quiz_id]);
$questions = $stmt->fetchAll();

foreach ($questions as $question) {
    $submitted_answer = isset($_POST["question_" . $question['id']]) ? $_POST["question_" . $question['id']] : null;

    $stmt = $pdo->prepare("SELECT is_correct FROM answers WHERE id = :answer_id");
    $stmt->execute(['answer_id' => $submitted_answer]);
    $answer = $stmt->fetch();

    if ($answer && $answer['is_correct']) {
        $score++;
    }
}

$stmt = $pdo->prepare("INSERT INTO scores (user_id, quiz_id, score) VALUES (:user_id, :quiz_id, :score)");
$stmt->execute(['user_id' => $user_id, 'quiz_id' => $quiz_id, 'score' => $score]);

echo "<h1>Your score: $score</h1>";
?>

<a href="welcome.php">Back to Dashboard</a>
