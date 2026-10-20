# StudentHub Portal

## Project Overview

StudentHub Portal is a web-based student portal developed using HTML, CSS, JavaScript, PHP, and MySQL. It provides a centralized interface for students to access common academic services and demonstrates web development concepts such as form validation, authentication, session management, database connectivity, and JSON data fetching.

**Project status:** Student registration and login use PHP and MySQL. The Events page fetches data from a JSON file. Some student service pages are currently static/demo interfaces and may require further database integration.

---

## Features

- Student Registration
- Student Login
- Student Dashboard
- Admin Dashboard
- Student Profile
- Attendance
- Results
- Certificates
- Fee Details
- Events using Fetch API and JSON
- Feedback
- PHP session management and logout
- MySQL database integration
- Standalone PHP practical examples

*Note: Not every page is connected to live database records yet.*

---

## Technologies Used

### Frontend
- HTML5
- CSS3
- JavaScript
- Fetch API
- JSON

### Backend
- PHP
- PHP Sessions
- PDO database operations

### Database
- MySQL / MariaDB

### Tools
- XAMPP
- Visual Studio Code
- phpMyAdmin
- Git
- GitHub

---

## Project Structure

```text
StudentHub Portal/
├── html/
│   ├── index.html
│   ├── login.html
│   ├── register.html
│   ├── dashboard.php
│   ├── admin_dashboard.php
│   ├── events.html
│   ├── Profile.html
│   ├── attendance.html
│   ├── results.html
│   ├── certificates.html
│   ├── fees.html
│   └── feedback.html
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
├── images/
│
├── data/
│   ├── events.json
│   └── registrations.json
│
├── php/
│   ├── db.php
│   ├── process_registration.php
│   ├── process_login.php
│   ├── session.php
│   ├── logout.php
│   └── test_connection.php
│
├── database/
│   └── studenthub.sql
│
├── Practical11/
├── Practical12/
├── Practical13/
├── Practical14/
├── PracticalFiles/
│
├── 25DCE108_WDF_poster.pdf
├── Requirement Analysis
├── Sitemapp.txt
├── Wireframes
├── Challenges & Learnings.txt
└── README.md
```

---

## Installation

### Requirements

- XAMPP with Apache and MySQL/MariaDB
- A modern web browser
- phpMyAdmin
- Visual Studio Code (recommended)

### Setup Steps

1. Install XAMPP.
2. Copy the project folder into the XAMPP `htdocs` directory.

   ```text
   C:\xampp\htdocs\StudentHub Portal\
   ```

3. Start **Apache** and **MySQL** from the XAMPP Control Panel.
4. Open phpMyAdmin:

   ```text
   http://localhost/phpmyadmin/
   ```

5. Import the database file:

   ```text
   database/studenthub.sql
   ```

6. Check the database name, username, and password in `php/db.php` and update them if necessary.
7. Open the portal in your browser:

   ```text
   http://localhost/StudentHub%20Portal/html/index.html
   ```

**Important:** Open the project through Apache rather than directly from File Explorer so PHP and Fetch API requests can work correctly.

---

## Database

The main database file is `database/studenthub.sql`.

It contains the schema and sample data for the StudentHub database, including tables for students, events, registrations, and administrators.

- Import the SQL file through phpMyAdmin.
- Confirm that the database settings in `php/db.php` match your local configuration.
- Back up an existing database before replacing its tables.
- The standalone practical folders may use separate databases and require their own setup.
- Use `php/test_connection.php` only for local connection testing.

---

## Modules

### 1. Student Registration
Allows students to submit their information through a registration form. PHP processes the submitted data and validates it before database insertion.

### 2. Student Login
Processes login credentials through PHP and checks the account against the database.

### 3. Student Dashboard
Provides the main interface for student-related portal services.

### 4. Admin Dashboard
Provides a separate dashboard for administrative access.

### 5. Student Profile
Provides a profile interface for displaying student information.

### 6. Attendance
Provides an interface for attendance-related information.

### 7. Results
Provides an interface for academic results.

### 8. Certificates
Provides access to a certificates-related interface.

### 9. Fee Details
Provides an interface for fee-related information.

### 10. Events
Uses JavaScript Fetch API to retrieve and display events from `data/events.json`.

**Note:** Fetching events from JSON does not automatically insert or update records in the MySQL `events` table.

### 11. Feedback
Provides an interface for student feedback.

Some service modules are currently static/demo pages and need further integration if they are expected to display live student records.

---
---
---

## Future Enhancements

- Database integration for profile, attendance, results, fees, certificates, and feedback
- Admin event management
- Student notifications
- Online fee payment
- QR-based attendance
- Faculty portal
- Improved mobile responsiveness
- Dark mode
- Automated testing and stronger security checks

---

## Coursework Documents

The project includes practical reports in `PracticalFiles/`, the poster `25DCE108_WDF_poster.pdf`, and supporting documentation such as requirements analysis, sitemap, wireframes, and challenges/learnings notes.

---

## Developed By

**Heer Shah**

---

