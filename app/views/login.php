<?php $base = \Flight::get('flight.base_url'); ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Login</h3>
        </div>

        <form method="POST" action="/login">
          <input type="email" name="mail" placeholder="Email" required>
          <input type="password" name="mdp" placeholder="Mot de passe" required>
          <button type="submit">Login</button>
        </form>


        <?php if (!empty($error)): ?>
          <p style="color:red"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>


      </div>

    </div>
  </div>
</div>