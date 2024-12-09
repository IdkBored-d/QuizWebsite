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

// Validate quiz_id from GET parameters
if (!isset($_GET['quiz_id']) || !is_numeric($_GET['quiz_id'])) {
    echo "Invalid quiz ID.";
    exit();
}

$quiz_id = (int) $_GET['quiz_id'];

try {
    // Fetch the quiz details
    $stmt = $pdo->prepare("SELECT name FROM quizzes WHERE id = :quiz_id");
    $stmt->execute(['quiz_id' => $quiz_id]);
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quiz) {
        echo "Quiz not found.";
        exit();
    }

    // Fetch questions and answers for the quiz
    $stmt = $pdo->prepare("SELECT id, id FROM questions WHERE quiz_id = :quiz_id");
    $stmt->execute(['quiz_id' => $quiz_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$questions) {
        echo "No questions available for this quiz.";
        exit();
    }

    // Fetch answers for each question
    $answers = [];
    foreach ($questions as $question) {
        $stmt = $pdo->prepare("SELECT id, text FROM answers WHERE question_id = :question_id");
        $stmt->execute(['question_id' => $question['id']]);
        $answers[$question['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($quiz['name']) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= htmlspecialchars($quiz['name']) ?></h1>
    <form action="submit_quiz.php" method="POST">
        <?php foreach ($questions as $question): ?>
            <div class="mb-4">
                <h5><?= htmlspecialchars($question['question']) ?></h5>
                <?php if (!empty($answers[$question['id']])): ?>
                    <?php foreach ($answers[$question['id']] as $answer): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_<?= $question['id'] ?>"
                                   id="answer_<?= $answer['id'] ?>" value="<?= $answer['id'] ?>">
                            <label class="form-check-label" for="answer_<?= $answer['id'] ?>">
                                <?= htmlspecialchars($answer['answer_text']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No answers available for this question.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
        <button type="submit" class="btn btn-primary">Submit Quiz</button>
    </form>
    <div class="mt-4">
        <a href="welcome.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
</body>
</html>