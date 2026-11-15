<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../lib/db.php';

$name = getenv('ADMIN_NAME');
$email = getenv('ADMIN_EMAIL');
$password = getenv('ADMIN_PASSWORD');
$phone = getenv('ADMIN_PHONE');

if ($name === false || trim($name) === '' || strlen($name) > 200
    || $email === false || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 200
    || $password === false || strlen($password) < 12
) {
    fwrite(STDERR, "Set ADMIN_NAME, ADMIN_EMAIL, and ADMIN_PASSWORD (at least 12 characters). ADMIN_PHONE is optional.\n");
    exit(1);
}

$phone = $phone === false ? '' : $phone;
$email = trim($email);

$existing = $mysqli->prepare(
    "SELECT s_email AS email FROM iL_Students WHERE s_email = ?
     UNION SELECT l_email AS email FROM iL_Librarians WHERE l_email = ?
     UNION SELECT email FROM iL_sudo WHERE email = ? LIMIT 1"
);
$existing->bind_param('sss', $email, $email, $email);
$existing->execute();
$emailExists = $existing->get_result()->num_rows > 0;
$existing->close();

$request = $mysqli->prepare("SELECT ar_id FROM iL_AccountRequests WHERE ar_email = ? AND ar_status = 'Pending' LIMIT 1");
$request->bind_param('s', $email);
$request->execute();
$pendingRequestExists = $request->get_result()->num_rows > 0;
$request->close();

if ($emailExists || $pendingRequestExists) {
    fwrite(STDERR, "That email already belongs to an account or a pending registration.\n");
    exit(1);
}

do {
    $number = 'iLib-' . random_int(10000, 99999);
    $numberCheck = $mysqli->prepare("SELECT id FROM iL_sudo WHERE number = ? LIMIT 1");
    $numberCheck->bind_param('s', $number);
    $numberCheck->execute();
    $numberExists = $numberCheck->get_result()->num_rows > 0;
    $numberCheck->close();
} while ($numberExists);

$hash = password_hash($password, PASSWORD_DEFAULT);
if ($hash === false) {
    throw new RuntimeException('Unable to secure the administrator password.');
}

$statement = $mysqli->prepare(
    "INSERT INTO iL_sudo (username, number, email, password, profile_pic, bio, phone)
     VALUES (?, ?, ?, ?, '', '', ?)"
);
$statement->bind_param('sssss', $name, $number, $email, $hash, $phone);
$statement->execute();
$statement->close();

fwrite(STDOUT, "Administrator account created: $email\n");
