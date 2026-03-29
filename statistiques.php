<?php include 'connexion.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'menu.php'; ?>

<div class="container mt-4">
    <h2 class="fw-bold mb-4 text-center">Statistiques & Recherche Avancée</h2>

    <!-- Cartes statistiques (remplies par AJAX) -->
    <div class="row text-center mb-5">
        <div class="col-md-2 mb-3">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h2 class="fw-bold text-primary" id="stat_chercheurs">...</h2>
                    <p class="text-muted mb-0">Chercheurs</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card shadow-sm border-dark">
                <div class="card-body">
                    <h2 class="fw-bold" id="stat_projets">...</h2>
                    <p class="text-muted mb-0">Projets total</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h2 class="fw-bold text-success" id="stat_encours">...</h2>
                    <p class="text-muted mb-0">En cours</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h2 class="fw-bold text-primary" id="stat_termines">...</h2>
                    <p class="text-muted mb-0">Terminés</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card shadow-sm border-danger">
                <div class="card-body">
                    <h2 class="fw-bold text-danger" id="stat_annules">...</h2>
                    <p class="text-muted mb-0">Annulés</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card shadow-sm border-warning">
                <div class="card-body">
                    <h2 class="fw-bold text-warning" id="stat_attente">...</h2>
                    <p class="text-muted mb-0">En attente</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recherche avancée chercheurs -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            Recherche de Chercheurs
        </div>
        <div class="card-body">
            <input type="text" id="searchChercheur" class="form-control mb-3" 
                   placeholder="Rechercher par nom, prénom ou email...">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Spécialité</th>
                            <th>Date inscription</th>
                        </tr>
                    </thead>
                    <tbody id="resultsChercheurs">
                        <tr><td colspan="5" class="text-center text-muted">Tapez pour rechercher...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recherche avancée projets -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white fw-bold">
            Recherche de Projets
        </div>
        <div class="card-body">
            <input type="text" id="searchProjet" class="form-control mb-3" 
                   placeholder="Rechercher par titre ou chercheur principal...">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Titre</th>
                            <th>Chercheur principal</th>
                            <th>Statut</th>
                            <th>Budget</th>
                            <th>Date début</th>
                        </tr>
                    </thead>
                    <tbody id="resultsProjets">
                        <tr><td colspan="5" class="text-center text-muted">Tapez pour rechercher...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
// ============================================
// AJAX — CHARGER LES STATISTIQUES
// ============================================
function chargerStats(){
    fetch('get_stats.php')
        .then(function(response){
            return response.json(); // convertir la réponse en objet JS
        })
        .then(function(data){
            // Remplir les cartes avec les données reçues
            document.getElementById('stat_chercheurs').textContent = data.total_chercheurs;
            document.getElementById('stat_projets').textContent    = data.total_projets;
            document.getElementById('stat_encours').textContent    = data.en_cours;
            document.getElementById('stat_termines').textContent   = data.termines;
            document.getElementById('stat_annules').textContent    = data.annules;
            document.getElementById('stat_attente').textContent    = data.en_attente;
        });
}

// Appeler au chargement de la page
chargerStats();

// ============================================
// AJAX — RECHERCHE CHERCHEURS EN TEMPS RÉEL
// ============================================
document.getElementById('searchChercheur').addEventListener('keyup', function(){
    let mot = this.value;

    fetch('search_chercheurs.php?q=' + encodeURIComponent(mot))
        .then(function(response){ return response.json(); })
        .then(function(data){
            let tbody = document.getElementById('resultsChercheurs');
            tbody.innerHTML = ''; // vider le tableau

            if(data.length === 0){
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucun résultat.</td></tr>';
                return;
            }

            // Construire les lignes du tableau
            data.forEach(function(c){
                let ligne = '<tr>' +
                    '<td>' + c.nom + '</td>' +
                    '<td>' + c.prenom + '</td>' +
                    '<td>' + c.email + '</td>' +
                    '<td>' + c.specialite + '</td>' +
                    '<td>' + c.date_inscription + '</td>' +
                '</tr>';
                tbody.innerHTML += ligne;
            });
        });
});

// ============================================
// AJAX — RECHERCHE PROJETS EN TEMPS RÉEL
// ============================================
document.getElementById('searchProjet').addEventListener('keyup', function(){
    let mot = this.value;

    fetch('search_projets.php?q=' + encodeURIComponent(mot))
        .then(function(response){ return response.json(); })
        .then(function(data){
            let tbody = document.getElementById('resultsProjets');
            tbody.innerHTML = '';

            if(data.length === 0){
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucun résultat.</td></tr>';
                return;
            }

            data.forEach(function(p){
                // Badge couleur selon statut
                let couleur = 'secondary';
                if(p.statut == 'En cours')   couleur = 'success';
                if(p.statut == 'Terminé')    couleur = 'primary';
                if(p.statut == 'Annulé')     couleur = 'danger';
                if(p.statut == 'En attente') couleur = 'warning';

                let ligne = '<tr>' +
                    '<td>' + p.titre + '</td>' +
                    '<td>' + p.nom + ' ' + p.prenom + '</td>' +
                    '<td><span class="badge bg-' + couleur + '">' + p.statut + '</span></td>' +
                    '<td>' + parseInt(p.budget).toLocaleString() + ' FCFA</td>' +
                    '<td>' + p.date_debut + '</td>' +
                '</tr>';
                tbody.innerHTML += ligne;
            });
        });
});
</script>
</body>
</html>
