<?php include 'connexion.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNAO — Gestion des Projets</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container mt-5">

    <!-- Message de bienvenue -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">Bienvenue sur UNAO Projets</h1>
        <p class="lead text-muted">
            Système de Gestion Avancée des Projets de Recherche Universitaire
        </p>
        <hr>
    </div>

    <!-- Statistiques rapides -->
    <?php
    $nb_chercheurs = $connexion->query("SELECT COUNT(*) as total FROM chercheurs")->fetch_assoc()['total'];
    $nb_projets    = $connexion->query("SELECT COUNT(*) as total FROM projets")->fetch_assoc()['total'];
    $nb_encours    = $connexion->query("SELECT COUNT(*) as total FROM projets WHERE statut='En cours'")->fetch_assoc()['total'];
    $nb_termines   = $connexion->query("SELECT COUNT(*) as total FROM projets WHERE statut='Terminé'")->fetch_assoc()['total'];
    ?>

    <div class="row text-center mb-5">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h2 class="fw-bold text-primary"><?php echo $nb_chercheurs; ?></h2>
                    <p class="text-muted mb-0">Chercheurs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h2 class="fw-bold text-success"><?php echo $nb_projets; ?></h2>
                    <p class="text-muted mb-0">Projets total</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-warning">
                <div class="card-body">
                    <h2 class="fw-bold text-warning"><?php echo $nb_encours; ?></h2>
                    <p class="text-muted mb-0">En cours</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-secondary">
                <div class="card-body">
                    <h2 class="fw-bold text-secondary"><?php echo $nb_termines; ?></h2>
                    <p class="text-muted mb-0">Terminés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">À propos de cette application</h4>
                    <p class="text-muted">
                        Cette application permet aux administrateurs de l'Université Numérique 
                        de l'Afrique de l'Ouest (UNAO) de superviser les projets de recherche, 
                        aux chercheurs de soumettre leurs travaux, et aux évaluateurs d'accéder 
                        aux informations pertinentes.
                    </p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="chercheurs.php" class="btn btn-primary">Gérer les chercheurs</a>
                        <a href="projets.php" class="btn btn-success">Gérer les projets</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>