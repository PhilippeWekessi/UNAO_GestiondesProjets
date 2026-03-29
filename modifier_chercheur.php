<?php
include 'connexion.php';

$id  = intval($_GET['id']);
$sql = "SELECT * FROM chercheurs WHERE id_chercheur = $id";
$res = $connexion->query($sql);
$c   = $res->fetch_assoc();

if(!$c){
    header("Location: chercheurs.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Chercheur</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'menu.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm p-4">
                <h3 class="fw-bold mb-4 text-center">Modifier le Chercheur</h3>

                <form id="formModifier" action="update_chercheur.php" method="POST" novalidate>

                    <!-- ID caché -->
                    <input type="hidden" name="id_chercheur" value="<?php echo $c['id_chercheur']; ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nom :</label>
                            <input type="text" name="nom" id="nom" class="form-control"
                                   value="<?php echo htmlspecialchars($c['nom']); ?>">
                            <div class="invalid-feedback" id="erreur_nom"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Prénom :</label>
                            <input type="text" name="prenom" id="prenom" class="form-control"
                                   value="<?php echo htmlspecialchars($c['prenom']); ?>">
                            <div class="invalid-feedback" id="erreur_prenom"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email :</label>
                            <input type="text" name="email" id="email" class="form-control"
                                   value="<?php echo htmlspecialchars($c['email']); ?>">
                            <div class="invalid-feedback" id="erreur_email"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Spécialité :</label>
                            <input type="text" name="specialite" id="specialite" class="form-control"
                                   value="<?php echo htmlspecialchars($c['specialite']); ?>">
                            <div class="invalid-feedback" id="erreur_specialite"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Date d'inscription :</label>
                        <input type="date" name="date_inscription" id="date_inscription" class="form-control"
                               value="<?php echo $c['date_inscription']; ?>">
                        <div class="invalid-feedback" id="erreur_date"></div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            Enregistrer les modifications
                        </button>
                        <a href="chercheurs.php" class="btn btn-secondary">Annuler</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
// Même validation que dans chercheurs.php
document.getElementById('formModifier').addEventListener('submit', function(e){
    e.preventDefault();
    let valide = true;

    function afficherErreur(idChamp, idErreur, message){
        document.getElementById(idChamp).classList.add('is-invalid');
        document.getElementById(idErreur).textContent = message;
        valide = false;
    }
    function effacerErreur(idChamp){
        document.getElementById(idChamp).classList.remove('is-invalid');
        document.getElementById(idChamp).classList.add('is-valid');
    }

    let nom        = document.getElementById('nom').value.trim();
    let prenom     = document.getElementById('prenom').value.trim();
    let email      = document.getElementById('email').value.trim();
    let specialite = document.getElementById('specialite').value.trim();
    let date       = document.getElementById('date_inscription').value;
    let aujourdhui = new Date().toISOString().split('T')[0];

    ['nom','prenom','email','specialite','date_inscription'].forEach(function(id){
        document.getElementById(id).classList.remove('is-invalid','is-valid');
    });

    if(nom === ''){
        afficherErreur('nom','erreur_nom','Le nom est obligatoire.');
    } else { effacerErreur('nom'); }

    if(prenom === ''){
        afficherErreur('prenom','erreur_prenom','Le prénom est obligatoire.');
    } else { effacerErreur('prenom'); }

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(email === ''){
        afficherErreur('email','erreur_email','L\'email est obligatoire.');
    } else if(!regexEmail.test(email)){
        afficherErreur('email','erreur_email','Format email invalide.');
    } else { effacerErreur('email'); }

    if(specialite === ''){
        afficherErreur('specialite','erreur_specialite','La spécialité est obligatoire.');
    } else { effacerErreur('specialite'); }

    if(date === ''){
        afficherErreur('date_inscription','erreur_date','La date est obligatoire.');
    } else if(date > aujourdhui){
        afficherErreur('date_inscription','erreur_date','La date ne peut pas être dans le futur.');
    } else { effacerErreur('date_inscription'); }

    if(valide){ this.submit(); }
});
</script>
</body>
</html>