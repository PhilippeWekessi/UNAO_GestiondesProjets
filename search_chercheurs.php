<?php
include 'connexion.php';

$mot = $connexion->real_escape_string(trim($_GET['q'] ?? ''));

$sql = "SELECT * FROM chercheurs 
        WHERE nom LIKE '%$mot%' 
        OR prenom LIKE '%$mot%' 
        OR email LIKE '%$mot%'
        ORDER BY nom ASC";

$res      = $connexion->query($sql);
$resultats = [];

while($c = $res->fetch_assoc()){
    $resultats[] = $c;
}

header('Content-Type: application/json');
echo json_encode($resultats);
?>