<?php require __DIR__ . '/layouts/header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter région / ville</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5" style="max-width: 980px;">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-semibold">Ajouter une région / une ville</h2>
    <a href="/accueil" class="btn btn-outline-secondary btn-sm">Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="POST" action="/admin/localites" class="vstack gap-4">

    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold"><i class="bi bi-compass-fill"></i> Créer une région</div>
      <div class="card-body">
        <label class="form-label">Nom de la région</label>
        <input type="text" name="region_nom" class="form-control">
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold"><i class="bi bi-geo-alt-fill"></i> Créer une ville</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <label class="form-label">Nom de la ville</label>
            <input type="text" name="ville_nom" class="form-control">
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label">Région de la ville</label>
            <select name="region_id" class="form-select">
              <option value="0">-- choisir --</option>
              <?php foreach ($regions as $r): ?>
                <option value="<?= (int)$r['id'] ?>"><?= htmlspecialchars($r['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
      <a href="/accueil" class="btn btn-outline-secondary">Annuler</a>
      <button class="btn btn-primary" type="submit">Enregistrer</button>
    </div>

  </form>

</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

<?php require __DIR__ . '/layouts/footer.php'; ?>

</html>
