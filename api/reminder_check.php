<?php
session_start();
require_once('../system/config.php');
header('Content-Type: application/json');

// Prüfen ob eingeloggt
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Nicht eingeloggt']);
    exit;
}

$user_id = $_SESSION['user_id'];
$heute = date('Y-m-d');
$jetzt = date('H:i');

// Medis für heute holen, die noch nicht genommen wurden
$stmt = $pdo->prepare("
  SELECT s.id AS schedule_id, s.time, s.date, s.reminder_sent_at, s.reminder_counter, s.taken,
         m.name, m.urgency
  FROM schedules s
  JOIN medications m ON s.medication_id = m.id
  WHERE s.user_id = :uid AND s.date = :heute AND s.taken = 0
");
$stmt->execute([':uid' => $user_id, ':heute' => $heute]);
$einträge = $stmt->fetchAll();

$reminderListe = [];

foreach ($einträge as $eintrag) {
    $geplanteZeit = DateTime::createFromFormat('H:i:s', $eintrag['time']);
    $jetztZeit = new DateTime($jetzt);
    $diffMinuten = $jetztZeit->getTimestamp() - $geplanteZeit->getTimestamp();
    $diffMinuten = $diffMinuten / 60;

    // Noch nicht fällig
    if ($diffMinuten < 0) continue;

    // Zeitkritisch prüfen
    $intervall = 0;
    $limit = 0;

    switch ($eintrag['urgency']) {
        case '1h':
            $intervall = 20;
            $limit = 60;
            break;
        case '2h':
            $intervall = 40;
            $limit = 120;
            break;
        case '3h':
            $intervall = 60;
            $limit = 180;
            break;
        default:
            // "egal" → nur einmal benachrichtigen
            if ($eintrag['reminder_counter'] == 0) {
                $reminderListe[] = [
                    'name' => $eintrag['name'],
                    'zu_spaet' => false
                ];

                // Reminder speichern
                $update = $pdo->prepare("UPDATE schedules SET reminder_sent_at = NOW(), reminder_counter = 1 WHERE id = :id");
                $update->execute([':id' => $eintrag['schedule_id']]);
            }
            continue 2;
    }

    // Reminder schon beendet?
    if ($diffMinuten > $limit) {
        if ($eintrag['reminder_counter'] < 4) {
            $reminderListe[] = [
                'name' => $eintrag['name'],
                'zu_spaet' => true
            ];

            $update = $pdo->prepare("UPDATE schedules SET reminder_counter = 4 WHERE id = :id");
            $update->execute([':id' => $eintrag['schedule_id']]);
        }
    } else {
        // Intervall-Reminder: z. B. alle 20min → 1 + floor(x / 20)
        $erwartet = 1 + floor($diffMinuten / $intervall);
        if ($eintrag['reminder_counter'] < $erwartet && $erwartet <= 3) {
            $reminderListe[] = [
                'name' => $eintrag['name'],
                'zu_spaet' => false
            ];

            $update = $pdo->prepare("UPDATE schedules SET reminder_sent_at = NOW(), reminder_counter = :counter WHERE id = :id");
            $update->execute([
                ':counter' => $erwartet,
                ':id' => $eintrag['schedule_id']
            ]);
        }
    }
}

echo json_encode([
    'status' => 'success',
    'reminder' => $reminderListe
]);
exit;
