<?php
// Έλεγχος αν η συνεδρία έχει ήδη ξεκινήσει
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <ul>
        <li><a href="index.php">Αρχική Σελίδα</a></li>
        <li><a href="skopos.html">Σκοπός</a></li>
	<li><a href="help.html">Βοήθεια</a></li>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <li><a href="profile.php">Το Προφίλ Μου</a></li>
            <li><a href="task_lists.php">Λίστες Εργασιών</a></li>
            <li><a href="search.php">Αναζήτηση</a></li>
            <li><a href="export.php">Εξαγωγή Δεδομένων</a></li>
            <li><a href="logout.php">Αποσύνδεση</a></li>
        <?php else: ?>
            <li><a href="login.php">Σύνδεση</a></li>
            <li><a href="register.php">Εγγραφή</a></li>
        <?php endif; ?>
    </ul>
</nav>