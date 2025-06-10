<?php
// db.php
$host = "192.168.1.108";   // or MySQL server IP
$user = "noder";        // adapt if needed
$pass = "passroot";            // XAMPP password (often empty by default)
$dbname = "sae23";     // your database name

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Database connection error: " . mysqli_connect_error());
}
?>