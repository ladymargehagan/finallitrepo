CREATE TABLE IF NOT EXISTS quiz_results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    language_name VARCHAR(50) NOT NULL,
    category_name VARCHAR(50) NOT NULL,
    total_questions INT NOT NULL,
    correct_answers INT NOT NULL,
    completion_time INT NOT NULL,
    score DECIMAL(5,2) NOT NULL,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

