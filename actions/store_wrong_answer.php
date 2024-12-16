<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['wrong_answers'])) {
    $_SESSION['wrong_answers'] = [];
}

if (isset($data['exerciseId']) && !in_array($data['exerciseId'], $_SESSION['wrong_answers'])) {
    $_SESSION['wrong_answers'][] = $data['exerciseId'];
}

echo json_encode(['success' => true]); 