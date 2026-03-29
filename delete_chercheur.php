<?php
include 'connexion.php';

$id = intval($_GET['id']);

if($id > 0){
    $sql = "DELETE FROM chercheurs WHERE id_chercheur = $id";
    if($connexion->query($sql)){
        header("Location: chercheurs.php?status=deleted");
        exit();
    } else {
        echo "Erreur : " . $connexion->error;
    }
} else {
    header("Location: chercheurs.php");
    exit();
}
?>