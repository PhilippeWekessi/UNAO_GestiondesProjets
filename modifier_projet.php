<?php
include 'connexion.php';

$id  = intval($_GET['id']);
$sql = "SELECT * FROM projets WHERE id_projet = $id";
$res = $connexion->query($sql);
$p   = $res->fetch_assoc();

if(!$p){
    header("Location: projets.php");
    exit();
}

// Récupérer les IDs des chercheurs secondaires déjà associés
$secondaires_actuels = [];
$res_part = $connexion->query("SELECT id_chercheur FROM participations WHERE id_projet = $id");
while($part = $res_part->fetch_assoc()){
    $secondaires_actuels[] = $part['id_chercheur'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Projet</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'menu.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm p-4">
                <h3 class="fw-bold mb-4 text-center">Modifier le Projet</h3>

                <form id="formModifier" action="update_projet.php" method="POST" novalidate>

                    <input type="hidden" name="id_projet" value="<?php echo $p['id_projet']; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Titre :</label>
                        <input type="text" name="titre" id="titre" class="form-control"
                               value="<?php echo htmlspecialchars($p['titre']); ?>">
                        <div class="invalid-feedback" id="erreur_titre"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description :</label>
                        <textarea name="description" id="description" class="form-control" 
                                  rows="3"><?php echo htmlspecialchars($p['description']); ?></textarea>
                        <div class="invalid-feedback" id="erreur_description"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date de début :</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control"
                                   value="<?php echo $p['date_debut']; ?>">
                            <div class="invalid-feedback" id="erreur_date_debut"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date de fin prévue :</label>
                            <input type="date" name="date_fin_prevue" id="date_fin_prevue" class="form-control"
                                   value="<?php echo $p['date_fin_prevue']; ?>">
                            <div class="invalid-feedback" id="erreur_date_fin"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Statut :</label>
                            <select name="statut" id="statut" class="form-select">
                                <?php
                                $statuts = ['En cours','Terminé','Annulé','En attente'];
                                foreach($statuts as $s){
                                    $selected = ($p['statut'] == $s) ? 'selected' : '';
                                    echo "<option value='$s' $selected>$s</option>";
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback" id="erreur_statut"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Budget (FCFA) :</label>
                            <input type="number" name="budget" id="budget" class="form-control"
                                   value="<?php echo $p['budget']; ?>">
                            <div class="invalid-feedback" id="erreur_budget"></div>
                        </div>
                    </div>

                    <!-- Chercheur principal -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chercheur principal :</label>
                        <select name="id_chercheur_principal" id="id_chercheur_principal" class="form-select">
                            <?php
                            $res_c = $connexion->query("SELECT * FROM chercheurs ORDER BY nom ASC");
                            while($ch = $res_c->fetch_assoc()){
                                // ✅ selected sur le chercheur actuel du projet
                                $selected = ($ch['id_chercheur'] == $p['id_chercheur_principal']) ? 'selected' : '';
                                echo "<option value='".$ch['id_chercheur']."' $selected>".
                                     htmlspecialchars($ch['nom'])." ".htmlspecialchars($ch['prenom']).
                                     "</option>";
                            }
                            ?>
                        </select>
                        <div class="invalid-feedback" id="erreur_chercheur"></div>
                    </div>

                    <!-- Chercheurs secondaires -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chercheurs secondaires :</label>
                        <div class="border rounded p-3">
                            <?php
                            $res_c2 = $connexion->query("SELECT * FROM chercheurs ORDER BY nom ASC");
                            while($ch2 = $res_c2->fetch_assoc()){
                                // ✅ coché si déjà dans participations
                                $checked = in_array($ch2['id_chercheur'], $secondaires_actuels) ? 'checked' : '';
                                echo "
                                <div class='form-check'>
                                    <input class='form-check-input' type='checkbox'
                                           name='chercheurs_secondaires[]'
                                           value='".$ch2['id_chercheur']."'
                                           id='ch_".$ch2['id_chercheur']."' $checked>
                                    <label class='form-check-label' for='ch_".$ch2['id_chercheur']."'>
                                        ".htmlspecialchars($ch2['nom'])." ".htmlspecialchars($ch2['prenom'])."
                                    </label>
                                </div>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            Enregistrer les modifications
                        </button>
                        <a href="projets.php" class="btn btn-secondary">Annuler</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
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
    } else if(date_fin <= date_debut){
        afficherErreur('date_fin_prevue','erreur_date_fin','La date de fin doit être après la date de début.');
    } else { effacerErreur('date_fin_prevue'); }

    if(budget === '' || parseFloat(budget) <= 0){
        afficherErreur('budget','erreur_budget','Le budget doit être un nombre positif.');
    } else { effacerErreur('budget'); }

    if(chercheur === ''){
        afficherErreur('id_chercheur_principal','erreur_chercheur','Sélectionnez un chercheur principal.');
    } else { effacerErreur('id_chercheur_principal'); }

    if(valide){ this.submit(); }
});
</script>
</body>
</html>