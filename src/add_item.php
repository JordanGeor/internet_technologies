<?php
// add_item.php

require_once 'auth_check.php';
require_once 'config.php';

$list_id = $_GET['list_id'] ?? null;
$video_id = $_GET['video_id'] ?? '';
$title = $_GET['title'] ?? '';

if (!isset($_SESSION['user_id'])) {
    die("Μη εξουσιοδοτημένη πρόσβαση.");
}
$user_id = $_SESSION['user_id'];

if (!$list_id) {
    die("Δεν καθορίστηκε λίστα.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $video_id = $_POST['video_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $list_id = $_POST['list_id'] ?? '';

    if (!isset($_SESSION['user_id'])) {
        die("Μη εξουσιοδοτημένη πρόσβαση.");
    }
    $user_id = $_SESSION['user_id'];

    if ($video_id && $title && $list_id) {
        $stmt = $conn->prepare("INSERT INTO list_items (list_id, user_id, youtube_title, youtube_video_id, added_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiss", $list_id, $user_id, $title, $video_id);
        $stmt->execute();

        header("Location: list_items.php?list_id=$list_id");
        exit;
    } else {
        echo "Όλα τα πεδία είναι υποχρεωτικά.";
    }
}
?>

<h2>➕ Προσθήκη Video στη Λίστα</h2>
<form method="post">
    <input type="hidden" name="list_id" value="<?= htmlspecialchars($list_id) ?>">
    <label>Τίτλος:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required><br><br>

    <label>YouTube Video ID:</label><br>
    <input type="text" name="video_id" value="<?= htmlspecialchars($video_id) ?>" required><br><br>

    <button type="submit">➕ Καταχώρηση</button>
</form>

<a href="list_items.php?list_id=<?= urlencode($list_id) ?>">⬅️ Πίσω στη λίστα</a>
