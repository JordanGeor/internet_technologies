<?php
session_start();
// Σύνδεση στη βάση
$servername = "mysql_container";
$username = "dogas";
$password = "dogas2002";
$dbname = "users_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Σφάλμα σύνδεσης: " . $conn->connect_error);
}
// Απενεργοποιήστε την εμφάνιση των warnings για να αποφευχθούν τα προβλήματα με τα headers
error_reporting(E_ERROR | E_PARSE);

// Έλεγχος αν είναι POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Έλεγχος αν όλα τα απαιτούμενα δεδομένα είναι διαθέσιμα
    if (isset($_POST['task_id'], $_POST['status'], $_POST['list_id'])) {
        $task_id = $_POST['task_id'];
        $status = $_POST['status'];
        $list_id = $_POST['list_id']; // Πρέπει να μεταβιβαστεί σωστά από τη φόρμα

        // Έλεγχος αν υπάρχει νέο όνομα χρήστη για ανάθεση
        if (isset($_POST['assigned_user']) && !empty($_POST['assigned_user'])) {
            $assigned_user = $_POST['assigned_user'];

            // Εύρεση του χρήστη με βάση το όνομα
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $assigned_user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $assigned_user_id = $result->fetch_assoc()['id'];
            } else {
                // Αν δεν βρεθεί ο χρήστης, επιστροφή στη σελίδα με σφάλμα
                echo "Ο χρήστης που προσπαθείτε να αναθέσετε την εργασία δεν υπάρχει.";
                exit;
            }
        } else {
            // Αν δεν υπάρχει ανάθεση, βάλτε assigned_user_id ως NULL
            $assigned_user_id = null;
        }

        // Ενημέρωση της εργασίας (κατάσταση και πιθανή ανάθεση)
        $stmt = $conn->prepare("UPDATE tasks SET status = ?, assigned_user_id = ? WHERE id = ?");
        $stmt->bind_param("sii", $status, $assigned_user_id, $task_id);

        if ($stmt->execute()) {
            // Επιστροφή στη σελίδα προβολής της λίστας εργασιών μετά την επιτυχή ενημέρωση
            header("Location: task_list_view.php?list_id=" . $list_id);
            exit;
        } else {
            echo "Σφάλμα κατά την ενημέρωση της εργασίας.";
        }
    } else {
        echo "Λείπουν απαραίτητα δεδομένα.";
    }
} else {
    echo "Μη έγκυρο αίτημα.";
}
?>