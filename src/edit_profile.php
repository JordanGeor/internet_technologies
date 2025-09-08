<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';
include 'navprofile.php';
require_once 'auth_check.php';

$user_id = $_SESSION['user_id'];



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    

    // Ενημέρωση στοιχείων χρήστη
    $sql = "UPDATE users SET firstname=?, lastname=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $firstname, $lastname, $email, $user_id);
    $stmt->execute();
}

// Ανάκτηση των στοιχείων του χρήστη
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Επεξεργασία Προφίλ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Επεξεργασία Προφίλ</h2>
    <form action="edit_profile.php" method="POST">
        <label for="firstname">Όνομα:</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo $user['firstname']; ?>" required><br>
        
        <label for="lastname">Επώνυμο:</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required><br>

        <button type="submit">Αποθήκευση</button>
    </form>

    <footer>
        <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
    </footer>
</body>
</html>
