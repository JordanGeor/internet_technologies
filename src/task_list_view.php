<?php
session_start();
require 'config.php';

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$list_id = $_GET['list_id'];
$user_id = $_SESSION['user_id'];

// Ανάκτηση των εργασιών της λίστας
$stmt = $conn->prepare("SELECT tasks.id, tasks.title, tasks.status, tasks.created_at, tasks.assigned_user_id, users.username AS assigned_user
                        FROM tasks
                        LEFT JOIN users ON tasks.assigned_user_id = users.id
                        WHERE tasks.list_id = ? ORDER BY tasks.created_at DESC");
$stmt->bind_param("i", $list_id);
$stmt->execute();
$tasks = $stmt->get_result();

// Συνάρτηση για την εύρεση του ονόματος χρήστη βάσει του id
function getUsernameById($user_id, $conn) {
    if (!$user_id) {
        return 'Κανένας';
    }
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user ? $user['username'] : 'Κανένας';
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Εργασίες Λίστας</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation Menu -->
    <nav>
        <ul id="nav-menu">
            <li><a href="index.php">Αρχική Σελίδα</a></li>
            <li><a href="task_lists.php">Οι εργασίες μου</a></li>
            <li><a href="task_lists.php">Δημιουργία Νέας Λίστας Εργασιών</a></li>
            <li><a href="logout.php">Αποσύνδεση</a></li>
        </ul>
    </nav>

    <h1>Εργασίες στη Λίστα</h1>

    <!-- Φόρμα για τη δημιουργία νέας εργασίας -->
    <form method="POST" action="task_create.php">
        <input type="text" name="title" placeholder="Νέα Εργασία" required>
        <input type="hidden" name="list_id" value="<?php echo $list_id; ?>">
        <input type="text" name="assigned_user" placeholder="Ανάθεση Χρήστη (Προαιρετικό)">
        <input type="submit" value="Δημιουργία Εργασίας"><br>
    </form>

    <ul>
        <?php while ($task = $tasks->fetch_assoc()) { ?>
            <li>
                <strong><?php echo htmlspecialchars($task['title']); ?></strong> (<?php echo $task['status']; ?>)
                <br>Ανάθεση σε: <?php echo htmlspecialchars(getUsernameById($task['assigned_user_id'], $conn)); ?><br>
                <br>Ημερομηνία: <?php echo $task['created_at']; ?>
                <br><br>
                <!-- Φόρμα για ενημέρωση κατάστασης εργασίας -->
                <form method="POST" action="task_update.php">
                    <label for="status">Κατάσταση:</label>
                    <select name="status">
                        <option value="Σε αναμονή" <?php if ($task['status'] == 'Σε αναμονή') echo 'selected'; ?>>Σε αναμονή</option>
                        <option value="Σε εξέλιξη" <?php if ($task['status'] == 'Σε εξέλιξη') echo 'selected'; ?>>Σε εξέλιξη</option>
                        <option value="Ολοκληρωμένη" <?php if ($task['status'] == 'Ολοκληρωμένη') echo 'selected'; ?>>Ολοκληρωμένη</option> <br>
                    </select>
                   
                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                    <input type="hidden" name="list_id" value="<?php echo $list_id; ?>"> <!-- Σωστό πέρασμα του list_id -->
                    <input type="submit" value="Ενημέρωση">
                </form>

                <!-- Φόρμα για αλλαγή ανάθεσης χρήστη -->
                <form method="POST" action="task_update.php"><br>
                    <label for="assigned_user">Ανάθεση σε χρήστη:</label>
                    <input type="text" name="assigned_user" placeholder="Όνομα Χρήστη" value="<?php echo getUsernameById($task['assigned_user_id'], $conn); ?>">
                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                    <input type="hidden" name="list_id" value="<?php echo $list_id; ?>"> <!-- Για ανακατεύθυνση πίσω στη λίστα -->
                    <input type="submit" value="Ενημέρωση Ανάθεσης"><br><br><br>
                <label>______________________________________________________________________________________________</label>

		</form>

            </li>
        <?php } ?>
    </ul>

    <footer>
        <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
    </footer>

</body>
</html>
