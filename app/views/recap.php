<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Récapitulatif</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5" style="max-width: 980px;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-semibold">Récapitulatif</h2>
    <div class="d-flex gap-2">
      <a href="/accueil" class="btn btn-outline-secondary btn-sm">Retour</a>
      <button id="btnRefresh" class="btn btn-primary btn-sm" type="button">Actualiser</button>
    </div>
  </div>

  <div id="alertBox"></div>

  <div class="row g-3">

    <div class="col-12 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Besoins</div>
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>Total</span>
            <span class="fw-semibold" id="besoinTotal">-</span>
          </div>
          <div class="d-flex justify-content-between">
            <span>Satisfaits</span>
            <span class="fw-semibold text-success" id="besoinSatisfait">-</span>
          </div>
          <div class="d-flex justify-content-between">
            <span>Restants</span>
            <span class="fw-semibold text-danger" id="besoinReste">-</span>
          </div>

          <hr>

          <div class="small text-muted mb-2">Détails</div>
          <div class="d-flex justify-content-between small">
            <span>Argent</span>
            <span id="besoinArgent">-</span>
          </div>
          <div class="d-flex justify-content-between small">
            <span>Nature (montant)</span>
            <span id="besoinNature">-</span>
          </div>
          <div class="d-flex justify-content-between small">
            <span>Matériaux (montant)</span>
            <span id="besoinMat">-</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Dons</div>
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>Reçus (argent)</span>
            <span class="fw-semibold" id="donsRecus">-</span>
          </div>
          <div class="d-flex justify-content-between">
            <span>Dispatchés (achats)</span>
            <span class="fw-semibold text-primary" id="donsDispatches">-</span>
          </div>
          <div class="d-flex justify-content-between">
            <span>Reste disponible</span>
            <span class="fw-semibold" id="donsReste">-</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
  function fmtAr(n) {
    const x = Number(n || 0);
    return x.toLocaleString('fr-FR') + ' Ar';
  }

  const alertBox = document.getElementById('alertBox');
  function showError(msg) {
    alertBox.innerHTML = '<div class="alert alert-danger mb-3">' + msg + '</div>';
  }
  function clearError() {
    alertBox.innerHTML = '';
  }

  async function loadRecap() {
    clearError();
    try {
      const res = await fetch('/recap/data', { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('Erreur HTTP ' + res.status);
      const data = await res.json();

      document.getElementById('besoinTotal').textContent = fmtAr(data.besoins.total_montant);
      document.getElementById('besoinSatisfait').textContent = fmtAr(data.besoins.satisfait_montant);
      document.getElementById('besoinReste').textContent = fmtAr(data.besoins.reste_montant);

      document.getElementById('besoinArgent').textContent = fmtAr(data.besoins.details.argent);
      document.getElementById('besoinNature').textContent = fmtAr(data.besoins.details.nature);
      document.getElementById('besoinMat').textContent = fmtAr(data.besoins.details.materiaux);

      document.getElementById('donsRecus').textContent = fmtAr(data.dons.recus_montant);
      document.getElementById('donsDispatches').textContent = fmtAr(data.dons.dispatches_montant);
      document.getElementById('donsReste').textContent = fmtAr(data.dons.reste_montant);

    } catch (e) {
      showError(e.message);
    }
  }

  document.getElementById('btnRefresh').addEventListener('click', loadRecap);
  loadRecap();
</script>

</body>
</html>
