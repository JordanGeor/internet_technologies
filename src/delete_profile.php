<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// Βρες όλες τις λίστες του χρήστη
$stmt = $conn->prepare("SELECT id FROM task_lists WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $list_id = $row['id'];

    // Διαγραφή όλων των tasks σε κάθε λίστα
    $stmt2 = $conn->prepare("DELETE FROM tasks WHERE list_id = ?");
    $stmt2->bind_param("i", $list_id);
    $stmt2->execute();
    $stmt2->close();
}
$stmt->close();

// Διαγραφή όλων των λιστών του χρήστη
$stmt = $conn->prepare("DELETE FROM task_lists WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Τελικά διαγραφή του χρήστη
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    session_destroy();
    header("Location: index.php");
    exit;
} else {
    echo "Σφάλμα κατά τη διαγραφή του χρήστη: " . $stmt->error;
}
$stmt->close();
?>