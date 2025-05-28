<?php
session_start();
require_once('../system/config.php');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'Nicht eingeloggt']);
  exit;
}

$user_id = $_SESSION['user_id'];
$id = $_POST['id'] ?? 0;

if (!$id) {
  echo json_encode(['status' => 'error', 'message' => 'Keine ID angegeben']);
  exit;
}

$stmt = $pdo->prepare("DELETE FROM medications WHERE id = :id AND user_id = :uid");
$stmt->execute([':id' => $id, ':uid' => $user_id]);

if ($stmt->rowCount() > 0) {
  echo json_encode(['status' => 'success', 'message' => 'Medikament wurde gelöscht']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Löschen fehlgeschlagen oder keine Berechtigung']);
}
exit;
