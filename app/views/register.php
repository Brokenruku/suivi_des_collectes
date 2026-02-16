<?php require __DIR__ . '/layouts/header.php'; ?>
<?php $base = \Flight::get('flight.base_url'); ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Login</h3>
        </div>

        <form method="post" action="<?= $base ?>login">
            <div class="card-body">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type=text class="form-control" id="nom" name="nom" placeholder="Entrer nom">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type=email class="form-control" id="email" name="email" placeholder="Entrer email">
                </div>
        </form>

      </div>

    </div>
  </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>
