<main class="container">
    <header class="my-5">
        <h1>Inscription</h1>
    </header>

    <?php if (isset($data['error'])): ?>
        <div class="alert alert-danger mb-5" role="alert">
            <?= $data['error'] ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12 col-lg-4">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="email" class="form-label fw-bold">Adresse e-mail</label>
                    <input type="email" placeholder="Saisir votre adresse e-mail" name="email" id="email" required <?php if (isset($_POST['email'])) { echo 'value="' . $_POST['email'] .'"'; } ?> class="form-control">
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Mot de passe</label>
                    <input type="password" placeholder="Saisir votre mot de passe" name="password" id="password" minlength="5" required class="form-control">
                </div>

                <div class="mb-4">
                    <label for="passwordConfirmation" class="form-label fw-bold">Confirmation du mot de passe</label>
                    <input type="password" placeholder="Saisir à nouveau votre mot de passe" name="passwordConfirmation" id="passwordConfirmation" required class="form-control">
                </div>

                <div class="mb-4">
                    <label for="username" class="form-label fw-bold">Pseudonyme</label>
                    <input type="text" placeholder="Saisir votre pseudonyme" name="username" id="username" required <?php if (isset($_POST['username'])) { echo 'value="' . $_POST['username'] .'"'; } ?> class="form-control">
                </div>

                <input type="submit" value="Valider" class="btn btn-primary" role="button">
            </form>
        </div>

        <div class="col-12 col-lg-8 align-self-end">
            <img src="public/resources/register.png" alt="Illustration de l'inscription" class="img-fluid">
        </div>
    </div>
</main>
