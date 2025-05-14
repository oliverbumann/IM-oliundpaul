<?php

session_start();
require_once('../system/config.php');
header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'Nicht eingeloggt.']);
  exit;
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'] ?? '';
$frequency = $_POST['frequency'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$start_time = $_POST['start_time'] ?? ''; // NEU
$urgency = $_POST['urgency'] ?? '';

// Validierung
if (strlen($name) < 1 || strlen($start_date) < 1 || strlen($start_time) < 1) {
  echo json_encode(['status' => 'error', 'message' => 'Alle Felder müssen ausgefüllt sein.']);
  exit;
}

$stmt = $pdo->prepare("INSERT INTO medications (user_id, name, frequency, start_date, start_time, urgency)
                       VALUES (:uid, :name, :freq, :start, :time, :urg)");
$stmt->execute([
  ':uid' => $user_id,
  ':name' => $name,
  ':freq' => $frequency,
  ':start' => $start_date,
  ':time' => $start_time,
  ':urg' => $urgency
]);

echo json_encode(['status' => 'success', 'message' => 'Medikament erfolgreich gespeichert.']);
exit;
