<?php require __DIR__ . '/layouts/header.php'; ?>
<?php $base = \Flight::get('flight.base_url'); ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Register</h3>
        </div>

        <form method="post" action="<?= $base ?>register">
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrer votre nom" required>
                </div>
                <div class="form-group mb-3">
                    <label for="mail">Email</label>
                    <input type="email" class="form-control" id="mail" name="mail" placeholder="Entrer votre email" required>
                </div>
                <div class="form-group mb-3">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Entrer votre mot de passe" required>
                </div>
                <div class="form-group mb-3">
                    <label for="numero">Numero de telephone</label>
                    <input type="text" class="form-control" id="numero" name="numero" placeholder="Entrer votre numero">
                </div>
                <?php if (!empty($error)): ?>
                  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Register</button>
                <a href="<?= $base ?>login" class="btn btn-secondary">Login</a>
            </div>
        </form>

      </div>

    </div>
  </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>
