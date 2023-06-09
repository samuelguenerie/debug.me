<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use Exception;
use Plugo\Controller\AbstractController;
use Plugo\Services\Auth\Authenticator;
use Plugo\Services\Flash\Toast;

class AccountController extends AbstractController
{
    /**
     * @return string|null
     * @throws Exception
     */
    public function register(): ?string
    {
        if (empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            if (!empty($_POST)) {
                $userManager = new UserManager();
                $user = new User();

                if ($_POST['password'] === $_POST['passwordConfirmation']) {
                    $user->setEmail($_POST['email']);
                    $user->setPassword($_POST['password']);
                    $user->setUsername($_POST['username']);

                    if ($userManager->add($user)) {
                        $authenticator = new Authenticator();

                        $userAdded = $userManager->findOneBy(['email' => $user->getEmail()]);

                        $authenticator->login($userAdded, false);

                        return $this->redirectToRoute('home');
                    }

                    throw new Exception('User ' . $user->getEmail() . ' can\'t be add.');
                }

                return $this->renderView('account/register.php', [
                    'title' => 'Inscription',
                    'error' => 'La combination du mot de passe et de sa confirmation ne fonctionne pas.'
                ]);
            }

            return $this->renderView('account/register.php', [
                'title' => 'Inscription'
            ]);
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function login(): ?string
    {
        if (empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            if (!empty($_POST)) {
                $email = $_POST['email'];
                $userManager = new UserManager();
                /** @var User $user */
                $user = $userManager->findOneBy([
                    'email' => $_POST['email'],
                ]);

                if ($user) {
                    if (!$user->getIsBlocked()) {
                        if (password_verify($_POST['password'], $user->getPassword())) {
                            $authenticator = new Authenticator();
                            $loginInSession = true;

                            if (isset($_POST['remember'])) {
                                $loginInSession = false;
                            }

                            if ($authenticator->login($user, $loginInSession)) {
                                return $this->redirectToRoute('home');
                            }
                        }

                        return $this->renderView('account/login.php', [
                            'title' => 'Connexion',
                            'error' => 'L\'adresse e-mail ou le mot de passe est erroné.'
                        ]);
                    }

                    return $this->renderView('account/login.php', [
                        'title' => 'Connexion',
                        'error' => 'Ce compte est bloqué.'
                    ]);
                }

                return $this->renderView('account/login.php', [
                    'title' => 'Connexion',
                    'error' => 'L\'adresse e-mail ou le mot de passe est erroné.'
                ]);
            }

            return $this->renderView('account/login.php', [
                'title' => 'Connexion'
            ]);
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function logout(): ?string
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            $authenticator = new Authenticator();

            if ($authenticator->logout()) {
                return $this->redirectToRoute('home');
            }

            throw new Exception('User can\'t be logout.');
        }

        return $this->redirectToRoute('login');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function account(): ?string
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                $userManager = new UserManager();
                /** @var User $user */
                $user = $userManager->findOneBy([
                    'email' => $userSession->getEmail(),
                ]);

                if (!empty($_POST)) {
                    if (!empty($_POST['email'])) {
                        $user->setEmail($_POST['email']);
                    }

                    if (!empty($_POST['password'])) {
                        $user->setPassword($_POST['password']);
                    }

                    if (!empty($_POST['username'])) {
                        $user->setUsername($_POST['username']);
                    }

                    $userManager->edit($user);

                    return $this->redirectToRoute('account');
                }

                return $this->renderView('account/account.php', [
                    'title' => 'Gestion de mon compte',
                    'user' => $user
                ]);
            }

            return $this->renderView('account/login.php', [
                'title' => 'Gestion de mon compte',
                'error' => 'Ce compte est bloqué.'
            ]);
        }

        return $this->redirectToRoute('login');
    }
}
