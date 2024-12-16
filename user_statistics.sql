CREATE TABLE IF NOT EXISTS user_statistics (
    user_id INT PRIMARY KEY,
    total_quizzes INT DEFAULT 0,
    total_questions INT DEFAULT 0,
    correct_answers INT DEFAULT 0,
    total_time INT DEFAULT 0,
    average_score DECIMAL(5,2) DEFAULT 0.00,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS user_language_progress (
    progress_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    language_name VARCHAR(50) NOT NULL,
    completed_exercises INT DEFAULT 0,
    correct_answers INT DEFAULT 0,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE KEY user_language_unique (user_id, language_name)
); 