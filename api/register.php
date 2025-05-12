<?php
// Very simple debugging output.
// In Produktion → Passwort nicht im Klartext zurücksenden,
// sondern z.B. mit password_hash() abspeichern und gar nicht echo‑n!

// immer wenn wir etwas mit der db machen, 
// brauchen wir require once
require_once('../system/config.php');

header('Content-Type: text/plain; charset=UTF-8');

// ► Daten aus $_POST abgreifen (kommen dort an, weil wir FormData benutzen)
$username = $_POST['username'] ?? '';
$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';









// ► Serverseitige Eingabevalidierung
if (strlen($username) < 3) {
    echo "Der Benutzername muss mindestens 3 Zeichen lang sein.";
    exit;
}

if (strlen($email) < 5 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Die E-Mail-Adresse ist ungültig.";
    exit;
}

if (strlen($password) < 5) {
    echo "Das Passwort muss mindestens 5 Zeichen lang sein.";
    exit;
}







// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);



// ► E-Mail überprüfen
$stmtEmail = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmtEmail->execute([':email' => $email]);
$userByEmail = $stmtEmail->fetch();

if ($userByEmail) {
    echo "Diese E-Mail-Adresse ist bereits registriert.";
    exit;
}

// ► Benutzername überprüfen
$stmtUsername = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmtUsername->execute([':username' => $username]);
$userByUsername = $stmtUsername->fetch();

if ($userByUsername) {
    echo "Dieser Benutzername ist bereits vergeben.";
    exit;
} else {






// Insert the new user
$insert = $pdo->prepare("INSERT INTO users (email, password_hash, username) VALUES (:email, :pass, :user)");
$insert->execute([
    ':email' => $email,
    ':pass'  => $hashedPassword,
    ':user' => $username
]);


// ► Ausgeben – nur zum Test!
echo "PHP meldet, Daten erfolgreich empfangen.";
echo "Username: {$username}\n";
echo "E-Mail:   {$email}\n";
echo "Passwort: {$hashedPassword}\n";



}

?>