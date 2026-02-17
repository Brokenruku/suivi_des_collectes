<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Collectes</title>
    <link href="<?= \Flight::get('flight.base_url') ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark align-items-center">
        <div class="container-fluid">
            <a class="navbar-brand" href="/accueil">BNGRC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto"> 
                    <?php if (!empty($_SESSION['user'])): ?>
                        <a class="nav-link" href="/accueil"><i class="bi bi-house-door-fill"></i> Accueil</a>
                        <a href="/admin/localites" class="nav-link">Ajouter région/ville</a>
                        <a href="/besoins" class="nav-link"> <i class="bi bi-plus"></i> Ajouter besoins</a>
                        <a class="nav-link" href="/achats"><i class="bi bi-bag-fill"></i> Achats</a>
                        <a href="/vendre" class="nav-link"> Vendre</a>
                        <a class="nav-link" href="/don"><i class="bi bi-heart-half"></i> Faire un Don</a>
                        <a class="nav-link" href="/recap"><i class="bi bi-graph-up"></i> Récapitulatif</a>

                        <a class="nav-link text-danger fw-semibold" href="/logout" style="transition: 0.2s;"><i class="bi bi-box-arrow-left"></i> Déconnexion</a>

                    <?php else: ?>
                        <a class="nav-link" href="/login">Connexion</a>
                        <a class="nav-link" href="/register">Inscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <main class="py-4">