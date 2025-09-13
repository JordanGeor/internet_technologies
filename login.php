<?php
// Ξεκινάμε τη συνεδρία
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Ελέγχουμε αν τα πεδία δεν είναι κενά
    if (empty($username) || empty($password)) {
        $error_message = "Παρακαλούμε συμπληρώστε όλα τα πεδία.";
    } else {
        // Ερώτημα για τον έλεγχο των στοιχείων χρήστη
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Έλεγχος εάν ο χρήστης βρέθηκε
            $row = $result->fetch_assoc();
            // Επιβεβαίωση κωδικού (χρησιμοποιήστε password_hash και password_verify για ασφάλεια)
            if (password_verify($password, $row['password'])) {
                // Εισαγωγή στοιχείων στη συνεδρία
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                // Ανακατεύθυνση σε προστατευμένη σελίδα
                header("Location: index.php");
                exit;
            } else {
                // Λάθος κωδικός
                $error_message = "Λανθασμένος κωδικός.";
            }
        } else {
            // Ο χρήστης δεν βρέθηκε
            $error_message = "Ο χρήστης δεν βρέθηκε.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Σύνδεση</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?> <!-- Προσθέστε το μενού πλοήγησης -->

    <div class="container">
        <h2>Σύνδεση Χρήστη</h2>

        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Όνομα Χρήστη:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Κωδικός:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <button type="submit">Σύνδεση</button>
            </div>
        </form>

        <p>Δεν έχετε λογαριασμό; <a href="register.php">Εγγραφείτε εδώ</a>.</p>
    </div>
<footer>
        <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
    </footer>
</body>
</html>






