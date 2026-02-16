<?php require __DIR__ . '/layouts/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails achat</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 900px;">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-semibold">Détails achat #<?= (int)$achat['id'] ?></h2>
    <a href="/achats" class="btn btn-outline-secondary btn-sm">Retour</a>
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <div><span class="text-muted">Ville :</span> <?= htmlspecialchars($achat['ville']) ?></div>
      <div><span class="text-muted">Total :</span> <?= htmlspecialchars((string)$achat['total_argent']) ?> Ar</div>
      <div><span class="text-muted">Date :</span> <?= htmlspecialchars((string)$achat['created_at']) ?></div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Lignes</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead class="table-light">
          <tr>
            <th>Type</th>
            <th>ID objet</th>
            <th>Qté</th>
            <th>PU</th>
            <th>Montant</th>
          </tr>
          </thead>
          <tbody>
          <?php if (empty($lignes)): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">Aucune ligne</td></tr>
          <?php else: ?>
            <?php foreach ($lignes as $L): ?>
              <tr>
                <td><?= htmlspecialchars($L['type_objet']) ?></td>
                <td><?= (int)$L['id_objet'] ?></td>
                <td><?= (int)$L['qte'] ?></td>
                <td><?= htmlspecialchars((string)$L['pu']) ?></td>
                <td><?= htmlspecialchars((string)$L['montant']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php require __DIR__ . '/layouts/footer.php'; ?>