<?php
// Έλεγχος αν η συνεδρία έχει ήδη ξεκινήσει
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <ul>
        <li><a href="index.php">Αρχική</a></li>
        <li><a href="skopos.html">Σκοπός</a></li>
        <li><a href="help.html">Βοήθεια</a></li>

        <?php if (!empty($_SESSION['loggedin'])): ?>
            <li><a href="profile.php">Προφίλ</a></li>
            <li><a href="lists.php">Οι Λίστες μου</a></li>
            <li><a href="export_yaml.php">Εξαγωγή</a></li>
            <li><a href="logout.php">Αποσύνδεση</a></li>
        <?php else: ?>
            <li><a href="login.php">Σύνδεση</a></li>
            <li><a href="register.php">Εγγραφή</a></li>
        <?php endif; ?>
    </ul>
</nav>