<?php
require 'config.php';
global $pdo;
session_start();

// Redirect to login if user is not logged in or invalid request method
if (!isset($_SESSION['user_id']) || !isset($_SESSION['quiz_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

try {
    // Fetch quiz and user IDs from session
    $quiz_id = $_SESSION['quiz_id'];
    $user_id = $_SESSION['user_id'];
    $score = 0;

    // Prepare to fetch all questions for the quiz
    $stmt = $pdo->prepare("SELECT id FROM questions WHERE quiz_id = :quiz_id");
    $stmt->execute(['quiz_id' => $quiz_id]);
    $questions = $stmt->fetchAll();

    foreach ($questions as $question) {
        // Retrieve the submitted answer for each question
        $question_id = $question['id'];
        $submitted_answer = isset($_POST["question_$question_id"]) ? $_POST["question_$question_id"] : null;

        if ($submitted_answer) {
            // Check if the submitted answer is correct
            $stmt = $pdo->prepare("SELECT is_correct FROM answers WHERE id = :answer_id");
            $stmt->execute(['answer_id' => $submitted_answer]);
            $answer = $stmt->fetch();

            if ($answer && $answer['is_correct']) {
                $score++;
            }
        }
    }

    // Insert the user's score into the database
    $stmt = $pdo->prepare("INSERT INTO scores (user_id, quiz_id, score) VALUES (:user_id, :quiz_id, :score)");
    $stmt->execute([
        'user_id' => $user_id,
        'quiz_id' => $quiz_id,
        'score' => $score
    ]);

    // Display the score
    echo "<h1>Your score: $score</h1>";
} catch (PDOException $e) {
    // Handle database errors
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}
?>

<a href="welcome.php">Back to Dashboard</a>
