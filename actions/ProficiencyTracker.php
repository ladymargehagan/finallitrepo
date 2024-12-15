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
            INSERT INTO word_attempts (userId, wordId, isCorrect, attemptDate) 
            VALUES (?, ?, ?, NOW())
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
                (
                    SELECT COUNT(*) 
                    FROM word_attempts 
                    WHERE userId = ? AND wordId = ? 
                    AND isCorrect = 1 
                    ORDER BY attemptDate DESC
                    LIMIT 5
                ) as recent_correct_count
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
        if (!$stats) return 'learning';

        $correctRate = $stats['total_attempts'] > 0 ? 
            $stats['correct_attempts'] / $stats['total_attempts'] : 0;
        
        // Mastered: Above 80% accuracy in recent attempts
        if ($stats['total_attempts'] >= 5 && $correctRate >= 0.8) {
            return 'mastered';
        }
        // Familiar: Above 50% accuracy in recent attempts
        elseif ($stats['total_attempts'] >= 5 && $correctRate >= 0.5) {
            return 'familiar';
        }
        // Learning: Default state or below 50% accuracy
        return 'learning';
    }

    public function markSectionViewed($categoryId) {
        // Mark all words in a section as at least 'learning' when viewed
        $stmt = $this->pdo->prepare("
            INSERT INTO learned_words (userId, wordId, proficiency, first_encounter)
            SELECT ?, w.wordId, 'learning', NOW()
            FROM words w
            WHERE w.categoryId = ?
            ON DUPLICATE KEY UPDATE
            lastReviewed = NOW()
        ");
        $stmt->execute([$this->userId, $categoryId]);
    }

    public function getProgress($languageId = null, $categoryId = null) {
        $params = [$this->userId];
        $languageCondition = '';
        $categoryCondition = '';
        
        if ($languageId) {
            $languageCondition = ' AND w.languageId = ?';
            $params[] = $languageId;
        }
        if ($categoryId) {
            $categoryCondition = ' AND w.categoryId = ?';
            $params[] = $categoryId;
        }

        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(CASE WHEN lw.proficiency = 'learning' THEN 1 END) as learning_count,
                COUNT(CASE WHEN lw.proficiency = 'familiar' THEN 1 END) as familiar_count,
                COUNT(CASE WHEN lw.proficiency = 'mastered' THEN 1 END) as mastered_count,
                COUNT(*) as total_learned,
                (
                    SELECT COUNT(DISTINCT w2.wordId) 
                    FROM words w2 
                    WHERE 1=1 
                    $languageCondition
                    $categoryCondition
                ) as total_words
            FROM learned_words lw
            JOIN words w ON lw.wordId = w.wordId
            WHERE lw.userId = ?
            $languageCondition
            $categoryCondition
        ");

        $stmt->execute($params);
        $counts = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'progress' => $this->calculateWeightedProgress($counts),
            'counts' => $counts
        ];
    }

    private function calculateWeightedProgress($counts) {
        $learningWeight = 0.3;
        $familiarWeight = 0.7;
        $masteredWeight = 1.0;

        $weightedSum = 
            ($counts['learning_count'] * $learningWeight +
             $counts['familiar_count'] * $familiarWeight +
             $counts['mastered_count'] * $masteredWeight);

        $totalPossible = $counts['total_words'] * $masteredWeight;

        return $totalPossible > 0 ? 
            min(100, round(($weightedSum / $totalPossible) * 100, 2)) : 0;
    }
}
