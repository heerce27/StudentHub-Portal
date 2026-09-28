<?php

/* =========================================================
   PRACTICAL 7
   PHP FORM PROCESSING WITH SERVER-SIDE VALIDATION
   ========================================================= */


/* ---------- Only allow POST ---------- */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    exit("Invalid request method.");

}


/* ---------- Helper function ---------- */

function cleanInput($value)
{
    return htmlspecialchars(
        trim($value),
        ENT_QUOTES,
        'UTF-8'
    );
}


/* ---------- Get form values ---------- */

$fullname =
    cleanInput($_POST["fullname"] ?? "");

$studentid =
    strtoupper(
        cleanInput($_POST["studentid"] ?? "")
    );

$email =
    strtolower(
        cleanInput($_POST["email"] ?? "")
    );

$mobile =
    cleanInput($_POST["mobile"] ?? "");

$department =
    cleanInput($_POST["department"] ?? "");

$year =
    cleanInput($_POST["year"] ?? "");

$gender =
    cleanInput($_POST["gender"] ?? "");

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

}

elseif (
    !preg_match(
        "/^[A-Za-z ]{2,50}$/",
        $fullname
    )
) {

    $errors[] =
        "Full name contains invalid characters.";

}


/* Student ID */

if ($studentid === "") {

    $errors[] =
        "Student ID is required.";

}

elseif (
    !preg_match(
        "/^(23|24|25|26)(CE|IT|CS|EC|ME)\d{3}$/",
        $studentid
    )
) {

    $errors[] =
        "Invalid Student ID format.";

}


/* Email */

if ($email === "") {

    $errors[] =
        "Email is required.";

}

elseif (
    !filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )
) {

    $errors[] =
        "Invalid email address.";

}


/* Mobile */

if (
    !preg_match(
        "/^[6-9][0-9]{9}$/",
        $mobile
    )
) {

    $errors[] =
        "Enter a valid 10-digit mobile number.";

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

    $errors[] =
        "Invalid department.";

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

    $errors[] =
        "Invalid year.";

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

    $errors[] =
        "Please select a valid gender.";

}


/* Password */

if (strlen($password) < 8) {

    $errors[] =
        "Password must contain at least 8 characters.";

}


/* Confirm password */

if ($password !== $confirmPassword) {

    $errors[] =
        "Passwords do not match.";

}


/* Terms */

if (!$terms) {

    $errors[] =
        "You must accept the terms and conditions.";

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
   STORE DATA IN JSON
   ========================================================= */


/* Password should never be stored as plain text */

$passwordHash =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );


$newRegistration = [

    "id" =>
        time(),

    "fullname" =>
        $fullname,

    "studentid" =>
        $studentid,

    "email" =>
        $email,

    "mobile" =>
        $mobile,

    "department" =>
        $department,

    "year" =>
        $year,

    "gender" =>
        $gender,

    "password" =>
        $passwordHash,

    "registered_at" =>
        date("Y-m-d H:i:s")

];


/* JSON file */

$file =
    "../data/registrations.json";


/* Create file if it does not exist */

if (!file_exists($file)) {

    file_put_contents(
        $file,
        "[]"
    );

}


/* Read existing data */

$json = file_get_contents($file);

$registrations = json_decode($json, true);
/* Check JSON */
if (!is_array($registrations)) {
    $registrations = [];

}

/* ---------- Duplicate checking ---------- */
foreach ($registrations as $registration) {

    if (
        isset($registration["studentid"]) &&
        $registration["studentid"] === $studentid
    ) {
        exit( "Student ID is already registered.");
    }

    if ( isset($registration["email"]) && $registration["email"] === $email
    ) {

        exit(
            "Email is already registered."
        );

    }

}


/* Add new record */

$registrations[] =
    $newRegistration;


/* Convert to JSON */

$newJson =
    json_encode(
        $registrations,
        JSON_PRETTY_PRINT
    );


/* Safely write file */

if (
    file_put_contents(
        $file,
        $newJson,
        LOCK_EX
    ) === false
) {

    exit(
        "Unable to save registration data."
    );

}


/* ---------- Success ---------- */

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
            <?php echo $fullname; ?>.
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