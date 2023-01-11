<div class="row">
    <div class="col-12 col-lg-4">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" placeholder="Saisir le titre" name="title" id="title" required <?php if (isset($data['ticket'])) { echo 'value="' . $data['ticket']->getTitle() .'"'; } ?> class="form-control">
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Message</label>
                <textarea placeholder="Saisir le message" name="content" id="content" rows="5" class="form-control"><?php if (isset($data['ticket'])) { echo $data['ticket']->getContent(); } ?></textarea>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Image (une seule)</label>
                <input type="file" name="file" id="file" class="form-control">
            </div>

            <?php if (isset($data['ticket']) && !empty($data['ticket']->getImage())): ?>
                <p>Image actuelle</p>
                <img src="<?= $data['ticket']->getImage() ?>">
            <?php endif; ?>

            <div class="d-flex">
                <input type="submit" <?php if (!isset($data['ticket'])) { echo 'value="Ajouter"'; } else { echo 'value="Éditer"'; } ?> class="btn btn-primary me-3" role="button">
                <a href="index.php?page=ticket_index" class="btn btn-secondary" role="button">Annuler</a>
            </div>
        </form>
    </div>
</div>