<?php include 'connexion.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Projets</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container mt-4">

    <!-- Formulaire d'ajout -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white fw-bold">
            Ajouter un Projet
        </div>
        <div class="card-body">
            <form id="formProjet" action="add_projet.php" method="POST" novalidate>

                <div class="mb-3">
                    <label class="form-label fw-bold">Titre :</label>
                    <input type="text" name="titre" id="titre" class="form-control" 
                           placeholder="Ex: IA pour la santé">
                    <div class="invalid-feedback" id="erreur_titre"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Description :</label>
                    <textarea name="description" id="description" class="form-control" 
                              rows="3" placeholder="Décrivez le projet..."></textarea>
                    <div class="invalid-feedback" id="erreur_description"></div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Date de début :</label>
                        <input type="date" name="date_debut" id="date_debut" class="form-control">
                        <div class="invalid-feedback" id="erreur_date_debut"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Date de fin prévue :</label>
                        <input type="date" name="date_fin_prevue" id="date_fin_prevue" class="form-control">
                        <div class="invalid-feedback" id="erreur_date_fin"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Statut :</label>
                        <select name="statut" id="statut" class="form-select">
                            <option value="">Sélectionner un statut...</option>
                            <option value="En cours">En cours</option>
                            <option value="Terminé">Terminé</option>
                            <option value="Annulé">Annulé</option>
                            <option value="En attente">En attente</option>
                        </select>
                        <div class="invalid-feedback" id="erreur_statut"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Budget (FCFA) :</label>
                        <input type="number" name="budget" id="budget" class="form-control" 
                               placeholder="Ex: 5000000">
                        <div class="invalid-feedback" id="erreur_budget"></div>
                    </div>
                </div>

                <!-- Chercheur principal -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Chercheur principal :</label>
                    <select name="id_chercheur_principal" id="id_chercheur_principal" class="form-select">
                        <option value="">Sélectionner un chercheur principal...</option>
                        <?php
                        $res_c = $connexion->query("SELECT * FROM chercheurs ORDER BY nom ASC");
                        while($ch = $res_c->fetch_assoc()){
                            echo "<option value='".$ch['id_chercheur']."'>".
                                 htmlspecialchars($ch['nom'])." ".
                                 htmlspecialchars($ch['prenom'])."</option>";
                        }
                        ?>
                    </select>
                    <div class="invalid-feedback" id="erreur_chercheur"></div>
                </div>

                <!-- Chercheurs secondaires (cases à cocher) -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Chercheurs secondaires :</label>
                    <div class="border rounded p-3">
                        <?php
                        // On recharge la liste depuis le début
                        $res_c2 = $connexion->query("SELECT * FROM chercheurs ORDER BY nom ASC");
                        while($ch2 = $res_c2->fetch_assoc()){
                            echo "
                            <div class='form-check'>
                                <input class='form-check-input' type='checkbox' 
                                       name='chercheurs_secondaires[]' 
                                       value='".$ch2['id_chercheur']."' 
                                       id='ch_".$ch2['id_chercheur']."'>
                                <label class='form-check-label' for='ch_".$ch2['id_chercheur']."'>
                                    ".htmlspecialchars($ch2['nom'])." ".htmlspecialchars($ch2['prenom'])."
                                </label>
                            </div>";
                        }
                        ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Enregistrer le projet</button>
            </form>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="mb-3">
        <input type="text" id="recherche" class="form-control" 
               placeholder="Rechercher par titre ou chercheur principal...">
    </div>

    <!-- Tableau des projets -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white fw-bold">
            Liste des Projets
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Chercheur principal</th>
                            <th>Statut</th>
                            <th>Budget</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableProjets">
                        <?php
                        // Jointure projets ↔ chercheurs pour avoir le nom du chercheur principal
                        $sql = "SELECT p.*, c.nom, c.prenom 
                                FROM projets p
                                INNER JOIN chercheurs c ON p.id_chercheur_principal = c.id_chercheur
                                ORDER BY p.titre ASC";
                        $res = $connexion->query($sql);

                        if($res && $res->num_rows > 0){
                            while($p = $res->fetch_assoc()){
                                // Badge couleur selon statut
                                $badge = 'secondary';
                                if($p['statut'] == 'En cours')   $badge = 'success';
                                if($p['statut'] == 'Terminé')    $badge = 'primary';
                                if($p['statut'] == 'Annulé')     $badge = 'danger';
                                if($p['statut'] == 'En attente') $badge = 'warning';
                        ?>
                            <tr>
                                <td><?php echo $p['id_projet']; ?></td>
                                <td><?php echo htmlspecialchars($p['titre']); ?></td>
                                <td><?php echo htmlspecialchars($p['nom'].' '.$p['prenom']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $badge; ?>">
                                        <?php echo $p['statut']; ?>
                                    </span>
                                </td>
                                <td><?php echo number_format($p['budget'], 0, ',', ' '); ?> FCFA</td>
                                <td><?php echo date('d/m/Y', strtotime($p['date_debut'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($p['date_fin_prevue'])); ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="modifier_projet.php?id=<?php echo $p['id_projet']; ?>" 
                                           class="btn btn-sm btn-outline-primary">Modifier</a>
                                        <a href="delete_projet.php?id=<?php echo $p['id_projet']; ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Supprimer ce projet ?')">Supprimer</a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center p-4 text-muted'>Aucun projet enregistré.</td></tr>";
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
// VALIDATION JAVASCRIPT PROJETS
// ============================================
document.getElementById('formProjet').addEventListener('submit', function(e){
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

    // Réinitialiser
    ['titre','description','date_debut','date_fin_prevue','statut','budget','id_chercheur_principal'].forEach(function(id){
        document.getElementById(id).classList.remove('is-invalid','is-valid');
    });

    let titre       = document.getElementById('titre').value.trim();
    let description = document.getElementById('description').value.trim();
    let date_debut  = document.getElementById('date_debut').value;
    let date_fin    = document.getElementById('date_fin_prevue').value;
    let statut      = document.getElementById('statut').value;
    let budget      = document.getElementById('budget').value;
    let chercheur   = document.getElementById('id_chercheur_principal').value;
    let aujourdhui  = new Date().toISOString().split('T')[0];

    if(titre === ''){
        afficherErreur('titre','erreur_titre','Le titre est obligatoire.');
    } else { effacerErreur('titre'); }

    if(description === ''){
        afficherErreur('description','erreur_description','La description est obligatoire.');
    } else { effacerErreur('description'); }

    if(date_debut === ''){
        afficherErreur('date_debut','erreur_date_debut','La date de début est obligatoire.');
    } else if(date_debut > aujourdhui){
        afficherErreur('date_debut','erreur_date_debut','La date de début ne peut pas être dans le futur.');
    } else { effacerErreur('date_debut'); }

    if(date_fin === ''){
        afficherErreur('date_fin_prevue','erreur_date_fin','La date de fin est obligatoire.');
    } else if(date_debut !== '' && date_fin <= date_debut){
        afficherErreur('date_fin_prevue','erreur_date_fin','La date de fin doit être après la date de début.');
    } else { effacerErreur('date_fin_prevue'); }

    if(statut === ''){
        afficherErreur('statut','erreur_statut','Le statut est obligatoire.');
    } else { effacerErreur('statut'); }

    if(budget === '' || parseFloat(budget) <= 0){
        afficherErreur('budget','erreur_budget','Le budget doit être un nombre positif.');
    } else { effacerErreur('budget'); }

    if(chercheur === ''){
        afficherErreur('id_chercheur_principal','erreur_chercheur','Sélectionnez un chercheur principal.');
    } else { effacerErreur('id_chercheur_principal'); }

    if(valide){ this.submit(); }
});

// ============================================
// RECHERCHE EN TEMPS RÉEL
// ============================================
document.getElementById('recherche').addEventListener('keyup', function(){
    let recherche = this.value.toLowerCase();
    let lignes    = document.querySelectorAll('#tableProjets tr');
    lignes.forEach(function(ligne){
        ligne.style.display = ligne.textContent.toLowerCase().includes(recherche) ? '' : 'none';
    });
});
</script>
</body>
</html>