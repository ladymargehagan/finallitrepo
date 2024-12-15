<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['question']) || !isset($data['wordBank']) || empty($data['wordBank'])) {
        throw new Exception('Missing required data');
    }

    $pdo->beginTransaction();

    // Find the correct answer from wordBank
    $correctAnswer = '';
    foreach ($data['wordBank'] as $word) {
        if ($word['isAnswer']) {
            $correctAnswer = $word['text'];
            break;
        }
    }

    // 1. Insert into words table with translation
    $stmt = $pdo->prepare("
        INSERT INTO words (
            word,
            original_text,
            translation,
            languageId,
            categoryId,
            difficulty
        ) VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data['question'],
        $data['question'],
        $correctAnswer,  // This is the translation
        $data['languageId'],
        $data['categoryId'],
        $data['difficulty']
    ]);

    $wordId = $pdo->lastInsertId();

    // 2. Insert translation record
    $stmt = $pdo->prepare("
        INSERT INTO translations (
            wordId,
            translated_text,
            is_primary
        ) VALUES (?, ?, 1)
    ");

    $stmt->execute([
        $wordId,
        $correctAnswer
    ]);

    $translationId = $pdo->lastInsertId();

    // 3. Create exercise template
    $stmt = $pdo->prepare("
        INSERT INTO exercise_templates (
            languageId,
            categoryId,
            question_text,
            difficulty,
            type
        ) VALUES (?, ?, ?, ?, 'translation')
    ");

    $stmt->execute([
        $data['languageId'],
        $data['categoryId'],
        $data['question'],
        $data['difficulty']
    ]);

    $templateId = $pdo->lastInsertId();

    // 4. Create exercise set
    $stmt = $pdo->prepare("
        INSERT INTO exercise_sets (
            wordId,
            translationId,
            type,
            difficulty
        ) VALUES (?, ?, 'translation', ?)
    ");

    $stmt->execute([
        $wordId,
        $translationId,
        $data['difficulty']
    ]);

    $exerciseId = $pdo->lastInsertId();

    // 5. Insert word bank options
    foreach ($data['wordBank'] as $index => $word) {
        // Insert into word_bank
        $stmt = $pdo->prepare("
            INSERT INTO word_bank (
                segment_text,
                languageId
            ) VALUES (?, ?)
        ");
        
        $stmt->execute([
            $word['text'],
            $data['languageId']
        ]);
        
        $bankWordId = $pdo->lastInsertId();

        // Link to exercise template
        $stmt = $pdo->prepare("
            INSERT INTO exercise_word_bank (
                exerciseId,
                bankWordId,
                is_answer,
                position
            ) VALUES (?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $templateId,  // Using templateId instead of exerciseId
            $bankWordId,
            $word['isAnswer'] ? 1 : 0,
            $index
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'exerciseId' => $exerciseId]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}