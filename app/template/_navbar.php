<header class="container">
    <nav>
        <ul>
            <li>
                <strong>debug.me</strong>
            </li>
        </ul>

        <ul>
            <li>
                <a href="index.php?page=ticket_index">Tickets</a>
            </li>
            <?php if (isset($_SESSION[\Plugo\Services\Auth\Authenticator::AUTHENTICATOR_USER])): ?>
            <li>
                <a href="index.php?page=ticket_add" role="button">
                    <i class="fa-solid fa-plus"></i> Nouveau
                </a>
            </li>
            <?php endif; ?>
        </ul>

        <ul>
            <?php if (!isset($_SESSION[\Plugo\Services\Auth\Authenticator::AUTHENTICATOR_USER])): ?>
            <li>
                <a href="index.php?page=register">Inscription</a>
            </li>
            <li>
                <a href="index.php?page=login">Connexion</a>
            </li>
            <?php else: ?>
            <li>
                <a href="index.php?page=logout">Déconnexion</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
