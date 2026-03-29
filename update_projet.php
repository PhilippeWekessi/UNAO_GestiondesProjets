<?php
include 'connexion.php';

if(isset($_POST['id_projet'])){

    $id_projet              = intval($_POST['id_projet']);
    $titre                  = $connexion->real_escape_string(trim($_POST['titre']));
    $description            = $connexion->real_escape_string(trim($_POST['description']));
    $date_debut             = $connexion->real_escape_string($_POST['date_debut']);
    $date_fin_prevue        = $connexion->real_escape_string($_POST['date_fin_prevue']);
    $statut                 = $connexion->real_escape_string($_POST['statut']);
    $budget                 = floatval($_POST['budget']);
    $id_chercheur_principal = intval($_POST['id_chercheur_principal']);
    $secondaires            = isset($_POST['chercheurs_secondaires']) ? $_POST['chercheurs_secondaires'] : [];

    // Validation côté serveur
    $errors = [];
    if(empty($titre))            $errors[] = "Le titre est obligatoire.";
    if(empty($description))      $errors[] = "La description est obligatoire.";
    if(empty($date_debut))       $errors[] = "La date de début est obligatoire.";
    if(empty($date_fin_prevue))  $errors[] = "La date de fin est obligatoire.";
    if($date_debut > date('Y-m-d')) $errors[] = "La date de début ne peut pas être dans le futur.";
    if($date_fin_prevue <= $date_debut) $errors[] = "La date de fin doit être après la date de début.";
    if($budget <= 0)             $errors[] = "Le budget doit être positif.";
    if($id_chercheur_principal == 0) $errors[] = "Sélectionnez un chercheur principal.";

    if(!empty($errors)){
        foreach($errors as $err) echo "<p style='color:red'>$err</p>";
        echo "<a href='modifier_projet.php?id=$id_projet'>Retour</a>";
        exit();
    }

    // UPDATE projet
    $sql = "UPDATE projets SET
                titre                  = '$titre',
                description            = '$description',
                date_debut             = '$date_debut',
                date_fin_prevue        = '$date_fin_prevue',
                statut                 = '$statut',
                budget                 = '$budget',
                id_chercheur_principal = '$id_chercheur_principal'
            WHERE id_projet = $id_projet";

    if($connexion->query($sql)){

        // ✅ Stratégie : supprimer toutes les anciennes participations
        // puis réinsérer les nouvelles cochées
        $connexion->query("DELETE FROM participations WHERE id_projet = $id_projet");

        foreach($secondaires as $id_chercheur){
            $id_chercheur = intval($id_chercheur);
            if($id_chercheur != $id_chercheur_principal){
                $connexion->query("INSERT INTO participations (id_projet, id_chercheur, role)
                                   VALUES ('$id_projet','$id_chercheur','Co-chercheur')");
            }
        }

        header("Location: projets.php?status=updated");
        exit();

    } else {
        echo "Erreur SQL : " . $connexion->error;
    }
}
?>
