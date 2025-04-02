DROP DATABASE IF EXISTS todo_app;
CREATE DATABASE IF NOT EXISTS todo_app;
USE todo_app;

DROP TABLE IF EXISTS Tasks;

CREATE TABLE Tasks (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description VARCHAR(500) NULL,
    task_status BOOLEAN DEFAULT 0
);

INSERT INTO Tasks (title, description, task_status) VALUES ('workout', 'train arms and biceps ', 0);