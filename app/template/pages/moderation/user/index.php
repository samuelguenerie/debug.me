<main class="container">
    <form action="" method="post" class="row my-5">
        <div class="col-12 col-sm-10">
            <input type="text" placeholder="Saisir un utilisateur à rechercher" name="search" id="search" <?php if (isset($_POST['search'])) { echo 'value="' . $_POST['search'] .'"'; } ?> class="form-control">
        </div>

        <div class="col-12 col-sm-2">
            <input type="submit" value="Rechercher" class="btn btn-primary" role="button">
        </div>
    </form>

    <header class="mb-5">
        <h1>Liste des utilisateurs</h1>
    </header>

    <?php if (count($data['users']) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Adresse e-mail</th>
                    <th>Pseudonyme</th>
                    <th>Points</th>
                    <th>Est modérateur ?</th>
                    <th>Est bloqué ?</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['users'] as $user): ?>
                    <tr>
                        <td><?= $user->getEmail() ?></td>
                        <td><?= $user->getUsername() ?></td>
                        <td><?= $user->getPoints() ?></td>
                        <td><?= $user->getIsModerator() ? 'Oui' : 'Non' ?></td>
                        <td><?= $user->getIsBlocked() ? 'Oui' : 'Non' ?></td>
                        <td><?= $serviceDate->convertDateInFrench($user->getCreatedAt()) ?></td>
                        <td>
                            <?php if (!$user->getIsBlocked()): ?>
                                <a href="index.php?page=moderation_user_block&id=<?= $user->getId() ?>" class="btn btn-primary" role="button">Bloquer</a>
                            <?php else: ?>
                                <a href="index.php?page=moderation_user_unblock&id=<?= $user->getId() ?>" class="btn btn-primary" role="button">Débloquer</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <?php if (isset($_POST['search'])): ?>
            <p>Il n'y a aucun utilisateur correspond à votre recherche.</p>
        <?php else: ?>
            <p>Il n'y a aucun utilisateur.</p>
        <?php endif; ?>
    <?php endif; ?>
</main>
