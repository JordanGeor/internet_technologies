<?php
session_start();
require 'config.php';

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Ανάκτηση των λιστών εργασιών του χρήστη
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, title, created_at FROM task_lists WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$task_lists = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Λίστες Εργασιών</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <title>Λίστες Εργασιών</title>
    <link rel="stylesheet" href="style.css">
 <nav>
        <ul id="nav-menu">
            <li><a href="index.php">Αρχική Σελίδα</a></li>
            <li><a href="task_lists.php">Οι Λίστες με τις Εργασίες μου</a></li>
           
            <li><a href="logout.php">Αποσύνδεση</a></li>
        </ul>
    </nav>   
<h1>Οι Λίστες Μου</h1>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<form method="POST" action="task_list_create.php">
        <input type="text" name="title" placeholder="Νέα Λίστα" required>
        <input type="submit" value="Δημιουργία Λίστας">
    </form>

    <div style="display: flex; gap: 20px;">
        <?php while ($list = $task_lists->fetch_assoc()) { ?>
            <div>
                <h3><?php echo htmlspecialchars($list['title']); ?></h3>
                <p>Δημιουργήθηκε: <?php echo $list['created_at']; ?></p>
                <a href="task_list_view.php?list_id=<?php echo $list['id']; ?>">Δείτε τις εργασίες</a>
                <form method="POST" action="task_list_delete.php" style="display: inline;">
                    <input type="hidden" name="list_id" value="<?php echo $list['id']; ?>">
                    <input type="submit" value="Διαγραφή">
                </form>
            </div>
        <?php } ?>
    </div>
<footer>
        <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
    </footer>
</body>
</html>
