<?php include 'connexion.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Chercheurs</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container mt-4">

    <!-- Formulaire d'ajout -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            Ajouter un Chercheur
        </div>
        <div class="card-body">
            <form id="formChercheur" action="add_chercheur.php" method="POST" novalidate>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nom :</label>
                        <input type="text" name="nom" id="nom" class="form-control" placeholder="Ex: Akobi">
                        <div class="invalid-feedback" id="erreur_nom"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Prénom :</label>
                        <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Ex: Messan">
                        <div class="invalid-feedback" id="erreur_prenom"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email :</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Ex: messan@unao.bj">
                        <div class="invalid-feedback" id="erreur_email"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Spécialité :</label>
                        <input type="text" name="specialite" id="specialite" class="form-control" placeholder="Ex: Intelligence Artificielle">
                        <div class="invalid-feedback" id="erreur_specialite"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Date d'inscription :</label>
                        <input type="date" name="date_inscription" id="date_inscription" class="form-control">
                        <div class="invalid-feedback" id="erreur_date"></div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="mb-3">
        <input type="text" id="recherche" class="form-control" placeholder="Rechercher par nom, prénom ou email...">
    </div>

    <!-- Tableau des chercheurs -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white fw-bold">
            Liste des Chercheurs
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Spécialité</th>
                            <th>Date inscription</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableChercheurs">
                        <?php
                        $sql = "SELECT * FROM chercheurs ORDER BY nom ASC";
                        $res = $connexion->query($sql);

                        if($res && $res->num_rows > 0){
                            while($c = $res->fetch_assoc()){
                        ?>
                            <tr>
                                <td><?php echo $c['id_chercheur']; ?></td>
                                <td><?php echo htmlspecialchars($c['nom']); ?></td>
                                <td><?php echo htmlspecialchars($c['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($c['email']); ?></td>
                                <td><?php echo htmlspecialchars($c['specialite']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($c['date_inscription'])); ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="modifier_chercheur.php?id=<?php echo $c['id_chercheur']; ?>" 
                                           class="btn btn-sm btn-outline-primary">Modifier</a>
                                        <a href="delete_chercheur.php?id=<?php echo $c['id_chercheur']; ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Supprimer ce chercheur ?')">Supprimer</a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center p-4 text-muted'>Aucun chercheur enregistré.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
// ============================================
// VALIDATION JAVASCRIPT (Partie 3 du sujet)
// ============================================
document.getElementById('formChercheur').addEventListener('submit', function(e){
    e.preventDefault(); // bloquer l'envoi du formulaire
    
    let valide = true;

    // -- Fonction utilitaire pour afficher/effacer les erreurs --
    function afficherErreur(idChamp, idErreur, message){
        document.getElementById(idChamp).classList.add('is-invalid');
        document.getElementById(idErreur).textContent = message;
        valide = false;
    }
    function effacerErreur(idChamp){
        document.getElementById(idChamp).classList.remove('is-invalid');
        document.getElementById(idChamp).classList.add('is-valid');
    }

    // -- Récupérer les valeurs --
    let nom            = document.getElementById('nom').value.trim();
    let prenom         = document.getElementById('prenom').value.trim();
    let email          = document.getElementById('email').value.trim();
    let specialite     = document.getElementById('specialite').value.trim();
    let date           = document.getElementById('date_inscription').value;
    let aujourdhui     = new Date().toISOString().split('T')[0]; // date du jour

    // -- Réinitialiser les classes --
    ['nom','prenom','email','specialite','date_inscription'].forEach(function(id){
        document.getElementById(id).classList.remove('is-invalid','is-valid');
    });

    // -- Vérifications --
    if(nom === ''){
        afficherErreur('nom', 'erreur_nom', 'Le nom est obligatoire.');
    } else { effacerErreur('nom'); }

    if(prenom === ''){
        afficherErreur('prenom', 'erreur_prenom', 'Le prénom est obligatoire.');
    } else { effacerErreur('prenom'); }

    // Vérification email avec regex
    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(email === ''){
        afficherErreur('email', 'erreur_email', 'L\'email est obligatoire.');
    } else if(!regexEmail.test(email)){
        afficherErreur('email', 'erreur_email', 'Format email invalide.');
    } else { effacerErreur('email'); }

    if(specialite === ''){
        afficherErreur('specialite', 'erreur_specialite', 'La spécialité est obligatoire.');
    } else { effacerErreur('specialite'); }

    // Date ne peut pas être future
    if(date === ''){
        afficherErreur('date_inscription', 'erreur_date', 'La date est obligatoire.');
    } else if(date > aujourdhui){
        afficherErreur('date_inscription', 'erreur_date', 'La date ne peut pas être dans le futur.');
    } else { effacerErreur('date_inscription'); }

    // -- Si tout est valide, envoyer le formulaire --
    if(valide){
        this.submit();
    }
});

// ============================================
// RECHERCHE EN TEMPS RÉEL (Partie 5 du sujet)
// ============================================
document.getElementById('recherche').addEventListener('keyup', function(){
    let recherche = this.value.toLowerCase();
    let lignes    = document.querySelectorAll('#tableChercheurs tr');

    lignes.forEach(function(ligne){
        let texte = ligne.textContent.toLowerCase();
        if(texte.includes(recherche)){
            ligne.style.display = '';
        } else {
            ligne.style.display = 'none';
        }
    });
});
</script>

</body>
</html>