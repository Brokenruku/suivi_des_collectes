<?php require __DIR__ . '/layouts/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recapitulatif</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5" style="max-width: 980px;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-semibold">Recapitulatif</h2>
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

          <div class="small text-muted mb-2">Details</div>
          <div class="d-flex justify-content-between small">
            <span>Argent</span>
            <span id="besoinArgent">-</span>
          </div>
          <div class="d-flex justify-content-between small">
            <span>Nature (montant / qte)</span>
            <span id="besoinNature">-</span>
          </div>
          <div class="d-flex justify-content-between small">
            <span>Materiaux (montant / qte)</span>
            <span id="besoinMat">-</span>
          </div>

          <hr>

          <div class="small text-muted mb-2">Quantites globales</div>
          <div class="d-flex justify-content-between small">
            <span>Nature: demande</span>
            <span id="besoinNatureQte">-</span>
          </div>
          <div class="d-flex justify-content-between small">
            <span>Nature: satisfait</span>
            <span id="besoinNatureSatisfaitQte">-</span>
          </div>
          <div class="d-flex justify-content-between small">
            <span>Materiaux: demande</span>
            <span id="besoinMatQte">-</span>
          </div>
          <div class="d-flex justify-content-between small">
            <span>Materiaux: satisfait</span>
            <span id="besoinMatSatisfaitQte">-</span>
          </div>

          <hr>

          <div class="small text-muted mb-2">Details par objet (Nature)</div>
          <div class="table-responsive mb-2">
            <table class="table table-sm table-striped">
              <thead><tr><th>Objet</th><th>PU</th><th>Demande</th><th>Satisfait</th><th>Reste</th><th>Montant demande</th></tr></thead>
              <tbody id="natureTableBody"></tbody>
            </table>
          </div>

          <div class="small text-muted mb-2">Details par objet (Materiaux)</div>
          <div class="table-responsive">
            <table class="table table-sm table-striped">
              <thead><tr><th>Objet</th><th>PU</th><th>Demande</th><th>Satisfait</th><th>Reste</th><th>Montant demande</th></tr></thead>
              <tbody id="matTableBody"></tbody>
            </table>
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
            <span>Dispatches (achats)</span>
            <span class="fw-semibold text-primary" id="donsDispatches">-</span>
          </div>
          <div class="d-flex justify-content-between">
            <span>Reste disponible</span>
            <span class="fw-semibold" id="donsReste">-</span>
          </div>
          <hr>
          <div class="small text-muted mb-2">Dons - argent (recent)</div>
          <div class="table-responsive mb-2">
            <table class="table table-sm table-striped">
              <thead><tr><th>Id</th><th>Montant</th><th>Date</th><th>Donateur</th></tr></thead>
              <tbody id="donsArgentBody"></tbody>
            </table>
          </div>

          <div class="small text-muted mb-2">Dons - nature (par objet)</div>
          <div class="table-responsive mb-2">
            <table class="table table-sm table-striped">
              <thead><tr><th>Objet</th><th>PU</th><th>Qte donnee</th><th>Montant equivalent</th></tr></thead>
              <tbody id="donsNatureBody"></tbody>
            </table>
          </div>

          <div class="small text-muted mb-2">Dons - materiaux (par objet)</div>
          <div class="table-responsive">
            <table class="table table-sm table-striped">
              <thead><tr><th>Objet</th><th>PU</th><th>Qte donnee</th><th>Montant equivalent</th></tr></thead>
              <tbody id="donsMatBody"></tbody>
            </table>
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

  function fmtNum(n) {
    const x = Number(n || 0);
    return x.toLocaleString('fr-FR');
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
      // show montant / qte
      const natMont = data.besoins.details.nature || 0;
      const matMont = data.besoins.details.materiaux || 0;
      const natQte = (data.besoins.details.qte && data.besoins.details.qte.nature) || 0;
      const matQte = (data.besoins.details.qte && data.besoins.details.qte.materiaux) || 0;
      document.getElementById('besoinNature').textContent = fmtAr(natMont) + ' / ' + fmtNum(natQte) + ' p.';
      document.getElementById('besoinMat').textContent = fmtAr(matMont) + ' / ' + fmtNum(matQte) + ' p.';

      // global qtes
      document.getElementById('besoinNatureQte').textContent = fmtNum(natQte);
      document.getElementById('besoinNatureSatisfaitQte').textContent = fmtNum((data.besoins.details.qte && data.besoins.details.qte.satisfait_nature) || 0);
      document.getElementById('besoinMatQte').textContent = fmtNum(matQte);
      document.getElementById('besoinMatSatisfaitQte').textContent = fmtNum((data.besoins.details.qte && data.besoins.details.qte.satisfait_materiaux) || 0);

      // populate per-object tables
      const natBody = document.getElementById('natureTableBody');
      const matBody = document.getElementById('matTableBody');
      natBody.innerHTML = '';
      matBody.innerHTML = '';

      const nd = data.besoins.details.nature_details || [];
      nd.forEach(function(r){
        const tr = document.createElement('tr');
        tr.innerHTML = '<td>' + (r.nom || '') + '</td>'
          + '<td class="text-end">' + fmtAr(r.pu) + '</td>'
          + '<td class="text-end">' + fmtNum(r.qte_besoin) + '</td>'
          + '<td class="text-end text-success">' + fmtNum(r.qte_satisfait) + '</td>'
          + '<td class="text-end text-danger">' + fmtNum(r.qte_reste) + '</td>'
          + '<td class="text-end">' + fmtAr(r.montant_besoin) + '</td>';
        natBody.appendChild(tr);
      });

      const md = data.besoins.details.materiaux_details || [];
      md.forEach(function(r){
        const tr = document.createElement('tr');
        tr.innerHTML = '<td>' + (r.nom || '') + '</td>'
          + '<td class="text-end">' + fmtAr(r.pu) + '</td>'
          + '<td class="text-end">' + fmtNum(r.qte_besoin) + '</td>'
          + '<td class="text-end text-success">' + fmtNum(r.qte_satisfait) + '</td>'
          + '<td class="text-end text-danger">' + fmtNum(r.qte_reste) + '</td>'
          + '<td class="text-end">' + fmtAr(r.montant_besoin) + '</td>';
        matBody.appendChild(tr);
      });

      // populate dons sections
      const donsArgentBody = document.getElementById('donsArgentBody');
      const donsNatureBody = document.getElementById('donsNatureBody');
      const donsMatBody = document.getElementById('donsMatBody');
      donsArgentBody.innerHTML = '';
      donsNatureBody.innerHTML = '';
      donsMatBody.innerHTML = '';

      (data.dons.argent_entries || []).forEach(function(e){
        const tr = document.createElement('tr');
        tr.innerHTML = '<td>' + e.id + '</td>'
          + '<td class="text-end">' + fmtAr(e.vola) + '</td>'
          + '<td class="text-nowrap">' + (e.date || '') + '</td>'
          + '<td>' + (e.user || '-') + '</td>';
        donsArgentBody.appendChild(tr);
      });

      (data.dons.nature_don_details || []).forEach(function(r){
        const tr = document.createElement('tr');
        tr.innerHTML = '<td>' + (r.nom || '') + '</td>'
          + '<td class="text-end">' + fmtAr(r.pu) + '</td>'
          + '<td class="text-end">' + fmtNum(r.qte_don) + '</td>'
          + '<td class="text-end">' + fmtAr(r.montant_don) + '</td>';
        donsNatureBody.appendChild(tr);
      });

      (data.dons.materiaux_don_details || []).forEach(function(r){
        const tr = document.createElement('tr');
        tr.innerHTML = '<td>' + (r.nom || '') + '</td>'
          + '<td class="text-end">' + fmtAr(r.pu) + '</td>'
          + '<td class="text-end">' + fmtNum(r.qte_don) + '</td>'
          + '<td class="text-end">' + fmtAr(r.montant_don) + '</td>';
        donsMatBody.appendChild(tr);
      });

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

<?php require __DIR__ . '/layouts/footer.php'; ?>