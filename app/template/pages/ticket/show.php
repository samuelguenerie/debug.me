<main class="container">
    <header>
        <h1><?= $data['ticket']->getTitle() ?></h1>
    </header>

    <?php if (!empty($sessionUser) && $sessionUser->getId() === $data['ticket']->getUser()->getId()): ?>
        <a href="?page=ticket_edit&id=<?= $data['ticket']->getId() ?>" class="btn btn-primary" role="button">Éditer mon ticket</a>
        <a href="#" id="close" class="btn btn-danger" role="button">Fermer mon ticket</a>

        <script>
            const closeButton = document.getElementById('close');

            closeButton.addEventListener('click', function(e) {
                e.preventDefault();

                if (confirm("Souhaitez-vous vraiment fermer ce ticket ?")) {
                    location.href='?page=ticket_close&id=' + <?= $data['ticket']->getId() ?>;
                }
            });
        </script>
    <?php elseif (!empty($sessionUser) && $sessionUser->getIsModerator()): ?>
        <a href="#" id="closeModerator" class="btn btn-danger" role="button">Fermer le ticket</a>

        <script>
            const closeModeratorButton = document.getElementById('closeModerator');

            closeModeratorButton.addEventListener('click', function(e) {
                e.preventDefault();

                if (confirm("Souhaitez-vous vraiment fermer ce ticket ?")) {
                    location.href='?page=ticket_close&id=' + <?= $data['ticket']->getId() ?>;
                }
            });
        </script>
    <?php endif; ?>

    <p><?= $data['ticket']->getContent() ?></p>

    <img src="<?= $data['ticket']->getImage() ?>">

    <p>Posté le <?= $serviceDate->convertDateInFrench($data['ticket']->getCreatedAt()) ?> par <?= $data['ticket']->getUser()->getUsername() ?> | <?= $data['ticket']->getUser()->getPoints() ?> point<?php if ($data['ticket']->getUser()->getPoints() > 1): ?>s<?php endif; ?></p>

    <hr>

    <div class="row">
        <div class="col-12 col-md-8">
            <h2>Commentaires</h2>

            <?php if (count($data['ticket']->getComments()) > 0): ?>
                <?php foreach ($data['ticket']->getComments() as $comment): ?>
                    <article class="mb-3">
                        <?= $comment->getContent() ?>

                        <footer class="d-flex justify-content-between text-secondary">
                            <div>
                                Posté par <?= $serviceDate->convertDateInFrench($comment->getCreatedAt()) ?>
                            </div>
                            <div>
                                <a
                                    href="?page=ticket_comment_score_increment&id=<?= $comment->getId() ?>"
                                    <?php if (!empty($sessionUser) && !empty($comment->getScoreFromUser($sessionUser)) && $comment->getScoreFromUser($sessionUser)->getScore() > 0): ?>
                                        class="btn btn-primary disabled"
                                        aria-disabled="true"
                                    <?php else: ?>
                                        class="btn btn-primary"
                                    <?php endif; ?>
                                    role="button"
                                >
                                    +
                                </a>

                                <?= $comment->getScore() ?>

                                <a
                                    href="?page=ticket_comment_score_decrement&id=<?= $comment->getId() ?>"
                                    <?php if (!empty($sessionUser) && !empty($comment->getScoreFromUser($sessionUser)) && $comment->getScoreFromUser($sessionUser)->getScore() <= 0): ?>
                                        class="btn btn-danger disabled"
                                        aria-disabled="true"
                                    <?php else: ?>
                                        class="btn btn-danger"
                                    <?php endif; ?>
                                    role="button"
                                >
                                    -
                                </a>
                            </div>
                        </footer>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Il n'y a aucun commentaire.</p>
            <?php endif; ?>
        </div>

        <div class="col-12 col-md-4">
            <h3>Répondre au ticket</h3>

            <?php if (!empty($sessionUser)): ?>
                <form action="?page=ticket_comment_add&id=<?= $data['ticket']->getId() ?>" method="post">
                    <div class="mb-3">
                        <label for="content" class="form-label">Message</label>
                        <textarea rows="10" placeholder="Saisir votre message" name="content" id="content" required class="form-control"></textarea>
                    </div>

                    <input type="submit" value="Répondre" class="btn btn-primary" role="button">
                </form>
            <?php else: ?>
                <a href="?page=login" class="btn btn-primary" role="button">Connectez-vous pour commenter</a>
            <?php endif; ?>
        </div>
    </div>
</main>
