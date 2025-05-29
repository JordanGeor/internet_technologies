<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'] ?? null;
    $list_id = $_POST['list_id'] ?? null;
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if (!$task_id || !$list_id) {
        exit('Λείπει το task_id ή το list_id.');
    }

    // Ανάθεση χρήστη (προαιρετική)
    if (!empty($_POST['assigned_user'])) {
        $assigned_user = $_POST['assigned_user'];

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $assigned_user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $assigned_user_id = $result->fetch_assoc()['id'];
        } else {
            $assigned_user_id = null; // Δεν βρέθηκε χρήστης
        }
    } else {
        $assigned_user_id = null;
    }

    // Ενημέρωση task
    $stmt = $conn->prepare("UPDATE tasks SET status = ?, assigned_user_id = ? WHERE id = ?");
    $stmt->bind_param("sii", $status, $assigned_user_id, $task_id);

    if ($stmt->execute()) {
        header("Location: task_list_view.php?list_id=" . $list_id);
        exit;
    } else {
        echo "Σφάλμα κατά την ενημέρωση της εργασίας.";
    }
}
