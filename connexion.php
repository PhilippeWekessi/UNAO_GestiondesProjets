<?php
// Railway fournit ces variables automatiquement
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

try {
    // On ajoute le port car Railway n'utilise pas toujours le 3306 par défaut
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Cela affichera l'erreur précise dans les "Deploy Logs" de Railway
    error_log("Erreur de connexion : " . $e->getMessage());
    die("Une erreur interne est survenue.");
}
?>