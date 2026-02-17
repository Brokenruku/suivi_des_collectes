<?php require __DIR__ . '/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">reduction</h3>
                </div>

                <form method="POST" action="/reduction">
                    <div class="card-body">
                        <div class="form-group mb-3"><label for="reduction">update reduction</label>
                            <input type="number" class="form-control" id="reduction" name="reduction" required>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">submit</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>