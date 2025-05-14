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

$datum = new DateTime($ausgewähltesDatum);
$tag = $datum->format('Y-m-d');

$stmt = $pdo->prepare("SELECT * FROM medications WHERE user_id = :uid");
$stmt->execute([':uid' => $user_id]);
$alleMedis = $stmt->fetchAll();

$anzeigeListe = [];

foreach ($alleMedis as $m) {
    $start = new DateTime($m['start_date']);
    $diff = $start->diff($datum)->days;

    if ($datum < $start) continue; // Startdatum liegt in der Zukunft

    switch ($m['frequency']) {
        case 'täglich':
            $anzeigeListe[] = $m;
            break;

        case 'wöchentlich':
            if ($diff % 7 === 0) $anzeigeListe[] = $m;
            break;

        case 'monatlich':
            if ($start->format('d') === $datum->format('d')) $anzeigeListe[] = $m;
            break;
    }
}

echo json_encode([
    'status' => 'success',
    'medikamente' => $anzeigeListe
]);
