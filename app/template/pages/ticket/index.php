<main class="container">
    <form action="" method="post" class="d-flex">
        <input type="text" placeholder="Saisir un ticket à rechercher" name="search" id="search" <?php if (isset($_POST['search'])) { echo 'value="' . $_POST['search'] .'"'; } ?> class="form-control">
        <input type="submit" value="Rechercher" class="btn btn-primary" role="button">
    </form>

    <header>
        <h1>Liste des tickets</h1>
    </header>

    <a href="index.php?page=ticket_add" class="btn btn-primary" role="button">Ajouter un ticket</a>

    <?php if (count($data['tickets']) > 0): ?>
        <?php foreach ($data['tickets'] as $ticket) { ?>
            <article>
                <header>
                    <a href="index.php?page=ticket_show&id=<?= $ticket->getId() ?>">
                        <h2><?= $ticket->getTitle() ?></h2>
                    </a>
                </header>

                <footer class="d-flex justify-content-between">
                    <div>
                        <?= count($ticket->getComments()) ?> réponses
                    </div>
                    <div>
                        Dernière réponse le <?= $serviceDate->convertDateInFrench($ticket->getCreatedAt()) ?>
                    </div>
                </footer>
            </article>
        <?php } ?>
    <?php else: ?>
        <?php if (isset($_POST['search'])): ?>
            <p>Il n'y a aucun ticket correspond à votre recherche.</p>
        <?php else: ?>
            <p>Il n'y a aucun ticket.</p>
        <?php endif; ?>
    <?php endif; ?>
</main>
