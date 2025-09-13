<?php
// lists.php
session_start();
require_once 'auth_check.php';
require_once 'config.php';

$user_id = $_SESSION['user_id'] ?? 1;

$stmt = $conn->prepare("SELECT * FROM lists WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Οι λίστες μου</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>

<main>
    <h1>Οι λίστες μου</h1>
    <a href="add_list.php">➕ Νέα λίστα</a>
    <ul>
        <?php while ($list = $result->fetch_assoc()): ?>
            <li>
                <a href="list_items.php?list_id=<?= $list['id'] ?>">
                    <?= htmlspecialchars($list['title'] ?? 'Χωρίς τίτλο') ?>
                </a>
                | <a href="delete_list.php?list_id=<?= $list['id'] ?>" onclick="return confirm('Σίγουρα;')">❌</a>
            </li>
        <?php endwhile; ?>
    </ul>
</main>

<footer>
    <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
</footer>
</body>
</html>