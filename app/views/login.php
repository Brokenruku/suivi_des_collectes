
<?php $base = \Flight::get('flight.base_url'); ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Login</h3>
        </div>

        <form method="post" action="<?= $base ?>/accueil">
          <div class="card-body">
            <div class="form-group">
              <label for="email">Email</label>
              <input type=email class="form-control" id="email" name="email" placeholder="Entrer email">
            </div>
            
            <div class="form-group">
              <label for="mdp">Password</label>
              <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Entrer mot de passe">
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block">
              Login
            </button>
          </div>
        </form>

      </div>

    </div>
  </div>
</div>

