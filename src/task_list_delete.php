<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $list_id = $_POST['list_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM tasks WHERE list_id = ? ");
    $stmt->bind_param("i", $list_id);
    $stmt->execute();

    // Διαγραφή λίστας αν ανήκει στον χρήστη
    $stmt = $conn->prepare("DELETE FROM task_lists WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $list_id, $user_id);

    if ($stmt->execute()) {
        header('Location: task_lists.php');
        exit;
    } else {
        echo "Σφάλμα κατά τη διαγραφή της λίστας.";
    }
    
}

?>
