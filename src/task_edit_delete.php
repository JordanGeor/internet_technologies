<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';
$user_id = $_SESSION['user_id'];

// Διαγραφή εργασίας
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $sql = "DELETE FROM tasks WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    header("Location: task_edit_delete.php");
}

// Ενημέρωση εργασίας
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task_name'];

    $sql = "UPDATE tasks SET task_name=? WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $task_name, $task_id, $user_id);
    $stmt->execute();
}

// Ανάκτηση εργασιών
$sql = "SELECT * FROM tasks WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$tasks = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Επεξεργασία/Διαγραφή Εργασιών</title>
</head>
<body>
    <h2>Επεξεργασία/Διαγραφή Εργασιών</h2>

    <ul>
        <?php while($task = $tasks->fetch_assoc()): ?>
            <li>
                <form action="task_edit_delete.php" method="POST">
                    <input type="text" name="task_name" value="<?php echo $task['task_name']; ?>" required>
                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                    <button type="submit">Ενημέρωση</button>
                    <a href="task_edit_delete.php?delete=<?php echo $task['id']; ?>">Διαγραφή</a>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
<footer>
        <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
    </footer>
</body>
</html>
