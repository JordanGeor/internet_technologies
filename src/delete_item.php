<?php
// delete_item.php

require_once 'auth_check.php';
require 'config.php'; // Σύνδεση με τη βάση


// Έλεγχος ότι υπάρχει το ID
if (!isset($_GET['id'])) {
    echo "Λείπει το ID του item.";
    exit;
}

$id = intval($_GET['id']);

// Εκτέλεση διαγραφής
$stmt = $conn->prepare("DELETE FROM list_items WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "✅ Το item διαγράφηκε επιτυχώς.";
} else {
    echo "❌ Σφάλμα κατά τη διαγραφή.";
}

$stmt->close();
$conn->close();
?>
