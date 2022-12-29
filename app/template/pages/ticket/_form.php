<form action="" method="post" enctype="multipart/form-data">
    <div class="form-item">
        <label for="title">Titre</label>
        <input type="text" name="title" id="title" required <?php if (isset($data['ticket'])) { echo 'value="' . htmlspecialchars_decode($data['ticket']->getTitle()) .'"'; } ?>>
    </div>

    <div class="form-item">
        <label for="content">Contenu</label>
        <textarea name="content" id="content" rows="5"><?php if (isset($data['ticket'])) { echo htmlspecialchars_decode($data['ticket']->getContent()); } ?></textarea>
    </div>

    <div class="form-item">
        <label for="content">Image (une seule)</label>
        <input type="file" name="file" id="file">
    </div>

    <?php if (isset($data['ticket']) && !empty($data['ticket']->getImage())): ?>
        <p>Image actuelle</p>
        <img src="<?= htmlspecialchars_decode($data['ticket']->getImage()) ?>">
    <?php endif; ?>

    <div class="form-item">
        <input type="submit" <?php if (!isset($data['ticket'])) { echo 'value="Ajouter"'; } else { echo 'value="Éditer"'; } ?>>
    </div>
</form>