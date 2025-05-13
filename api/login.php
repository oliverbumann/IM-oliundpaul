<?php

require_once('../system/config.php');
header('Content-Type: text/plain; charset=UTF-8');

// ► Daten aus $_POST abgreifen (kommen dort an, weil wir FormData benutzen)
$loginInfo = $_POST['loginInfo'] ?? '';
$password    = $_POST['password']    ?? '';




// ► Benutzer anhand der E-Mail suchen
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :loginInfo OR username = :loginInfo");
$stmt->execute([':loginInfo' => $loginInfo]);
$user = $stmt->fetch();

if (!$user) {
    echo "Dieser Benutzer ist nicht registriert.";
    exit;
}

// ► Passwort vergleichen (user['password'] ist der gespeicherte Hash)
if (password_verify($password, $user['password_hash'])) {
   
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];

    echo "Login erfolgreich!";
} else {
    echo "Falsches Passwort.";
    exit;
}


