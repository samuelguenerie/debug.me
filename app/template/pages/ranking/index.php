<main class="container">
    <header class="my-5">
        <h1>Classement</h1>
    </header>

    <?php if (count($data['users']) > 0): ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Pseudonyme</th>
                <th>Points</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['users'] as $user): ?>
                <tr>
                    <td><?= $user->getUsername() ?></td>
                    <td><?= $user->getPoints() ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Il n'y a aucun utilisateur.</p>
    <?php endif; ?>
</main>
