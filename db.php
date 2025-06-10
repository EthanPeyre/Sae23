<?php
// db.php
$host = "192.168.1.108";   // ou IP du serveur MySQL
$user = "noder";        // adapte si besoin
$pass = "passroot";            // mot de passe XAMPP (souvent vide par défaut)
$dbname = "sae23";     // nom de ta base

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
?>