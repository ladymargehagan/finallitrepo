<?php
class ProficiencyTracker {
    private $pdo;
    private $userId;

    public function __construct($pdo, $userId) {
        $this->pdo = $pdo;
        $this->userId = $userId;
    }

    public function recordAttempt($wordId, $isCorrect) {
        // Record attempt
        $stmt = $this->pdo->prepare("
            INSERT INTO word_attempts (userId, wordId, isCorrect) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$this->userId, $wordId, $isCorrect]);

        // Update learned_words
        $this->updateLearnedWord($wordId, $isCorrect);
        
        // Update proficiency level
        $this->updateProficiencyLevel($wordId);
    }

    private function updateLearnedWord($wordId, $isCorrect) {
        $stmt = $this->pdo->prepare("
            INSERT INTO learned_words (userId, wordId, correct_attempts, total_attempts, lastReviewed)
            VALUES (?, ?, ?, 1, NOW())
            ON DUPLICATE KEY UPDATE
            correct_attempts = correct_attempts + ?,
            total_attempts = total_attempts + 1,
            lastReviewed = NOW()
        ");
        $stmt->execute([$this->userId, $wordId, $isCorrect ? 1 : 0, $isCorrect ? 1 : 0]);
    }

    private function updateProficiencyLevel($wordId) {
        // Get attempt history
        $stmt = $this->pdo->prepare("
            SELECT 
                lw.correct_attempts,
                lw.total_attempts,
                lw.lastReviewed,
                (
                    SELECT COUNT(*) 
                    FROM word_attempts 
                    WHERE userId = ? AND wordId = ? 
                    AND isCorrect = 1 
                    AND attemptDate >= DATE_SUB(NOW(), INTERVAL 5 DAY)
                ) as recent_correct
            FROM learned_words lw
            WHERE userId = ? AND wordId = ?
        ");
        $stmt->execute([$this->userId, $wordId, $this->userId, $wordId]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Calculate new proficiency level
        $newLevel = $this->calculateProficiencyLevel($stats);

        // Update proficiency
        $stmt = $this->pdo->prepare("
            UPDATE learned_words 
            SET proficiency = ? 
            WHERE userId = ? AND wordId = ?
        ");
        $stmt->execute([$newLevel, $this->userId, $wordId]);
    }

    private function calculateProficiencyLevel($stats) {
        $correctRate = $stats['total_attempts'] > 0 ? 
            $stats['correct_attempts'] / $stats['total_attempts'] : 0;
        
        if ($stats['correct_attempts'] >= 5 && $correctRate >= 0.9 && $stats['recent_correct'] >= 3) {
            return 'mastered';
        } elseif ($stats['correct_attempts'] >= 3 && $correctRate >= 0.7) {
            return 'familiar';
        }
        return 'learning';
    }

    public function getProgress() {
        // Get total words in current exercise set
        $stmt = $this->pdo->prepare("
            SELECT COUNT(DISTINCT w.wordId) as total_words
            FROM words w
            JOIN exercise_sets es ON w.wordId = es.wordId
            JOIN user_enrollments ue ON w.languageId = ue.languageId
            WHERE ue.userId = ?
        ");
        $stmt->execute([$this->userId]);
        $totalWords = $stmt->fetch(PDO::FETCH_ASSOC)['total_words'];

        // Get proficiency counts with weighted progress
        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(CASE WHEN proficiency = 'learning' THEN 1 END) as learning_count,
                COUNT(CASE WHEN proficiency = 'familiar' THEN 1 END) as familiar_count,
                COUNT(CASE WHEN proficiency = 'mastered' THEN 1 END) as mastered_count
            FROM learned_words
            WHERE userId = ?
        ");
        $stmt->execute([$this->userId]);
        $counts = $stmt->fetch(PDO::FETCH_ASSOC);

        // Calculate weighted progress
        $learningWeight = 0.3;
        $familiarWeight = 0.7;
        $masteredWeight = 1.0;

        $weightedProgress = 
            ($counts['learning_count'] * $learningWeight + 
             $counts['familiar_count'] * $familiarWeight + 
             $counts['mastered_count'] * $masteredWeight) / ($totalWords ?: 1) * 100;

        return [
            'progress' => min(100, round($weightedProgress, 2)),
            'counts' => $counts
        ];
    }
}
