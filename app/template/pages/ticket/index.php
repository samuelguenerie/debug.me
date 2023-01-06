<main class="container">
    <form action="" method="post" class="d-flex">
        <input type="text" placeholder="Saisir un ticket à rechercher" name="search" id="search" class="form-control">
        <input type="submit" value="Rechercher" class="btn btn-primary" role="button">
    </form>

    <header>
        <h1>Liste des tickets</h1>
    </header>

    <a href="?page=ticket_add" class="btn btn-primary" role="button">Ajouter un ticket</a>

    <?php foreach ($data['tickets'] as $ticket) { ?>
        <article>
            <header>
                <a href="?page=ticket_show&id=<?= $ticket->getId() ?>">
                    <h2><?= $ticket->getTitle() ?></h2>
                </a>
            </header>

            <footer class="d-flex justify-content-between">
                <div>
                    <?= count($ticket->getComments()) ?> réponses
                </div>
                <div>
                    Dernière réponse le <?= date('d F Y', strtotime($ticket->getCreatedAt())) ?>
                </div>
            </footer>
        </article>
    <?php } ?>
</main>
