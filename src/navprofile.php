<?php
// Έλεγχος αν η συνεδρία έχει ήδη ξεκινήσει
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <ul>
        <li><a href="index.php">Αρχική Σελίδα</a></li>
        
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <li><a href="profile.php">Το Προφίλ Μου</a></li>
            <li><a href='edit_profile.php'>Επεξεργασία Προφίλ</a></li>
	    <li><a href='delete_profile.php'>Διαγραφή Προφίλ</a></li>

        <?php else: ?>
            <li><a href="login.php">Σύνδεση</a></li>
            <li><a href="register.php">Εγγραφή</a></li>
        <?php endif; ?>
    </ul>
</nav>