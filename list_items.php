<?php
// list_items.php

session_start();
require_once 'auth_check.php';
require_once 'config.php';

$user_id = $_SESSION['user_id'] ?? 1;
$list_id = $_GET['list_id'] ?? 0;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$items_per_page = 10;
$offset = ($page - 1) * $items_per_page;

// Πάρε το όνομα της λίστας
$stmt = $conn->prepare("SELECT * FROM lists WHERE id = ?");
$stmt->bind_param("i", $list_id);
$stmt->execute();
$result = $stmt->get_result();
$list = $result->fetch_assoc();
$stmt->close();

if (!$list) {
    echo "Η λίστα δεν βρέθηκε.";
    exit;
}

// Πάρε συνολικό αριθμό items για σελιδοποίηση
$stmt = $conn->prepare("SELECT COUNT(*) FROM list_items WHERE list_id = ?");
$stmt->bind_param("i", $list_id);
$stmt->execute();
$stmt->bind_result($total_items);
$stmt->fetch();
$stmt->close();

$total_pages = ceil($total_items / $items_per_page);

// Πάρε video της τρέχουσας σελίδας
$stmt = $conn->prepare("SELECT * FROM list_items WHERE list_id = ? ORDER BY added_at DESC LIMIT ?, ?");
$stmt->bind_param("iii", $list_id, $offset, $items_per_page);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Λίστα Βίντεο</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>

<main>
    <h1>Λίστα: <?= htmlspecialchars($list['title'] ?? 'Χωρίς τίτλο') ?></h1>
    <a href="add_item.php?list_id=<?= $list_id ?>">➕ Προσθήκη Video</a><br><br>

    <!-- Φόρμα αναζήτησης -->
    <form action="search_items.php" method="get" style="margin-bottom:20px;">
        <input type="hidden" name="list_id" value="<?= $list_id ?>">
        <input type="text" name="search" placeholder="Αναζήτηση τίτλου, ημερομηνίας ή χρήστη">
        <button type="submit">🔍 Αναζήτηση</button>
    </form>

    <?php if (count($items) === 0): ?>
        <p>Η λίστα δεν έχει ακόμα περιεχόμενο.</p>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
            <div style="margin-bottom: 30px;">
                <h3><?= htmlspecialchars($item['youtube_title']) ?></h3>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/<?= htmlspecialchars($item['youtube_video_id']) ?>" frameborder="0" allowfullscreen></iframe>
                <br>
                <a href="delete_item.php?id=<?= $item['id'] ?>" onclick="return confirm('Θέλεις σίγουρα να διαγράψεις αυτό το video;');">🗑️ Διαγραφή</a>
            </div>
            <hr>
        <?php endforeach; ?>

        <!-- Σελιδοποίηση -->
        <div style="margin-top:20px;">
            Σελίδα <?= $page ?> από <?= $total_pages ?><br>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?list_id=<?= $list_id ?>&page=<?= $i ?>" style="margin-right:5px;<?= $i === $page ? 'font-weight:bold;' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <a href="lists.php">⬅️ Πίσω στις λίστες</a>
</main>

<footer>
    <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
</footer>
</body>
</html>