<?php
// Ξεκινάμε τη συνεδρία

session_start();
include 'config.php';


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

   
        <header>
        <h1>Καλώς Ήλθατε!</h1>
    </header>

    <main>
        <p style="text-align: center; font-size: 1.2em; margin-top: 20px;">
            Η συγκεκριμένη εργασία υποβλήθηκε για το μάθημα <strong>Τεχνολογίες Διαδικτύου</strong><br/>
            από τους φοιτητές: <br/><br/>
            <strong>Ιορδάνης Γεωργιάδης Π2020181</strong><br/>
            <strong>Πέτρος Περαντωνάκης inf 2021182</strong><br/>
            <strong>Κωνσταντίνος Σπένδας Π2020148</strong>
        </p>
    </main>

    <footer>
        <p>&copy; Ιόνιο Πανεπιστήμιο - Τμήμα Πληροφορικής</p>
    </footer>
</body>
</html>






