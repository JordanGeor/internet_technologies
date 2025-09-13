<?php 
session_start();
require_once 'auth_check.php';
require 'config.php'; // Σύνδεση με τη βάση δεδομένων


// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ανάκτηση των στοιχείων του χρήστη από τη βάση δεδομένων
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
include 'navprofile.php';
if ($user) {
    echo "<h1>Προφίλ Χρήστη</h1>";
    echo "Όνομα: " . htmlspecialchars($user['firstname']) . "<br>";
    echo "Επώνυμο: " . htmlspecialchars($user['lastname']) . "<br>";
    echo "Email: " . htmlspecialchars($user['email']) . "<br>";
    //echo "<a href='edit_profile.php'>Επεξεργασία Προφίλ</a><br>";
    //echo "<a href='delete_profile.php'>Διαγραφή Προφίλ</a>";
 
} else {
    echo "Ο χρήστης δεν βρέθηκε.";
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Σύνδεση</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
<footer>
        <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
    </footer>
        
</body>
</html>
