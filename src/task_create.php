<?php
session_start();
require 'functions.php';
include 'config.php';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $list_id = $_POST['list_id'];
    $assigned_user = $_POST['assigned_user'];

    // Εύρεση χρήστη με βάση το όνομα
    $assigned_user_id = null;
    if (!empty($assigned_user)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $assigned_user);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $assigned_user_id = $result->fetch_assoc()['id'];
        }
    }
    else {
        $assigned_user_id = $user_id;
    }


    $stmt = $conn->prepare("INSERT INTO tasks (title, list_id, assigned_user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $title, $list_id, $assigned_user_id);

    if ($stmt->execute()) {
        header("Location: task_lists.php?list_id=" . $list_id);
        exit;
    } else {
        echo "Σφάλμα κατά τη δημιουργία της εργασίας.";
    }
}
?>
