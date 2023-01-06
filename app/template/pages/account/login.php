<main class="container">
    <header>
        <h1>Connexion</h1>
    </header>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" placeholder="Saisir votre adresse e-mail" name="email" id="email" required class="form-control">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" placeholder="Saisir votre mot de passe" name="password" id="password" required class="form-control">
        </div>

        <div class="mb-3">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Mémoriser mon compte</label>
        </div>

        <input type="submit" value="Valider" class="btn btn-primary" role="button">
    </form>
</main>
