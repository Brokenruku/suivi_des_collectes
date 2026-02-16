<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Collectes</title>
    <link href="<?= \Flight::get('flight.base_url') ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/accueil">Suivi des Collectes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <?php if (!empty($_SESSION['user'])): ?>
                        <a class="nav-link" href="/accueil">Accueil</a>
                        <a class="nav-link" href="/achats">Achats</a>
                        <a class="nav-link" href="/don">Faire un Don</a>
                        <a class="nav-link" href="/recap">Récapitulatif</a>
                        <div class="nav-divider mx-2"></div>


                        <a class="nav-link text-danger fw-semibold" href="/logout" style="transition: 0.2s;">
                            Déconnexion
                        </a>

                    <?php else: ?>
                        <a class="nav-link" href="/login">Connexion</a>
                        <a class="nav-link" href="/register">Inscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <main class="py-4">