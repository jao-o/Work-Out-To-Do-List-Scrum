CREATE DATABASE IF NOT EXISTS workout_tracker;


USE workout_tracker;


CREATE TABLE IF NOT EXISTS workout_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(255) NOT NULL,
    task_description TEXT NOT NULL,
    completed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
