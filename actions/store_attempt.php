<?php
session_start();
require_once '../config/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['exercise_results'])) {
    $_SESSION['exercise_results'] = [
        'total_words' => 0,
        'correct_words' => 0,
        'answers' => [],
        'start_time' => time()
    ];
}

// Store the attempt result
$_SESSION['exercise_results']['answers'][] = [
    'word' => $data['word'] ?? '',
    'user_answer' => $data['userAnswer'],
    'correct_answer' => $data['correctAnswer'],
    'correct' => $data['isCorrect']
];

// Increment total words only once per question
$_SESSION['exercise_results']['total_words']++;

// Increment correct words only if answer is correct
if ($data['isCorrect']) {
    $_SESSION['exercise_results']['correct_words']++;
}

echo json_encode(['success' => true]); 