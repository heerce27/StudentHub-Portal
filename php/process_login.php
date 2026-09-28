<?php

session_start();
/* ---------- Only allow POST ---------- */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Invalid request method.");
}

/* ---------- Get login values ---------- */
$studentid = strtoupper(trim($_POST["studentid"] ?? ""));
$password = $_POST["password"] ?? "";

/* ---------- Basic validation ---------- */
$errors = [];
if ($studentid === "") {
    $errors[] = "Student ID is required.";
}
if ($password === "") {
    $errors[] = "Password is required.";
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

/* ---------- JSON file ---------- */
$file = "../data/registrations.json";
if (!file_exists($file)) {
    exit("Registration data not found.");
}

/* ---------- Read registrations ---------- */
$json = file_get_contents($file);
$registrations = json_decode($json, true);
if (!is_array($registrations)) {
    exit("Invalid registration data.");
}

/* ---------- Find student ---------- */
$student = null;
foreach ($registrations as $registration) {
    if (
        isset($registration["studentid"]) &&
        strtoupper($registration["studentid"]) === $studentid
    ) {
        $student = $registration;
        break;
    }
}

/* ---------- Check student ---------- */
if ($student === null) {

    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>Login Failed</title>";
    echo "<link rel='stylesheet' href='../css/style.css'>";
    echo "</head>";
    echo "<body>";
    echo "<div class='card'>";
    echo "<h2>Login Failed</h2>";
    echo "<p>Invalid Student ID or password.</p>";
    echo "<a href='../html/login.html'>";
    echo "Go Back to Login";
    echo "</a>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
    exit;
}

/* ---------- Verify password ---------- */
if (
    !isset($student["password"]) ||
    !password_verify($password, $student["password"])
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
    echo "<p>Invalid Student ID or password.</p>";
    echo "<a href='../html/login.html'>";
    echo "Go Back to Login";
    echo "</a>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
    exit;
}

/* ---------- Login successful ---------- */
$_SESSION["logged_in"] = true;
$_SESSION["studentid"] = $student["studentid"];
$_SESSION["fullname"] = $student["fullname"];
$_SESSION["email"] = $student["email"];
$_SESSION["department"] = $student["department"];
$_SESSION["year"] = $student["year"];

/* ---------- Redirect ---------- */
header("Location: ../html/dashboard.php");
exit;
?>