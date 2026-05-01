<?php
// 1. Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact.php?status=error&msg=invalid_method');
    exit;
}

// 2. Sanitize input
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

// 3. Validate input
$errors = [];

if (empty($name)) $errors[] = 'Name is required.';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
if (empty($subject)) $errors[] = 'Subject is required.';
if (empty($phone)) $errors[] = 'Phone number is required.';
if (empty($message)) $errors[] = 'Message is required.';

if (!empty($errors)) {
    // Redirect back with error (optional: use session to persist errors)
    $error_query = urlencode(implode(', ', $errors));
    header("Location: contact.php?status=error&msg=$error_query");
    exit;
}

// 4. Include DB connection (mysqli)
require_once 'connection.php'; // Assumes $conn is created inside

// 5. Prepare and bind statement
$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, phone, message) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    header('Location: contact.php?status=error&msg=db_prepare_failed');
    exit;
}

$stmt->bind_param("sssss", $name, $email, $subject, $phone, $message);


if ($stmt->execute()) {
    header('Location: contact.php?status=success');
} else {
    header('Location: contact.php?status=error&msg=insert_failed');
}

$stmt->close();
$conn->close();
?>
