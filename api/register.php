<?php

require_once('../system/config.php');
header('Content-Type: application/json; charset=UTF-8');

$username = $_POST['username'] ?? '';
$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';

// ► Serverseitige Validierung
if (strlen($username) < 3) {
    echo json_encode(['status' => 'error', 'message' => 'Der Benutzername muss mindestens 3 Zeichen lang sein.']);
    exit;
}

if (strlen($email) < 5 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Die E-Mail-Adresse ist ungültig.']);
    exit;
}

if (strlen($password) < 5) {
    echo json_encode(['status' => 'error', 'message' => 'Das Passwort muss mindestens 5 Zeichen lang sein.']);
    exit;
}

// ► Passwort hashen
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ► E-Mail prüfen
$stmtEmail = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmtEmail->execute([':email' => $email]);
if ($stmtEmail->fetch()) {
    echo json_encode(['status' => 'error', 'message' => 'Diese E-Mail-Adresse ist bereits registriert.']);
    exit;
}

// ► Benutzername prüfen
$stmtUsername = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmtUsername->execute([':username' => $username]);
if ($stmtUsername->fetch()) {
    echo json_encode(['status' => 'error', 'message' => 'Dieser Benutzername ist bereits vergeben.']);
    exit;
}

// ► Benutzer einfügen
$insert = $pdo->prepare("INSERT INTO users (email, password_hash, username) VALUES (:email, :pass, :user)");
$insert->execute([
    ':email' => $email,
    ':pass'  => $hashedPassword,
    ':user' => $username
]);

// ► Erfolgsantwort
echo json_encode([
    'status' => 'success',
    'message' => 'Benutzer erfolgreich registriert.'
]);
exit;