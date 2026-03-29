<?php
include 'connexion.php';

if(isset($_POST['id_chercheur'])){

    $id              = intval($_POST['id_chercheur']);
    $nom             = $connexion->real_escape_string(trim($_POST['nom']));
    $prenom          = $connexion->real_escape_string(trim($_POST['prenom']));
    $email           = $connexion->real_escape_string(trim($_POST['email']));
    $specialite      = $connexion->real_escape_string(trim($_POST['specialite']));
    $date_inscription = $connexion->real_escape_string($_POST['date_inscription']);

    // Validation côté serveur
    $errors = [];

    if(empty($nom))        $errors[] = "Le nom est obligatoire.";
    if(empty($prenom))     $errors[] = "Le prénom est obligatoire.";
    if(empty($specialite)) $errors[] = "La spécialité est obligatoire.";

    if(empty($email)){
        $errors[] = "L'email est obligatoire.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Format email invalide.";
    }

    if(empty($date_inscription)){
        $errors[] = "La date est obligatoire.";
    } elseif($date_inscription > date('Y-m-d')){
        $errors[] = "La date ne peut pas être dans le futur.";
    }

    // Vérifier email unique (sauf pour ce chercheur lui-même)
    $check = $connexion->query("SELECT id_chercheur FROM chercheurs 
                                WHERE email='$email' AND id_chercheur != $id");
    if($check->num_rows > 0){
        $errors[] = "Cet email est déjà utilisé par un autre chercheur.";
    }

    if(!empty($errors)){
        foreach($errors as $err){
            echo "<p style='color:red'>$err</p>";
        }
        echo "<a href='modifier_chercheur.php?id=$id'>Retour</a>";
        exit();
    }

    // Requête UPDATE en POO
    $sql = "UPDATE chercheurs SET
                nom              = '$nom',
                prenom           = '$prenom',
                email            = '$email',
                specialite       = '$specialite',
                date_inscription = '$date_inscription'
            WHERE id_chercheur = $id";

    if($connexion->query($sql)){
        header("Location: chercheurs.php?status=updated");
        exit();
    } else {
        echo "Erreur SQL : " . $connexion->error;
    }
}
?>