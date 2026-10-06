<?php

session_start();


/* ---------- Only allow POST ---------- */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    exit("Invalid request method.");

}


/* ---------- Database connection ---------- */

require_once "db.php";


/* ---------- Get login values ---------- */

$studentid =
    strtoupper(
        trim($_POST["studentid"] ?? "")
    );

$password =
    $_POST["password"] ?? "";


/* ---------- Basic validation ---------- */

$errors = [];


if ($studentid === "") {

    $errors[] = "Student ID is required.";
}

if ($password === "") {

    $errors[] =
        "Password is required.";

}


/* ---------- Show validation errors ---------- */

if (!empty($errors)) {

    echo "<!DOCTYPE html>";

    echo "<html>";

    echo "<head>";

    echo "<title>Login Failed</title>";

    echo "<link rel='stylesheet' href='../css/style.css'>";

    echo "</head>";

    echo "<body>";

    echo "<div class='card'>";

    echo "<h2>Login Failed</h2>";

    echo "<ul>";

    foreach ($errors as $error) {

        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
    echo "<a href='../html/login.html'>";
    echo "Go Back to Login";
    echo "</a>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
    exit;
}

/* =========================================================
   FIND STUDENT USING PREPARED STATEMENT
   ========================================================= */
$query = "
    SELECT
        student_id,
        fullname,
        studentid,
        email,
        department,
        year,
        password
    FROM students
    WHERE studentid = :studentid
    LIMIT 1
";


$stmt =
    $pdo->prepare($query);


$stmt->execute([
    ":studentid" => $studentid
]);


$student =
    $stmt->fetch();


/* ---------- Student not found ---------- */

if (!$student) {

    echo "<!DOCTYPE html>";

    echo "<html>";

    echo "<head>";

    echo "<title>Login Failed</title>";

    echo "<link rel='stylesheet' href='../css/style.css'>";

    echo "</head>";

    echo "<body>";

    echo "<div class='card'>";

    echo "<h2>Login Failed</h2>";

    echo "<p>";
    echo "Invalid Student ID or password.";
    echo "</p>";

    echo "<a href='../html/login.html'>";
    echo "Go Back to Login";
    echo "</a>";

    echo "</div>";

    echo "</body>";

    echo "</html>";

    exit;

}


/* =========================================================
   VERIFY PASSWORD
   ========================================================= */

if (
    !password_verify(
        $password,
        $student["password"]
    )
) {

    echo "<!DOCTYPE html>";

    echo "<html>";

    echo "<head>";

    echo "<title>Login Failed</title>";

    echo "<link rel='stylesheet' href='../css/style.css'>";

    echo "</head>";

    echo "<body>";

    echo "<div class='card'>";

    echo "<h2>Login Failed</h2>";

    echo "<p>";
    echo "Invalid Student ID or password.";
    echo "</p>";

    echo "<a href='../html/login.html'>";
    echo "Go Back to Login";
    echo "</a>";

    echo "</div>";

    echo "</body>";

    echo "</html>";

    exit;

}


/* =========================================================
   LOGIN SUCCESS
   ========================================================= */

$_SESSION["logged_in"] = true;

$_SESSION["studentid"] =
    $student["studentid"];

$_SESSION["fullname"] =
    $student["fullname"];

$_SESSION["email"] =
    $student["email"];

$_SESSION["department"] =
    $student["department"];

$_SESSION["year"] =
    $student["year"];


/* ---------- Redirect ---------- */

header(
    "Location: ../html/dashboard.php"
);

exit;

?>