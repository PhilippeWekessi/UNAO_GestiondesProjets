<?php
include 'connexion.php';

if(isset($_POST['nom'])){

    // 1. Récupération et sécurisation (POO)
    $nom             = $connexion->real_escape_string(trim($_POST['nom']));
    $prenom          = $connexion->real_escape_string(trim($_POST['prenom']));
    $email           = $connexion->real_escape_string(trim($_POST['email']));
    $specialite      = $connexion->real_escape_string(trim($_POST['specialite']));
    $date_inscription = $connexion->real_escape_string($_POST['date_inscription']);

    // 2. Validation côté serveur
    $errors = [];

    if(empty($nom))      $errors[] = "Le nom est obligatoire.";
    if(empty($prenom))   $errors[] = "Le prénom est obligatoire.";
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

    // 3. Vérifier si l'email existe déjà
    $check = $connexion->query("SELECT id_chercheur FROM chercheurs WHERE email='$email'");
    if($check->num_rows > 0){
        $errors[] = "Cet email est déjà utilisé.";
    }

    if(!empty($errors)){
        foreach($errors as $err){
            echo "<p style='color:red'>$err</p>";
        }
        echo "<a href='chercheurs.php'>Retour</a>";
        exit();
    }

    // 4. Insertion en POO
    $sql = "INSERT INTO chercheurs (nom, prenom, email, specialite, date_inscription)
            VALUES ('$nom', '$prenom', '$email', '$specialite', '$date_inscription')";

    if($connexion->query($sql)){
        header("Location: chercheurs.php?status=success");
        exit();
    } else {
        echo "Erreur SQL : " . $connexion->error;
    }
}
?>