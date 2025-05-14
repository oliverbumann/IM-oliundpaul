<?php
session_start();
require_once('../system/config.php');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'Nicht eingeloggt']);
  exit;
}

$user_id = $_SESSION['user_id'];
$heute = date('Y-m-d');

// Nur zukünftige oder heutige Medikamente
$stmt = $pdo->prepare("
  SELECT id, name, start_date, start_time, urgency, frequency
  FROM medications
  WHERE user_id = :uid AND start_date >= :heute
  ORDER BY start_date ASC
");
$stmt->execute([':uid' => $user_id, ':heute' => $heute]);
$medis = $stmt->fetchAll();

echo json_encode([
  'status' => 'success',
  'medikamente' => $medis
]);
exit;
