<?php
session_start();
require_once('../system/config.php');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'Nicht eingeloggt']);
  exit;
}

$user_id = $_SESSION['user_id'];
$schedule_id = $_POST['schedule_id'] ?? '';
$taken = $_POST['taken'] ?? '';

if (!$schedule_id || $taken === '') {
  echo json_encode(['status' => 'error', 'message' => 'Fehlende Daten']);
  exit;
}

$stmt = $pdo->prepare("
  UPDATE schedules
  SET taken = :taken
  WHERE id = :id AND user_id = :uid
");
$stmt->execute([
  ':taken' => $taken === 'true' ? 1 : 0,
  ':id' => $schedule_id,
  ':uid' => $user_id
]);

echo json_encode(['status' => 'success']);
