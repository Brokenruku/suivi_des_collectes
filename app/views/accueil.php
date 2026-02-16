<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accueil</title>

  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/accueil">Suivi des collectes</a>

    <div class="ms-auto">
      <a class="btn btn-outline-light btn-sm" href="/logout">Déconnexion</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Besoins par ville</h1>
    <a class="btn btn-primary btn-sm" href="/dons">Faire un don</a>
  </div>

  <div class="row g-3">
    <?php foreach (($villes ?? []) as $v): ?>
      <?php
        // Si tu utilises GROUP_CONCAT avec '||'
        $nature = !empty($v['besoin_nature']) ? explode('||', $v['besoin_nature']) : [];
        $mat    = !empty($v['besoin_materiaux']) ? explode('||', $v['besoin_materiaux']) : [];
        $argent = $v['besoin_argent'] ?? null;
      ?>

      <div class="col-12 col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title mb-1"><?= htmlspecialchars($v['ville'] ?? '') ?></h5>
            <p class="text-muted mb-3"><?= htmlspecialchars($v['region'] ?? '') ?></p>

            <div class="mb-3">
              <div class="fw-semibold">Besoins nature</div>
              <?php if (!$nature): ?>
                <div class="text-muted small">Aucun</div>
              <?php else: ?>
                <div class="d-flex flex-wrap gap-1 mt-1">
                  <?php foreach ($nature as $n): ?>
                    <span class="badge text-bg-success"><?= htmlspecialchars($n) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <div class="fw-semibold">Besoins matériaux</div>
              <?php if (!$mat): ?>
                <div class="text-muted small">Aucun</div>
              <?php else: ?>
                <div class="d-flex flex-wrap gap-1 mt-1">
                  <?php foreach ($mat as $m): ?>
                    <span class="badge text-bg-warning"><?= htmlspecialchars($m) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>

            <div>
              <div class="fw-semibold">Besoin argent</div>
              <div class="<?= $argent ? 'text-danger fw-bold' : 'text-muted' ?>">
                <?= $argent ? number_format((float)$argent, 0, ',', ' ') . ' Ar' : 'Aucun' ?>
              </div>
            </div>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
