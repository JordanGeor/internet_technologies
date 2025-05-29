<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $title = $_POST['title'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO task_lists (title, user_id) VALUES (?, ?)");
    $stmt->bind_param("si", $title, $user_id);

    if ($stmt->execute()) 
    {
        header('Location: task_lists.php');
        exit;
    } 
    else 
    {
        echo "Σφάλμα κατά τη δημιουργία της λίστας.";
    }
}
?>
