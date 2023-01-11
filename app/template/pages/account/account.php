<main class="container">
    <header class="my-5">
        <h1>Gestion de mon compte</h1>
    </header>

    <div class="row">
        <div class="col-12 col-lg-4">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Adresse e-mail</label>
                    <input type="email" placeholder="Saisir votre adresse e-mail" name="email" id="email" required class="form-control" <?php if (isset($data['user'])) { echo 'value="' . $data['user']->getEmail() .'"'; } ?>>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Mot de passe</label>
                    <input type="password" placeholder="Saisir votre mot de passe" name="password" id="password" minlength="5" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">Pseudonyme</label>
                    <input type="text" placeholder="Saisir votre pseudonyme" name="username" id="username" required class="form-control" <?php if (isset($data['user'])) { echo 'value="' . $data['user']->getUsername() .'"'; } ?>>
                </div>

                <input type="submit" value="Valider" class="btn btn-primary" role="button">
            </form>
        </div>
    </div>
</main>
