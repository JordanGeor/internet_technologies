<?php
require_once 'auth_check.php';
require_once 'config.php';

// Δεν χρειάζεται δεύτερο session_start(), το κάνει ήδη το auth_check.php

// Αν θέλεις επιπλέον έλεγχο (προαιρετικά):
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$conn->begin_transaction();

try {
    // 1) Διαγραφή όλων των λιστών που ανήκουν στον χρήστη
    //    (τα list_items αυτών των λιστών θα φύγουν λόγω ON DELETE CASCADE)
    $stmt = $conn->prepare("DELETE FROM lists WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Αποτυχία διαγραφής λιστών: " . $stmt->error);
    }
    $stmt->close();

    // 2) (Προαιρετικό) Αν ΔΕΝ έχεις FK ON DELETE CASCADE στο list_items.user_id,
    //    τότε ξε-σχολίασε τα παρακάτω για να διαγράφονται και τα items που έχει προσθέσει ο χρήστης σε ξένες λίστες.
    /*
    $stmt = $conn->prepare("DELETE FROM list_items WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Αποτυχία διαγραφής list_items από χρήστη: " . $stmt->error);
    }
    $stmt->close();
    */

    // 3) Διαγραφή χρήστη
    //    (αν έχεις FK list_items.user_id ON DELETE CASCADE, τότε ό,τι έχει καταχωρήσει θα φύγει μόνο του)
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Αποτυχία διαγραφής χρήστη: " . $stmt->error);
    }
    $stmt->close();

    // Όλα καλά
    $conn->commit();

    // Καθαρισμός session και redirect
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

    header('Location: login.php');
    exit;

} catch (Exception $e) {
    // Κάτι πήγε στραβά — κάνε rollback
    $conn->rollback();
    http_response_code(500);
    echo "Σφάλμα κατά τη διαγραφή προφίλ: " . htmlspecialchars($e->getMessage());
}
