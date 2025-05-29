<?php
session_start();
include 'config.php';

// Αναζήτηση με βάση το κείμενο στον τίτλο ή/και την κατάσταση
$search_title = '';
$search_status = '';

// SQL για την αναζήτηση των λιστών και εργασιών
$query = "SELECT task_lists.id AS list_id, task_lists.title AS list_title, tasks.id AS task_id, tasks.title AS task_title, tasks.status, tasks.created_at
          FROM task_lists
          LEFT JOIN tasks ON tasks.list_id = task_lists.id
          WHERE task_lists.user_id = ?
          AND (tasks.title LIKE ? OR task_lists.title LIKE ?)
          AND (tasks.status = ? OR ? = '')";

// Έτοιμα δεδομένα για το SQL query
$params = [$_SESSION['user_id'], "%$search_title%", "%$search_title%", $search_status, $search_status];

// Αν η φόρμα αναζήτησης έχει υποβληθεί
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['search_title'])) {
        $search_title = $_POST['search_title'];
        $params[1] = "%$search_title%";  // Αναζήτηση τίτλου
        $params[2] = "%$search_title%";  // Αναζήτηση τίτλου στη λίστα
    }

    if (!empty($_POST['search_status'])) {
        $search_status = $_POST['search_status'];
        $params[3] = $search_status;  // Αναζήτηση κατάστασης
        $params[4] = $search_status;  // Αναζήτηση κατάστασης
    }
}

$stmt = $conn->prepare($query);
$stmt->bind_param("issss", ...$params);  // Χρήση όλων των παραμέτρων
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Αναζήτηση Εργασιών και Λιστών</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Αρχική Σελίδα</a></li>
	    <li><a href="task_lists.php">Οι Λίστες Εργασιών μου</a></li>
            <li><a href="logout.php">Αποσύνδεση</a></li>
        </ul>
    </nav>

    <h1>Αναζήτηση Λιστών και Εργασιών</h1>

    <!-- Φόρμα Αναζήτησης -->
    <form method="POST" action="search.php">
        <input type="text" name="search_title" placeholder="Τίτλος Λίστας ή Εργασίας" value="<?php echo htmlspecialchars($search_title); ?>">
        <select name="search_status">
            <option value="">Όλες οι Καταστάσεις</option>
            <option value="Σε αναμονή" <?php if ($search_status == 'Σε αναμονή') echo 'selected'; ?>>Σε αναμονή</option>
            <option value="Σε εξέλιξη" <?php if ($search_status == 'Σε εξέλιξη') echo 'selected'; ?>>Σε εξέλιξη</option>
            <option value="Ολοκληρωμένη" <?php if ($search_status == 'Ολοκληρωμένη') echo 'selected'; ?>>Ολοκληρωμένη</option>
        </select>
        <input type="submit" value="Αναζήτηση">
    </form>

    <!-- Προβολή των αποτελεσμάτων -->
    <?php if ($result->num_rows > 0) { ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li>
                    <h3>Λίστα: <?php echo htmlspecialchars($row['list_title']); ?></h3>
                    <strong>Εργασία:</strong> <?php echo htmlspecialchars($row['task_title']?? 'Δεν έχει δοθεί εργασία'); ?><br>
                    <strong>Κατάσταση:</strong> <?php echo $row['status']; ?><br>
                    <strong>Ημερομηνία Δημιουργίας:</strong> <?php echo $row['created_at']; ?><br>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>Δεν βρέθηκαν εργασίες ή λίστες με αυτά τα κριτήρια.</p>
    <?php } ?>

    <footer>
        <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
    </footer>
</body>
</html>
