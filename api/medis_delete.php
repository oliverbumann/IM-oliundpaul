<?php
session_start();
require_once('../system/config.php');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'Nicht eingeloggt']);
  exit;
}

$user_id = $_SESSION['user_id'];
$id = $_POST['id'] ?? '';

if (!$id) {
  echo json_encode(['status' => 'error', 'message' => 'Fehlende ID']);
  exit;
}

// Sicherheit: nur eigene Medis löschen
$stmt = $pdo->prepare("DELETE FROM medications WHERE id = :id AND user_id = :uid");
$stmt->execute([':id' => $id, ':uid' => $user_id]);

echo json_encode(['status' => 'success', 'message' => 'Medikament wurde gelöscht']);
exit;
