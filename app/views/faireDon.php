
<?php require __DIR__ . '/layouts/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faire un don</title>

  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5" style="max-width: 980px;">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-semibold">Faire un don</h2>
    <a href="/accueil" class="btn btn-outline-secondary btn-sm">Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger mb-4">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="/don" class="vstack gap-4">

    <div class="card shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Don d'argent</span>
      </div>
      <div class="card-body">
        <label class="form-label">Montant (Ar)</label>
        <div class="input-group">
          <span class="input-group-text">Ar</span>
          <input
            type="number"
            name="montant"
            min="0"
            step="0.01"
            class="form-control"
          >
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-semibold">Objets nature</span>
        </div>
        <button type="button" class="btn btn-sm btn-primary" id="btnAddNature">
          Ajouter une ligne
        </button>
      </div>

      <div class="card-body">

        <div id="nature-lines" class="vstack gap-2">
          <div class="row g-2 align-items-end nature-line">
            <div class="col-12 col-md-7">
              <label class="form-label">Objet</label>
              <select name="nature_id[]" class="form-select">
                <option value="">-- choisir --</option>
                <?php foreach ($natures as $o): ?>
                  <option value="<?= (int)$o['id'] ?>"><?= htmlspecialchars($o['nom']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12 col-md-3">
              <label class="form-label">Quantité</label>
              <input type="number" name="nature_qte[]" min="0" class="form-control">
            </div>

            <div class="col-12 col-md-2">
              <button type="button" class="btn btn-outline-danger w-100 btnRemoveLine">
                Supprimer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-semibold">Objets matériaux</span>
        </div>
        <button type="button" class="btn btn-sm btn-primary" id="btnAddMat">
          Ajouter une ligne
        </button>
      </div>

      <div class="card-body">

        <div id="mat-lines" class="vstack gap-2">
          <div class="row g-2 align-items-end mat-line">
            <div class="col-12 col-md-7">
              <label class="form-label">Objet</label>
              <select name="mat_id[]" class="form-select">
                <option value="">-- choisir --</option>
                <?php foreach ($mats as $o): ?>
                  <option value="<?= (int)$o['id'] ?>"><?= htmlspecialchars($o['nom']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12 col-md-3">
              <label class="form-label">Quantité</label>
              <input type="number" name="mat_qte[]" min="0" class="form-control">
            </div>

            <div class="col-12 col-md-2">
              <button type="button" class="btn btn-outline-danger w-100 btnRemoveLine">
                Supprimer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex gap-2 justify-content-end">
      <a href="/accueil" class="btn btn-outline-secondary">Annuler</a>
      <button type="submit" class="btn btn-success">Valider le don</button>
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

  function makeRemoveWork(container) {
    container.querySelectorAll('.btnRemoveLine').forEach(btn => {
      btn.onclick = () => {
        const line = btn.closest('.nature-line, .mat-line');
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

  document.getElementById('btnAddNature').addEventListener('click', () => {
    const first = natureContainer.querySelector('.nature-line');
    const clone = first.cloneNode(true);
    resetLine(clone);
    natureContainer.appendChild(clone);
    makeRemoveWork(natureContainer);
  });

  document.getElementById('btnAddMat').addEventListener('click', () => {
    const first = matContainer.querySelector('.mat-line');
    const clone = first.cloneNode(true);
    resetLine(clone);
    matContainer.appendChild(clone);
    makeRemoveWork(matContainer);
  });

  makeRemoveWork(natureContainer);
  makeRemoveWork(matContainer);
</script>

</body>
</html>

<?php require __DIR__ . '/layouts/footer.php'; ?>