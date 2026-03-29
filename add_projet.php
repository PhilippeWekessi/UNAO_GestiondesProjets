<?php
include 'connexion.php';

if(isset($_POST['titre'])){

    $titre                  = $connexion->real_escape_string(trim($_POST['titre']));
    $description            = $connexion->real_escape_string(trim($_POST['description']));
    $date_debut             = $connexion->real_escape_string($_POST['date_debut']);
    $date_fin_prevue        = $connexion->real_escape_string($_POST['date_fin_prevue']);
    $statut                 = $connexion->real_escape_string($_POST['statut']);
    $budget                 = floatval($_POST['budget']);
    $id_chercheur_principal = intval($_POST['id_chercheur_principal']);
    // Chercheurs secondaires (tableau de cases cochées)
    $secondaires = isset($_POST['chercheurs_secondaires']) ? $_POST['chercheurs_secondaires'] : [];

    // Validation côté serveur
    $errors = [];
    if(empty($titre))       $errors[] = "Le titre est obligatoire.";
    if(empty($description)) $errors[] = "La description est obligatoire.";
    if(empty($date_debut))  $errors[] = "La date de début est obligatoire.";
    if(empty($date_fin_prevue)) $errors[] = "La date de fin est obligatoire.";
    if($date_debut > date('Y-m-d')) $errors[] = "La date de début ne peut pas être dans le futur.";
    if($date_fin_prevue <= $date_debut) $errors[] = "La date de fin doit être après la date de début.";
    if($budget <= 0)        $errors[] = "Le budget doit être positif.";
    if($id_chercheur_principal == 0) $errors[] = "Sélectionnez un chercheur principal.";

    if(!empty($errors)){
        foreach($errors as $err) echo "<p style='color:red'>$err</p>";
        echo "<a href='projets.php'>Retour</a>";
        exit();
    }

    // INSERT projet
    $sql = "INSERT INTO projets (titre, description, date_debut, date_fin_prevue, statut, budget, id_chercheur_principal)
            VALUES ('$titre','$description','$date_debut','$date_fin_prevue','$statut','$budget','$id_chercheur_principal')";

    if($connexion->query($sql)){
        // Récupérer l'id du projet qu'on vient d'insérer
        $id_projet = $connexion->insert_id;

        // Insérer les participations (chercheurs secondaires)
        foreach($secondaires as $id_chercheur){
            $id_chercheur = intval($id_chercheur);
            // Ne pas ajouter le chercheur principal comme secondaire
            if($id_chercheur != $id_chercheur_principal){
                $connexion->query("INSERT INTO participations (id_projet, id_chercheur, role)
                                   VALUES ('$id_projet','$id_chercheur','Co-chercheur')");
            }
        }

        header("Location: projets.php?status=success");
        exit();
    } else {
        echo "Erreur SQL : " . $connexion->error;
    }
}
?>