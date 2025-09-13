<?php
session_start();
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/config.php'; // εδώ δημιουργείται $conn (mysqli)

if (!isset($_GET['list_id']) || !ctype_digit($_GET['list_id'])) {
    header('Location: /lists.php?err=missing_or_invalid_id');
    exit;
}

$listId = (int) $_GET['list_id'];
$userId = (int) $_SESSION['user_id'];

// 1) Έλεγχος αν η λίστα υπάρχει και αν ανήκει στον χρήστη
$stmt = $conn->prepare('SELECT user_id FROM lists WHERE id = ?');
$stmt->bind_param('i', $listId);
$stmt->execute();
$stmt->bind_result($ownerId);

if (!$stmt->fetch()) {
    $stmt->close();
    header('Location: /lists.php?err=list_not_found');
    exit;
}
$stmt->close();

if ((int)$ownerId !== $userId) {
    header('Location: /lists.php?err=forbidden');
    exit;
}

// 2) Διαγραφή της λίστας
$del = $conn->prepare('DELETE FROM lists WHERE id = ?');
$del->bind_param('i', $listId);
$del->execute();

if ($del->affected_rows > 0) {
    header('Location: /lists.php?ok=list_deleted');
} else {
    header('Location: /lists.php?err=delete_failed');
}
$del->close();
exit;
