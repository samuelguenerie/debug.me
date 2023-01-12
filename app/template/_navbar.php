<nav class="navbar navbar-expand sticky-top bg-primary">
    <div class="container">
        <a href="index.php?page=home" class="navbar-brand">
            <img src="public/resources/logo.png" alt="Logo de debug.me" width="50" height="50">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="index.php?page=home" class="nav-link text-white">Accueil</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=ticket_index" class="nav-link text-white">Tickets</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <?php if (!isset($sessionUser)): ?>
                    <li class="nav-item">
                        <a href="index.php?page=register" class="nav-link text-white">Inscription</a>
                    </li>
                    <li>
                        <a href="index.php?page=login" class="nav-link text-white">Connexion</a>
                    </li>
                <?php else: ?>
                    <?php if ($sessionUser->getIsModerator()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Modération
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="index.php?page=moderation_user_index" class="dropdown-item">Liste des utilisateurs</a>
                                </li>
                            </ul>
                        </li>

                    <?php endif; ?>

                    <li class="nav-item">
                        <a href="index.php?page=account" class="nav-link text-white">Mon compte</a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=logout" class="nav-link text-white">Déconnexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

