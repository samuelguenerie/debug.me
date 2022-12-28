<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use Exception;
use Plugo\Controller\AbstractController;

class AccountController extends AbstractController {
    /**
     * @return string|null
     * @throws Exception
     */
    public function register(): ?string
    {
        if (!empty($_POST)) {
            $userManager = new UserManager();
            $user = new User();

            if ($_POST['password'] === $_POST['passwordConfirmation']) {
                $user->setEmail($_POST['email']);
                $user->setPassword($_POST['password']);
                $user->setUsername($_POST['username']);

                $userManager->add($user);

                // TODO: add cookie / session for the user registered and now logged.

                return $this->redirectToRoute('home');
            }

            throw new Exception('Password and password confirmation doesn\'t match.');
        }

        return $this->renderView('account/register.php');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function login(): ?string
    {
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
                        // TODO: add cookie / session for the user logged.

                        return $this->redirectToRoute('home');
                    }

                    throw new Exception('Invalid password.');
                }

                throw new Exception("User " . $user->getUsername() . " is blocked.");
            }

            throw new Exception("User with email $email not found.");
        }

        return $this->renderView('account/login.php');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function logout(): ?string
    {
        /** @var User $userSession */
        $userSession = null; // TODO: get user object from session / cookie.

        if (!empty($userSession)) {
            // TODO: implement logout function

            return $this->redirectToRoute('home');
        }

        throw new Exception('User not logged');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function account(): ?string
    {
        /** @var User $userSession */
        $userSession = null; // TODO: get user object from session / cookie.

        if (!empty($userSession)) {
            if (!$userSession->getIsBlocked()) {
                if (!empty($_POST)) {
                    $userManager = new UserManager();
                    /** @var User $user */
                    $user = $userManager->findOneBy([
                        'email' => $userSession->getEmail(),
                    ]);

                    $user->setEmail($_POST['email']);
                    $user->setPassword($_POST['password']);
                    $user->setUsername($_POST['username']);

                    $userManager->edit($user);

                    return $this->redirectToRoute('account');
                }

                return $this->renderView('account/account.php');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }
}