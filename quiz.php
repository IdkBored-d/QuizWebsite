<?php
// Start session and include config
global $pdo;
require 'config.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

// Check if the quiz_id is provided in the query string
if (!isset($_GET['quiz_id']) || !is_numeric($_GET['quiz_id'])) {
    echo "Invalid quiz selected.";
    exit();
}

// Retrieve the quiz_id
$quiz_id = intval($_GET['quiz_id']);

try {
    // Fetch the quiz details and questions
    $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = :quiz_id");
    $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
    $stmt->execute();

    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quiz) {
        echo "Quiz not found.";
        exit();
    }

    // Fetch questions for the selected quiz
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = :quiz_id");
    $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
    $stmt->execute();

    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($questions)) {
        echo "No questions available for this quiz.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error fetching quiz: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($quiz['name']) ?> - Quiz</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= htmlspecialchars($quiz['name']) ?></h1>
    <p><?= htmlspecialchars($quiz['description']) ?></p>

    <form action="submit_quiz.php" method="post">
        <?php foreach ($questions as $index => $question): ?>
            <div class="mb-3">
                <h4>Question <?= $index + 1 ?>: <?= htmlspecialchars($question['question_text']) ?></h4>
                <?php
                // Assuming options are stored in JSON format
                $options = json_decode($question['options'], true);
                foreach ($options as $option_key => $option_value):
                    ?>
                    <div class="form-check">
                        <input type="radio" name="answers[<?= $question['id'] ?>]" value="<?= htmlspecialchars($option_key) ?>" class="form-check-input" id="q<?= $question['id'] ?>_<?= $option_key ?>">
                        <label class="form-check-label" for="q<?= $question['id'] ?>_<?= $option_key ?>">
                            <?= htmlspecialchars($option_value) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
        <button type="submit" class="btn btn-primary">Submit Quiz</button>
    </form>
</div>
</body>
</html>