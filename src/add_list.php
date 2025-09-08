<?php
session_start();
require_once 'auth_check.php';
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $user_id = $_SESSION['user_id'];

    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO lists (title, user_id) VALUES (?, ?)");
        $stmt->bind_param("si", $title, $user_id);
        $stmt->execute();
        $stmt->close();

        header("Location: lists.php");
        exit;
    } else {
        $error = "Το όνομα της λίστας είναι υποχρεωτικό.";
    }
}
?>

<h2>➕ Δημιουργία νέας λίστας</h2>

<?php if (isset($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">
    <label for="title">Όνομα λίστας:</label>
    <input type="text" name="title" id="title" required>
    <button type="submit">Δημιουργία</button>
</form>
