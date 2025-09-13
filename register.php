<?php
session_start();

// Σύνδεση στη βάση
require 'config.php';


// Έλεγχος αν η φόρμα υποβλήθηκε
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname  = $_POST['lastname'];
    $user      = $_POST['username'];
    $email     = $_POST['email'];
    $pass      = $_POST['password'];

    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Έλεγχος αν υπάρχει ήδη το email ή το username
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $checkUser  = "SELECT * FROM users WHERE username = '$user'";

    $emailResult = $conn->query($checkEmail);
    $userResult  = $conn->query($checkUser);

    if ($emailResult->num_rows > 0) {
        echo "<script>alert('Το email χρησιμοποιείται ήδη. Δοκιμάστε διαφορετικό.');</script>";
        echo "<script>window.location.href='register.php';</script>";
    } elseif ($userResult->num_rows > 0) {
        echo "<script>alert('Το όνομα χρήστη υπάρχει ήδη. Επιλέξτε άλλο.');</script>";
        echo "<script>window.location.href='register.php';</script>";
    } else {
        $sql = "INSERT INTO users (firstname, lastname, username, email, password) 
                VALUES ('$firstname', '$lastname', '$user', '$email', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $user;
            header("Location: profile.php");
            exit();
        } else {
            echo "<script>alert('Παρουσιάστηκε σφάλμα κατά την εγγραφή.');</script>";
            echo "<script>window.location.href='register.php';</script>";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Λίστα Εργασιών</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
<!-- Navigation Menu -->
   <nav>
        <ul id="nav-menu">
            <li><a href="index.html">Αρχική Σελίδα</a></li>
            <li><a href="help.html">Βοήθεια</a></li>
            <!-- Οι αυθεντικοποιημένες επιλογές -->
            <li id="profile-menu" style="display: none;"><a href="profile.html">Profile</a></li>
            <li id="tasks-menu" style="display: none;"><a href="tasks.html">My Tasks</a></li>
            <li id="logout-menu" style="display: none;"><a href="logout.html">Logout</a></li>
            <!-- Οι μη αυθεντικοποιημένες επιλογές -->
            <li id="login-menu"><a href="login.php">Σύνδεση Χρήστη</a></li>
            <li id="signup-menu"><a href="register.php">Εγγραφή Χρήστη</a></li>
        </ul>
    </nav>

 
    <!-- Main content -->
    <section class="signup-section">
        <h1>Εγγραφή Χρήστη</h1>
        <form action="register.php" method="POST"> <!-- Σύνδεση με register.php -->
            <label for="firstname">Όνομα:</label>
            <input type="text" id="firstname" name="firstname" required><br><br>
            
            <label for="lastname">Επώνυμο:</label>
            <input type="text" id="lastname" name="lastname" required><br><br>
            
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            
            <input type="submit" value="Εγγραφή">
        </form>
        <p>Έχεις Λογαριασμό; <a href="login.php">Σύνδεση</a></p>
    </section>

    <footer>
        <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
    </footer>
</body>
</html>