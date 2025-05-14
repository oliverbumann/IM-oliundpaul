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
$start_time = $_POST['start_time'] ?? '';
$urgency = $_POST['urgency'] ?? '';

// Validierung
if (strlen($name) < 1 || strlen($start_date) < 1 || strlen($start_time) < 1) {
  echo json_encode(['status' => 'error', 'message' => 'Alle Felder müssen ausgefüllt sein.']);
  exit;
}

// Medikament speichern
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

$medId = $pdo->lastInsertId(); // ID des neuen Medikaments

// Einträge in schedules erzeugen
$start = new DateTime($start_date);
$time = $start_time;
$tageVoraus = 30;

for ($i = 0; $i < $tageVoraus; $i++) {
  $datum = (clone $start)->modify("+$i days");

  $eintragen = false;

  switch ($frequency) {
    case 'täglich':
      $eintragen = true;
      break;
    case 'wöchentlich':
      if ($i % 7 === 0) $eintragen = true;
      break;
    case 'monatlich':
      if ($datum->format('d') === $start->format('d')) $eintragen = true;
      break;
  }

  if ($eintragen) {
    $stmt = $pdo->prepare("INSERT INTO schedules (user_id, medication_id, date, time, taken)
                           VALUES (:uid, :mid, :datum, :zeit, 0)");
    $stmt->execute([
      ':uid' => $user_id,
      ':mid' => $medId,
      ':datum' => $datum->format('Y-m-d'),
      ':zeit' => $time
    ]);
  }
}

echo json_encode(['status' => 'success', 'message' => 'Medikament erfolgreich gespeichert und geplant.']);
exit;
