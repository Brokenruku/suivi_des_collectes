<?php require __DIR__ . '/layouts/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Achats</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 1100px;">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-semibold">Achats</h2>
    <a href="/accueil" class="btn btn-outline-secondary btn-sm">Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger mb-4"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm mb-4">
    <div class="card-body d-flex flex-wrap gap-3 justify-content-between align-items-center">
      <form method="GET" action="/achats" class="d-flex gap-2 align-items-center mb-0">
        <label class="form-label mb-0">Ville</label>
        <select class="form-select" name="ville" style="min-width: 240px;" onchange="this.form.submit()">
          <option value="0">-- choisir --</option>
          <?php foreach ($villes as $v): ?>
            <option value="<?= (int)$v['id'] ?>" <?= ((int)$v['id'] === (int)$villeId) ? 'selected' : '' ?>>
              <?= htmlspecialchars($v['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <noscript><button class="btn btn-primary">Filtrer</button></noscript>
      </form>

      <div class="text-end">
        <div class="text-muted small">Argent restant global</div>
        <div class="fw-semibold"><?= htmlspecialchars((string)$argentRestant) ?> Ar</div>
      </div>
    </div>
  </div>

  <?php if ((int)$villeId <= 0): ?>
    <div class="alert alert-warning">Choisis une ville pour saisir un achat et voir les besoins restants.</div>
  <?php else: ?>

    <form method="POST" action="/achats" class="vstack gap-4">
      <input type="hidden" name="id_ville" value="<?= (int)$villeId ?>">

      <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <span class="fw-semibold">Creer un achat</span>
          <span class="badge text-bg-secondary">Ville selectionnee</span>
        </div>

        <div class="card-body">
          <div class="row g-4">

            <div class="col-12 col-lg-6">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Nature</div>
                <button type="button" class="btn btn-sm btn-primary" id="btnAddNature">Ajouter</button>
              </div>

                

              <div id="nature-lines" class="vstack gap-2">
                <div class="row g-2 align-items-end nature-line">
                  <div class="col-8">
                    <label class="form-label">Objet</label>
                    <select name="nature_id[]" class="form-select">
                      <option value="">-- choisir --</option>
                      <?php foreach ($natureRestants as $o): ?>
                        <?php if ((int)$o['restant'] > 0): ?>
                          <option value="<?= (int)$o['id'] ?>">
                            <?= htmlspecialchars($o['nom']) ?> (reste <?= (int)$o['restant'] ?>, pu <?= htmlspecialchars((string)$o['prix_unitaire']) ?>)
                          </option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-4">
                    <label class="form-label">Qte</label>
                    <input type="number" name="nature_qte[]" min="0" class="form-control" placeholder="0">
                  </div>
                  <div class="col-12">
                    <button type="button" class="btn btn-outline-danger w-100 btnRemoveLine">Supprimer</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-lg-6">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Materiaux</div>
                <button type="button" class="btn btn-sm btn-primary" id="btnAddMat">Ajouter</button>
              </div>

                

              <div id="mat-lines" class="vstack gap-2">
                <div class="row g-2 align-items-end mat-line">
                  <div class="col-8">
                    <label class="form-label">Objet</label>
                    <select name="mat_id[]" class="form-select">
                      <option value="">-- choisir --</option>
                      <?php foreach ($matRestants as $o): ?>
                        <?php if ((int)$o['restant'] > 0): ?>
                          <option value="<?= (int)$o['id'] ?>">
                            <?= htmlspecialchars($o['nom']) ?> (reste <?= (int)$o['restant'] ?>, pu <?= htmlspecialchars((string)$o['prix_unitaire']) ?>)
                          </option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-4">
                    <label class="form-label">Qte</label>
                    <input type="number" name="mat_qte[]" min="0" class="form-control" placeholder="0">
                  </div>
                  <div class="col-12">
                    <button type="button" class="btn btn-outline-danger w-100 btnRemoveLine">Supprimer</button>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="/achats?ville=<?= (int)$villeId ?>" class="btn btn-outline-secondary">Reinitialiser</a>
            <button type="submit" class="btn btn-success">Valider l'achat</button>
          </div>

        </div>
      </div>
    </form>

  <?php endif; ?>

  <div class="card shadow-sm mt-4">
    <div class="card-header bg-white fw-semibold">Liste des achats</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Ville</th>
              <th>Total (Ar)</th>
              <th>Date</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php if (empty($achats)): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">Aucun achat</td></tr>
          <?php else: ?>
            <?php foreach ($achats as $a): ?>
              <tr>
                <td><?= (int)$a['id'] ?></td>
                <td><?= htmlspecialchars($a['ville']) ?></td>
                <td><?= htmlspecialchars((string)$a['total_argent']) ?></td>
                <td><?= htmlspecialchars((string)$a['created_at']) ?></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary" href="/achats/<?= (int)$a['id'] ?>">Details</a>
                </td>
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
<script>
  function resetLine(line) {
    const sel = line.querySelector('select');
    const inp = line.querySelector('input[type="number"]');
    if (sel) sel.value = '';
    if (inp) inp.value = '';
  }

  function bindRemove(container, lineSelector) {
    container.querySelectorAll('.btnRemoveLine').forEach(btn => {
      btn.onclick = () => {
        const line = btn.closest(lineSelector);
        if (!line) return;

        const parent = line.parentElement;
        if (parent.children.length === 1) {
          resetLine(line);
        } else {
          line.remove();
        }
      };
    });
  }

  const natureContainer = document.getElementById('nature-lines');
  const matContainer = document.getElementById('mat-lines');

  if (natureContainer) {
    document.getElementById('btnAddNature').addEventListener('click', () => {
      const first = natureContainer.querySelector('.nature-line');
      const clone = first.cloneNode(true);
      resetLine(clone);
      natureContainer.appendChild(clone);
      bindRemove(natureContainer, '.nature-line');
    });
    bindRemove(natureContainer, '.nature-line');
  }

  if (matContainer) {
    document.getElementById('btnAddMat').addEventListener('click', () => {
      const first = matContainer.querySelector('.mat-line');
      const clone = first.cloneNode(true);
      resetLine(clone);
      matContainer.appendChild(clone);
      bindRemove(matContainer, '.mat-line');
    });
    bindRemove(matContainer, '.mat-line');
  }
</script>
</body>
</html>

<?php require __DIR__ . '/layouts/footer.php'; ?>