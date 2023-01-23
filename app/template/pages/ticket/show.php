<main class="container">
    <header class="my-5">
        <h1><?= $data['ticket']->getTitle() ?></h1>
    </header>

    <?php if (!empty($sessionUser)): ?>
        <?php if ($sessionUser->getId() === $data['ticket']->getUser()->getId()): ?>
            <div class="mb-5">
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
            </div>
        <?php elseif ($sessionUser->getIsModerator()): ?>
            <div class="mb-5">
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
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($data['ticket']->getImage())): ?>
        <div class="row">
            <div class="col-12 col-lg-8">
                <?= $data['ticket']->getContent() ?>
            </div>

            <div class="col-12 col-lg-4">
                <img src="<?= $data['ticket']->getImage() ?>" class="img-fluid" style="max-height: 500px;">
            </div>
        </div>
    <?php else: ?>
        <div>
            <?= $data['ticket']->getContent() ?>
        </div>
    <?php endif; ?>

    <div class=" text-secondary my-5">
        Posté par <?= $data['ticket']->getUser()->getUsername() ?> le <?= $serviceDate->convertDateInFrench($data['ticket']->getCreatedAt()) ?>

        <hr>
    </div>

    <div class="row">
        <div class="col-12">
            <h2 class="mb-5">Commentaires</h2>

            <?php if (count($data['ticket']->getComments()) > 0): ?>
                <?php foreach ($data['ticket']->getComments() as $comment): ?>
                    <article class="mb-4">
                        <div class="row">
                            <div class="col-12 col-sm-10">
                                <?= $comment->getContent() ?>

                                <footer class="text-secondary mt-2">
                                    Posté par <?= $comment->getUser()->getUsername() ?> le <?= $serviceDate->convertDateInFrench($comment->getCreatedAt()) ?><?php if (!empty($sessionUser) && $sessionUser->getIsModerator()): ?> | <a href="index.php?page=ticket_comment_delete&id=<?= $comment->getId() ?>" class="text-decoration-none">Supprimer le commentaire</a><?php endif; ?>
                                </footer>
                            </div>

                            <div class="col-12 col-lg-2 mt-2 mt-lg-0">
                                <a
                                        href="index.php?page=ticket_comment_score_increment&id=<?= $comment->getId() ?>"
                                    <?php if (!empty($sessionUser) && !empty($comment->getScoreFromUser($sessionUser)) && $comment->getScoreFromUser($sessionUser)->getScore() > 0): ?>
                                        class="btn btn-success disabled"
                                        aria-disabled="true"
                                    <?php else: ?>
                                        class="btn btn-success"
                                    <?php endif; ?>
                                        role="button"
                                >
                                    +
                                </a>

                                <div class="btn pe-none border border-secondary">
                                    <?= $comment->getScore() ?>
                                </div>

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


                        <section>
                            <?php if (count($comment->getChild()) > 0): ?>
                                <?php foreach ($comment->getChild() as $child): ?>
                                    <article class="my-3">
                                        <div class="row">
                                            <div class="col-12 col-sm-10">
                                                <div class="ps-5">
                                                    <?= $child->getContent() ?>

                                                    <footer class="text-secondary mt-2">
                                                        Posté par <?= $child->getUser()->getUsername() ?> le <?= $serviceDate->convertDateInFrench($child->getCreatedAt()) ?><?php if (!empty($sessionUser) && $sessionUser->getIsModerator()): ?> | <a href="index.php?page=ticket_comment_delete&id=<?= $child->getId() ?>" class="text-decoration-none">Supprimer le commentaire</a><?php endif; ?>
                                                    </footer>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-2 mt-2 mt-lg-0">
                                                <div class="ps-5 ps-lg-0">
                                                    <a
                                                            href="index.php?page=ticket_comment_score_increment&id=<?= $child->getId() ?>"
                                                        <?php if (!empty($sessionUser) && !empty($child->getScoreFromUser($sessionUser)) && $child->getScoreFromUser($sessionUser)->getScore() > 0): ?>
                                                            class="btn btn-success disabled"
                                                            aria-disabled="true"
                                                        <?php else: ?>
                                                            class="btn btn-success"
                                                        <?php endif; ?>
                                                            role="button"
                                                    >
                                                        +
                                                    </a>

                                                    <div class="btn pe-none border border-secondary">
                                                        <?= $child->getScore() ?>
                                                    </div>

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
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <div class="my-3 ps-5">
                                <?php if (!empty($sessionUser)): ?>
                                    <form action="index.php?page=ticket_comment_reply&id=<?= $comment->getId() ?>" method="post">
                                        <div class="mb-4">
                                            <label for="content" class="form-label fw-bold">Répondre au commentaire</label>
                                            <textarea rows="3" placeholder="Saisir votre message" name="content" id="content" required class="form-control"></textarea>
                                        </div>

                                        <input type="submit" value="Répondre" class="btn btn-primary" role="button">
                                    </form>
                                <?php else: ?>
                                    <a href="index.php?page=login" class="btn btn-primary" role="button">Connectez-vous pour répondre au commentaire</a>
                                <?php endif; ?>
                            </div>
                        </section>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Il n'y a aucun commentaire.</p>
            <?php endif; ?>

            <hr>

            <?php if (!empty($sessionUser)): ?>
                <form action="index.php?page=ticket_comment_add&id=<?= $data['ticket']->getId() ?>" method="post">
                    <div class="mb-4">
                        <label for="content" class="form-label fw-bold">Répondre au ticket</label>
                        <textarea rows="5" placeholder="Saisir votre message" name="content" id="content" required class="form-control"></textarea>
                    </div>

                    <input type="submit" value="Répondre" class="btn btn-primary" role="button">
                </form>
            <?php else: ?>
                <a href="index.php?page=login" class="btn btn-primary" role="button">Connectez-vous pour commenter</a>
            <?php endif; ?>
        </div>
    </div>
</main>
