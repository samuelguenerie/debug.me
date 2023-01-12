<main class="container">
    <form action="" method="post" class="row my-5">
        <div class="col-12 col-sm-10">
            <input type="text" placeholder="Saisir un ticket à rechercher" name="search" id="search" <?php if (isset($_POST['search'])) { echo 'value="' . $_POST['search'] .'"'; } ?> class="form-control">
        </div>

        <div class="col-12 col-sm-2">
            <input type="submit" value="Rechercher" class="btn btn-primary" role="button">
        </div>
    </form>

    <header class="mb-5">
        <h1>Liste des tickets</h1>
    </header>

    <a href="index.php?page=ticket_add" class="btn btn-primary mb-5" role="button">Ajouter un ticket</a>

    <?php if (count($data['tickets']) > 0): ?>
        <?php foreach ($data['tickets'] as $ticket) { ?>
            <article class="mb-4">
                <header>
                    <a href="index.php?page=ticket_show&id=<?= $ticket->getId() ?>" class="text-decoration-none">
                        <h2><?= $ticket->getTitle() ?></h2>
                    </a>
                </header>

                <footer class="d-flex justify-content-between">
                    <div>
                        <?= count($ticket->getComments()) ?> réponse<?php if (count($ticket->getComments()) > 1): ?>s<?php endif; ?> | Posté par <?= $ticket->getUser()->getUsername() ?> le <?= $serviceDate->convertDateInFrench($ticket->getCreatedAt()) ?>
                    </div>
                    <?php if (!empty($ticket->getLastReply())): ?>
                        <div>
                            Dernière réponse le <?= $serviceDate->convertDateInFrench($ticket->getLastReply()->getCreatedAt()) ?>
                        </div>
                    <?php endif; ?>
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
