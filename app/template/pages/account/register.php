<main class="container">
    <header>
        <h1>Inscription</h1>
    </header>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" placeholder="Saisir votre adresse e-mail" name="email" id="email" required class="form-control">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" placeholder="Saisir votre mot de passe" name="password" id="password" minlength="5" required class="form-control">
        </div>

        <div class="mb-3">
            <label for="passwordConfirmation" class="form-label">Confirmation du mot de passe</label>
            <input type="password" placeholder="Saisir à nouveau votre mot de passe" name="passwordConfirmation" id="passwordConfirmation" required class="form-control">
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Pseudonyme</label>
            <input type="text" placeholder="Saisir votre pseudonyme" name="username" id="username" required class="form-control">
        </div>

        <input type="submit" value="Valider" class="btn btn-primary" role="button">
    </form>
</main>
