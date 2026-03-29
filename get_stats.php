<?php
include 'connexion.php';

$stats = [
    'total_chercheurs' => $connexion->query("SELECT COUNT(*) as total FROM chercheurs")->fetch_assoc()['total'],
    'total_projets'    => $connexion->query("SELECT COUNT(*) as total FROM projets")->fetch_assoc()['total'],
    'en_cours'         => $connexion->query("SELECT COUNT(*) as total FROM projets WHERE statut='En cours'")->fetch_assoc()['total'],
    'termines'         => $connexion->query("SELECT COUNT(*) as total FROM projets WHERE statut='Terminé'")->fetch_assoc()['total'],
    'annules'          => $connexion->query("SELECT COUNT(*) as total FROM projets WHERE statut='Annulé'")->fetch_assoc()['total'],
    'en_attente'       => $connexion->query("SELECT COUNT(*) as total FROM projets WHERE statut='En attente'")->fetch_assoc()['total'],
];

// Retourner en JSON
header('Content-Type: application/json');
echo json_encode($stats);
?>