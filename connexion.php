<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "unao_projets_db";

$connexion = new mysqli($host, $username, $password, $database);

if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

$connexion->set_charset("utf8");
?>