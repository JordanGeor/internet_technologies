<?php
// Σύνδεση στη βάση
$servername = "mysql";
$username = "jordan";
$password = "123";
$dbname = "my_users_db";
$conn = new mysqli($servername, $username, $password, $dbname);



$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Σφάλμα σύνδεσης: " . $conn->connect_error);
}
