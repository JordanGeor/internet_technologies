<?php
// search_items.php
require_once 'auth_check.php';
require_once 'config.php';

$search = $_GET['search'] ?? '';
$list_id = $_GET['list_id'] ?? null;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$items_per_page = 10;
$offset = ($page - 1) * $items_per_page;

if (!$list_id) {
    header('Location: lists.php');
    exit;
}

// Για σελιδοποίηση: μέτρησε τα αποτελέσματα
$count_sql = "
    SELECT COUNT(*)
    FROM list_items li
    JOIN users u ON li.user_id = u.id
    WHERE li.list_id = ?
      AND (
        li.youtube_title LIKE ?
        OR li.added_at LIKE ?
        OR u.firstname LIKE ?
        OR u.lastname LIKE ?
        OR u.username LIKE ?
        OR u.email LIKE ?
    )
";
$stmt = $conn->prepare($count_sql);
$searchParam = '%' . $search . '%';
$stmt->bind_param("sssssss", $list_id, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
$stmt->execute();
$stmt->bind_result($total_results);
$stmt->fetch();
$stmt->close();

$total_pages = ceil($total_results / $items_per_page);

// Κύριο ερώτημα αναζήτησης
$sql = "
    SELECT li.*, u.firstname, u.lastname, u.username, u.email
    FROM list_items li
    JOIN users u ON li.user_id = u.id
    WHERE li.list_id = ?
      AND (
        li.youtube_title LIKE ?
        OR li.added_at LIKE ?
        OR u.firstname LIKE ?
        OR u.lastname LIKE ?
        OR u.username LIKE ?
        OR u.email LIKE ?
    )
    ORDER BY li.added_at DESC
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssi", $list_id, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Αποτελέσματα Αναζήτησης</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>

<main>
<h2>Αποτελέσματα Αναζήτησης</h2>
<a href="list_items.php?list_id=<?= htmlspecialchars($list_id) ?>">⬅️ Επιστροφή στη λίστα</a><br><br>

<?php if ($result->num_rows === 0): ?>
    <p>Δεν βρέθηκαν αποτελέσματα.</p>
<?php else: ?>
<ul>
    <?php while ($row = $result->fetch_assoc()): ?>
        <li style="margin-bottom: 30px;">
            <strong><?= htmlspecialchars($row['youtube_title']) ?></strong><br>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?= htmlspecialchars($row['youtube_video_id']) ?>" frameborder="0" allowfullscreen></iframe><br>
            Προστέθηκε: <?= htmlspecialchars($row['added_at']) ?><br>
            Από: <?= htmlspecialchars($row['firstname']) ?> <?= htmlspecialchars($row['lastname']) ?> (<?= htmlspecialchars($row['username']) ?>)
        </li>
        <hr>
    <?php endwhile; ?>
</ul>
<?php endif; ?>

<!-- Σελιδοποίηση -->
<div style="margin-top:20px;">
    Σελίδα <?= $page ?> από <?= $total_pages ?><br>
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?list_id=<?= $list_id ?>&search=<?= urlencode($search) ?>&page=<?= $i ?>" style="margin-right:5px;<?= $i === $page ? 'font-weight:bold;' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>
</main>

<footer>
    <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
</footer>
</body>
</html>
