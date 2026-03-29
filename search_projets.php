<?php
include 'connexion.php';

$mot = $connexion->real_escape_string(trim($_GET['q'] ?? ''));

$sql = "SELECT p.*, c.nom, c.prenom 
        FROM projets p
        INNER JOIN chercheurs c ON p.id_chercheur_principal = c.id_chercheur
        WHERE p.titre LIKE '%$mot%'
        OR c.nom LIKE '%$mot%'
        OR c.prenom LIKE '%$mot%'
        ORDER BY p.titre ASC";

$res      = $connexion->query($sql);
$resultats = [];

while($p = $res->fetch_assoc()){
    $resultats[] = $p;
}

header('Content-Type: application/json');
echo json_encode($resultats);
?>