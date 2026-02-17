<?php require __DIR__ . '/layouts/header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendre</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 1100px;">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-semibold">Vendre des objets</h2>
    <a href="/accueil" class="btn btn-outline-secondary btn-sm">Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <div class="alert alert-info">
    Reduction: <strong><?= htmlspecialchars((string)$reduction) ?>%</strong>
  </div>

  <form method="POST" action="/vendre" class="vstack gap-4">

    <div class="row g-4">

      <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Nature</span>
            <button type="button" class="btn btn-sm btn-primary" id="btnAddNature">Ajouter</button>
          </div>
          <div class="card-body">
            <div id="nature-lines" class="vstack gap-2">
              <div class="row g-2 align-items-end nature-line">
                <div class="col-8">
                  <label class="form-label">Objet</label>
                  <select name="nature_id[]" class="form-select">
                    <option value="">-- choisir --</option>
                    <?php foreach ($natures as $o): ?>
                      <option value="<?= (int)$o['id'] ?>">
                        <?= htmlspecialchars($o['nom']) ?> (PU <?= htmlspecialchars((string)$o['prix_unitaire']) ?>)
                      </option>
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
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Materiaux</span>
            <button type="button" class="btn btn-sm btn-primary" id="btnAddMat">Ajouter</button>
          </div>
          <div class="card-body">
            <div id="mat-lines" class="vstack gap-2">
              <div class="row g-2 align-items-end mat-line">
                <div class="col-8">
                  <label class="form-label">Objet</label>
                  <select name="mat_id[]" class="form-select">
                    <option value="">-- choisir --</option>
                    <?php foreach ($mats as $o): ?>
                      <option value="<?= (int)$o['id'] ?>">
                        <?= htmlspecialchars($o['nom']) ?> (PU <?= htmlspecialchars((string)$o['prix_unitaire']) ?>)
                      </option>
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
      </div>

    </div>

    <div class="d-flex justify-content-end gap-2">
      <a href="/accueil" class="btn btn-outline-secondary">Annuler</a>
      <button type="submit" class="btn btn-success">Vendre</button>
    </div>

  </form>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
  function resetLine(line) {
    const sel = line.querySelector('select');
    const inp = line.querySelector('input[type="number"]');
    if (sel) sel.value = '';
    if (inp) inp.value = '';
  }
  function bindRemove(container, selector) {
    container.querySelectorAll('.btnRemoveLine').forEach(btn => {
      btn.onclick = () => {
        const line = btn.closest(selector);
        const parent = line.parentElement;
        if (parent.children.length === 1) resetLine(line);
        else line.remove();
      };
    });
  }

  const natureContainer = document.getElementById('nature-lines');
  const matContainer = document.getElementById('mat-lines');

  document.getElementById('btnAddNature').addEventListener('click', () => {
    const first = natureContainer.querySelector('.nature-line');
    const clone = first.cloneNode(true);
    resetLine(clone);
    natureContainer.appendChild(clone);
    bindRemove(natureContainer, '.nature-line');
  });

  document.getElementById('btnAddMat').addEventListener('click', () => {
    const first = matContainer.querySelector('.mat-line');
    const clone = first.cloneNode(true);
    resetLine(clone);
    matContainer.appendChild(clone);
    bindRemove(matContainer, '.mat-line');
  });

  bindRemove(natureContainer, '.nature-line');
  bindRemove(matContainer, '.mat-line');
</script>
</body>

<?php require __DIR__ . '/layouts/footer.php'; ?>
</html>
