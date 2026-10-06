<?php

/* =========================================================
   PRACTICAL 8
   PHP FORM PROCESSING WITH MYSQL + PDO
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    exit("Invalid request method.");

}


/* ---------- Database connection ---------- */

require_once "db.php";


/* ---------- Get form values ---------- */

$fullname =
    trim($_POST["fullname"] ?? "");

$studentid =
    strtoupper(
        trim($_POST["studentid"] ?? "")
    );

$email =
    strtolower(
        trim($_POST["email"] ?? "")
    );

$mobile =
    trim($_POST["mobile"] ?? "");

$department =
    trim($_POST["department"] ?? "");

$year =
    trim($_POST["year"] ?? "");

$gender =
    trim($_POST["gender"] ?? "");

$password =
    $_POST["password"] ?? "";

$confirmPassword =
    $_POST["confirmPassword"] ?? "";

$terms =
    isset($_POST["terms"]);


/* ---------- Validation ---------- */

$errors = [];


/* Full name */

if ($fullname === "") {

    $errors[] =
        "Full name is required.";

} elseif (
    !preg_match(
        "/^[A-Za-z ]{2,50}$/",
        $fullname
    )
) {
    $errors[] =  "Full name contains invalid characters.";
}


/* Student ID */
if ($studentid === "") {
    $errors[] = "Student ID is required.";

} elseif (
    !preg_match(
        "/^(23|24|25|26)(CE|IT|CS|EC|ME)\d{3}$/",
        $studentid
    )
) {

    $errors[] = "Invalid Student ID format.";

}

/* Email */

if ($email === "") {
    $errors[] = "Email is required.";

} elseif (
    !filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )
) {

    $errors[] =  "Invalid email address.";

}


/* Mobile */

if (
    !preg_match(
        "/^[6-9][0-9]{9}$/",
        $mobile
    )
) {
    $errors[] = "Enter a valid 10-digit mobile number.";
}


/* Department */

$validDepartments = [
    "CE",
    "IT",
    "CS",
    "EC",
    "ME"
];

if (
    !in_array(
        $department,
        $validDepartments,
        true
    )
) {

    $errors[] = "Invalid department.";

}


/* Year */

$validYears = [
    "1",
    "2",
    "3",
    "4"
];

if (
    !in_array(
        $year,
        $validYears,
        true
    )
) {

    $errors[] =  "Invalid year.";

}

/* Gender */
$validGenders = [
    "male",
    "female",
    "other"
];

if (
    !in_array(
        $gender,
        $validGenders,
        true
    )
) {
    $errors[] = "Please select a valid gender.";

}

/* Password */

if (strlen($password) < 8) {
    $errors[] =  "Password must contain at least 8 characters.";
}

/* Confirm password */

if ($password !== $confirmPassword) {
    $errors[] = "Passwords do not match.";
}

/* Terms */

if (!$terms) {
    $errors[] =  "You must accept the terms and conditions.";
}

/* ---------- Display errors ---------- */

if (!empty($errors)) {
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>Registration Error</title>";
    echo "<link rel='stylesheet' href='../css/style.css'>";
    echo "</head>";
    echo "<body>";
    echo "<div class='card'>";
    echo "<h2>Registration Failed</h2>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>"
            . htmlspecialchars($error)
            . "</li>";

    }
    echo "</ul>";
    echo "<a href='../html/register.html'>";
    echo "Go Back to Registration";
    echo "</a>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
    exit;
}

/* =========================================================
   CHECK FOR DUPLICATE STUDENT ID / EMAIL
   USING PREPARED STATEMENT
   ========================================================= */

$checkQuery = "
    SELECT student_id
    FROM students
    WHERE studentid = :studentid
       OR email = :email
    LIMIT 1
";

$checkStmt = $pdo->prepare($checkQuery);

$checkStmt->execute([
    ":studentid" => $studentid,
    ":email" => $email
]);

$existingStudent = $checkStmt->fetch();

if ($existingStudent) {
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>Registration Failed</title>";
    echo "<link rel='stylesheet' href='../css/style.css'>";
    echo "</head>";
    echo "<body>";
    echo "<div class='card'>";
    echo "<h2>Registration Failed</h2>";
    echo "<p>";
    echo "Student ID or email is already registered.";
    echo "</p>";
    echo "<a href='../html/register.html'>";
    echo "Go Back to Registration";
    echo "</a>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
    exit;
}

/* =========================================================
   HASH PASSWORD
   ========================================================= */

$passwordHash =
    password_hash( $password, PASSWORD_DEFAULT);

/* =========================================================
   INSERT STUDENT USING PREPARED STATEMENT
   ========================================================= */

$insertQuery = "
    INSERT INTO students
    (
        fullname,
        studentid,
        email,
        mobile,
        department,
        year,
        gender,
        password
    )
    VALUES
    (
        :fullname,
        :studentid,
        :email,
        :mobile,
        :department,
        :year,
        :gender,
        :password
    )
";

$insertStmt =
    $pdo->prepare($insertQuery);


try {
    $insertStmt->execute([
        ":fullname" =>
            $fullname,
        ":studentid" =>
            $studentid,
        ":email" =>
            $email,
        ":mobile" =>
            $mobile,
        ":department" =>
            $department,
        ":year" =>
            $year,
        ":gender" =>
            $gender,
        ":password" =>
            $passwordHash
    ]);

} catch (PDOException $e) {
    exit(
        "Unable to register student."
    );
}

/* =========================================================
   SUCCESS MESSAGE
   ========================================================= */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Registration Successful</title>
    <link
        rel="stylesheet"
        href="../css/style.css"
    >
</head>
<body>
    <div class="card">
        <h2>
            Registration Successful!
        </h2>
        <p>
            Welcome to StudentHub,
            <?php
            echo htmlspecialchars($fullname);
            ?>.
        </p>
        <p>
            Your registration has been
            successfully stored.
        </p>
        <a
            href="../html/login.html"
            class="btn btn-primary"
        >
            Go to Login
        </a>
    </div>
</body>
</html>