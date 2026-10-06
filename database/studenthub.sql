CREATE DATABASE IF NOT EXISTS studenthub;

USE studenthub;

-- =========================
-- STUDENTS TABLE
-- =========================

CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    studentid VARCHAR(10) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(10) NOT NULL,
    department VARCHAR(5) NOT NULL,
    year TINYINT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    password VARCHAR(255) NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =========================
-- EVENTS TABLE
-- =========================

CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(50),
    event_date DATE NOT NULL,
    location VARCHAR(150),
    description TEXT
);


-- =========================
-- REGISTRATIONS TABLE
-- =========================

CREATE TABLE registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,
    event_id INT NOT NULL,

    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
        REFERENCES students(student_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (event_id)
        REFERENCES events(event_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- =========================
-- SAMPLE STUDENTS
-- =========================

INSERT INTO students
(fullname, studentid, email, mobile, department, year, gender, password)
VALUES
(
    'Aarav Patel',
    '25CE101',
    '25ce101@studenthub.edu.in',
    '9876543210',
    'CE',
    3,
    'male',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC1F0QY5qjLqYJ7G1Jm'
),
(
    'Riya Shah',
    '25CE102',
    '25ce102@studenthub.edu.in',
    '9876543211',
    'CE',
    3,
    'female',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC1F0QY5qjLqYJ7G1Jm'
);


-- =========================
-- SAMPLE EVENTS
-- =========================

INSERT INTO events
(title, category, event_date, location, description)
VALUES
(
    'TechFest 2026',
    'Technical',
    '2026-10-05',
    'CHARUSAT Campus',
    'Annual technical festival with coding and innovation competitions.'
),
(
    'Hackathon 2026',
    'Technical',
    '2026-10-12',
    'Innovation Lab',
    '24-hour hackathon for students to build innovative solutions.'
),
(
    'Sports Day',
    'Sports',
    '2026-10-18',
    'University Ground',
    'Annual sports event including cricket, football and athletics.'
);