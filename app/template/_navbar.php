<nav class="navbar navbar-expand sticky-top bg-body-tertiary">
    <div class="container">
        <a href="?page=home" class="navbar-brand">
            <img src="public/resources/logo.png" alt="Logo de debug.me" width="50" height="50">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="?page=home" class="nav-link">Accueil</a>
                </li>
                <li class="nav-item">
                    <a href="?page=ticket_index" class="nav-link">Tickets</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <?php if (!isset($sessionUser)): ?>
                    <li class="nav-item">
                        <a href="?page=register" class="nav-link">Inscription</a>
                    </li>
                    <li>
                        <a href="?page=login" class="nav-link">Connexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="?page=account" class="nav-link">Mon compte</a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=logout" class="nav-link">Déconnexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

