<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use Exception;
use Plugo\Controller\AbstractController;
use Plugo\Services\Auth\Authenticator;
use Plugo\Services\Flash\Toast;

class ModerationController extends AbstractController
{
    /**
     * @return string
     * @throws Exception
     */
    public function userIndex(): string
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if ($userSession->getIsModerator()) {
                    $userManager = new UserManager();

                    if (!empty($_POST) && !empty($_POST['search'])) {
                        $search = '%' . $_POST['search'] . '%';

                        $users = $userManager->search([], $search, ['created_at' => 'DESC']);
                    } else {
                        $users = $userManager->findBy([], ['created_at' => 'DESC']);
                    }

                    return $this->renderView('moderation/user/index.php', [
                        'title' => 'Liste des utilisateurs',
                        'users' => $users
                    ]);
                }

                return $this->redirectToRoute('home');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function userBlock(): ?string
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if ($userSession->getIsModerator()) {
                    if (!empty($_GET['id'])) {
                        $id = $_GET['id'];
                        $userManager = new UserManager();
                        /** @var User $ticket */
                        $user = $userManager->find($id);

                        if (!$user) {
                            throw new Exception("User with id $id not found.");
                        } elseif ($user->getId() === $userSession->getId()) {
                            throw new Exception($userSession->getUsername() . ' can\'t block himself.');
                        }

                        $user->setIsBlocked(true);

                        $userManager->edit($user);

                        return $this->redirectToRoute('moderation_user_index');
                    }


                    throw new Exception('Parameter id required in url.');
                }

                throw new Exception('User ' . $userSession->getUsername() . ' is not moderator.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function userUnblock(): ?string
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if ($userSession->getIsModerator()) {
                    if (!empty($_GET['id'])) {
                        $id = $_GET['id'];
                        $userManager = new UserManager();
                        /** @var User $ticket */
                        $user = $userManager->find($id);

                        if (!$user) {
                            throw new Exception("User with id $id not found.");
                        } elseif ($user->getId() === $userSession->getId()) {
                            throw new Exception($userSession->getUsername() . ' can\'t unblock himself.');
                        }

                        $user->setIsBlocked(false);

                        $userManager->edit($user);

                        return $this->redirectToRoute('moderation_user_index');
                    }


                    throw new Exception('Parameter id required in url.');
                }

                throw new Exception('User ' . $userSession->getUsername() . ' is not moderator.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }
}