<?php
session_start();
require_once('../system/config.php');
header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Nicht eingeloggt']);
    exit;
}

$user_id = $_SESSION['user_id'];
$ausgewähltesDatum = $_GET['datum'] ?? '';

if (!$ausgewähltesDatum) {
    echo json_encode(['status' => 'error', 'message' => 'Kein Datum angegeben']);
    exit;
}

$tag = (new DateTime($ausgewähltesDatum))->format('Y-m-d');

$stmt = $pdo->prepare("
  SELECT s.id AS schedule_id, s.time, s.taken, m.name
  FROM schedules s
  JOIN medications m ON s.medication_id = m.id
  WHERE s.user_id = :uid AND s.date = :datum
  ORDER BY s.time ASC
");
$stmt->execute([
  ':uid' => $user_id,
  ':datum' => $tag
]);

$einträge = $stmt->fetchAll();

echo json_encode([
  'status' => 'success',
  'medikamente' => $einträge
]);
