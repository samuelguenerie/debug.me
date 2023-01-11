<main class="container">
    <header>
        <h1><?= $data['ticket']->getTitle() ?></h1>
    </header>

    <?php if (!empty($sessionUser) && $sessionUser->getId() === $data['ticket']->getUser()->getId()): ?>
        <a href="index.php?page=ticket_edit&id=<?= $data['ticket']->getId() ?>" class="btn btn-primary" role="button">Éditer mon ticket</a>
        <a href="#" id="close" class="btn btn-danger" role="button">Fermer mon ticket</a>

        <script>
            const closeButton = document.getElementById('close');

            closeButton.addEventListener('click', function(e) {
                e.preventDefault();

                if (confirm("Souhaitez-vous vraiment fermer ce ticket ?")) {
                    location.href='index.php?page=ticket_close&id=' + <?= $data['ticket']->getId() ?>;
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
                    location.href='index.php?page=ticket_close&id=' + <?= $data['ticket']->getId() ?>;
                }
            });
        </script>
    <?php endif; ?>

    <p><?= $data['ticket']->getContent() ?></p>

    <img src="<?= $data['ticket']->getImage() ?>">

    <p>Posté par <?= $data['ticket']->getUser()->getUsername() ?> le <?= $serviceDate->convertDateInFrench($data['ticket']->getCreatedAt()) ?></p>

    <hr>

    <div class="row">
        <div class="col-12 col-md-8">
            <h2>Commentaires</h2>

            <?php if (count($data['ticket']->getComments()) > 0): ?>
                <?php foreach ($data['ticket']->getComments() as $comment): ?>
                    <article class="mb-5">
                        <div class="row">
                            <div class="col-12 col-sm-10">
                                <?= $comment->getContent() ?>

                                <footer class="text-secondary">
                                    Posté par <?= $comment->getUser()->getUsername() ?> le <?= $serviceDate->convertDateInFrench($comment->getCreatedAt()) ?><?php if (!empty($sessionUser) && $sessionUser->getIsModerator()): ?> | <a href="index.php?page=ticket_comment_delete&id=<?= $comment->getId() ?>">Supprimer le commentaire</a><?php endif; ?>
                                </footer>
                            </div>

                            <div class="col-12 col-sm-2">
                                <a
                                        href="index.php?page=ticket_comment_score_increment&id=<?= $comment->getId() ?>"
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
                                        href="index.php?page=ticket_comment_score_decrement&id=<?= $comment->getId() ?>"
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
                        </div>


                        <?php if (count($comment->getChild()) > 0): ?>
                            <?php foreach ($comment->getChild() as $child): ?>
                                <article class="my-3">
                                    <div class="row">
                                        <div class="col-12 col-sm-10 ps-5">
                                            <?= $child->getContent() ?>

                                            <footer class="text-secondary">
                                                Posté par <?= $child->getUser()->getUsername() ?> le <?= $serviceDate->convertDateInFrench($child->getCreatedAt()) ?><?php if (!empty($sessionUser) && $sessionUser->getIsModerator()): ?> | <a href="index.php?page=ticket_comment_delete&id=<?= $child->getId() ?>">Supprimer le commentaire</a><?php endif; ?>
                                            </footer>
                                        </div>

                                        <div class="col-12 col-sm-2">
                                            <a
                                                    href="index.php?page=ticket_comment_score_increment&id=<?= $child->getId() ?>"
                                                <?php if (!empty($sessionUser) && !empty($child->getScoreFromUser($sessionUser)) && $child->getScoreFromUser($sessionUser)->getScore() > 0): ?>
                                                    class="btn btn-primary disabled"
                                                    aria-disabled="true"
                                                <?php else: ?>
                                                    class="btn btn-primary"
                                                <?php endif; ?>
                                                    role="button"
                                            >
                                                +
                                            </a>

                                            <?= $child->getScore() ?>

                                            <a
                                                    href="index.php?page=ticket_comment_score_decrement&id=<?= $child->getId() ?>"
                                                <?php if (!empty($sessionUser) && !empty($child->getScoreFromUser($sessionUser)) && $child->getScoreFromUser($sessionUser)->getScore() <= 0): ?>
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
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Il n'y a aucun commentaire.</p>
            <?php endif; ?>
        </div>

        <div class="col-12 col-md-4">
            <h3>Répondre au ticket</h3>

            <?php if (!empty($sessionUser)): ?>
                <form action="index.php?page=ticket_comment_add&id=<?= $data['ticket']->getId() ?>" method="post">
                    <div class="mb-3">
                        <label for="content" class="form-label">Message</label>
                        <textarea rows="10" placeholder="Saisir votre message" name="content" id="content" required class="form-control"></textarea>
                    </div>

                    <input type="submit" value="Répondre" class="btn btn-primary" role="button">
                </form>
            <?php else: ?>
                <a href="index.php?page=login" class="btn btn-primary" role="button">Connectez-vous pour commenter</a>
            <?php endif; ?>
        </div>
    </div>
</main>
