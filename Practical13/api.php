<?php
header("Content-Type: application/json");
require 'db.php';

function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = $conn->query("SELECT * FROM students ORDER BY id DESC");
    $students = [];

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    respond($students);
}

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    respond(["message" => "Invalid JSON data"], 400);
}

if ($method === 'POST' || $method === 'PUT') {
    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $course = trim($data['course'] ?? '');

    if ($name === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !in_array($course, ['BCA', 'BSc IT', 'BTech'])) {
        respond(["message" => "Enter valid student details"], 400);
    }

    if ($method === 'POST') {
        $stmt = $conn->prepare(
            "INSERT INTO students (name, email, course)
             VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $name, $email, $course);

        if ($stmt->execute()) {
            respond(["message" => "Student added successfully"]);
        }

        respond(["message" => "Unable to add student"], 500);
    }

    $id = (int)($data['id'] ?? 0);

    if ($id <= 0) {
        respond(["message" => "Invalid student ID"], 400);
    }

    $stmt = $conn->prepare(
        "UPDATE students SET name=?, email=?, course=? WHERE id=?"
    );
    $stmt->bind_param("sssi", $name, $email, $course, $id);

    if ($stmt->execute()) {
        respond(["message" => "Student updated successfully"]);
    }

    respond(["message" => "Unable to update student"], 500);
}

if ($method === 'DELETE') {
    $id = (int)($data['id'] ?? 0);

    if ($id <= 0) {
        respond(["message" => "Invalid student ID"], 400);
    }

    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        respond(["message" => "Student deleted successfully"]);
    }

    respond(["message" => "Student not found"], 404);
}

header("Allow: GET, POST, PUT, DELETE");
respond(["message" => "Method not allowed"], 405);
?>