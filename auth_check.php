<?php
// auth_check.php

// Ξεκίνα το session αν δεν έχει ξεκινήσει
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Έλεγχος αν ο χρήστης είναι αυθεντικοποιημένος
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Αποθήκευση της αρχικής σελίδας που πήγε να ανοίξει (για Bonus redirect)
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

    // Ανακατεύθυνση στο login
    header('Location: login.php');
    exit;
}
?>
