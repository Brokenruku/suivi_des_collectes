<?php require __DIR__ . '/layouts/header.php'; ?>
<?php $base = \Flight::get('flight.base_url'); ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Login</h3>
        </div>

        <form method="POST" action="/login">
          <div class="card-body">
            <div class="form-group mb-3">
              <label for="mail">Email</label>
              <input type="email" class="form-control" id="mail" name="mail" placeholder="Email" required>
            </div>
            <div class="form-group mb-3">
              <label for="mdp">Mot de passe</label>
              <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe" required>
            </div>
            
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
          </div>
          
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="<?= $base ?>register" class="btn btn-secondary">Creer compte</a>
          </div>
        </form>

      </div>

    </div>
  </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>