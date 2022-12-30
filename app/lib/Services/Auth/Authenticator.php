<?php

namespace Plugo\Services\Auth;

use App\Entity\User;
use App\Manager\UserManager;
use Exception;
use Plugo\Manager\AbstractManager;

class Authenticator extends AbstractManager
{
    const cookieParent = 'DEBUGME';

    private int $userCookieDuration = 60 * 60 * 24 * 7;

    public function register($class, $data)
    {
    }

    /**
     * @param User $user
     * @param bool $loginInSession
     * @return bool
     */
    public function login(User $user, bool $loginInSession = true): bool
    {
        if ($_SESSION['user'] = $user) {
            if (!$loginInSession) {
                setcookie(self::cookieParent . '[user]', $user->getId(), time() + $this->userCookieDuration);
            }

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function logout(): bool
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);

            if (isset($_COOKIE[self::cookieParent]['user'])) {
                setcookie(self::cookieParent . '[user]', '', time() - $this->userCookieDuration);
            }

            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function checkLogged(): bool
    {
        if (isset($_COOKIE[self::cookieParent]['user']) || isset($_SESSION['user'])) {
            if (!isset($_SESSION['user'])) {
                $id = $_COOKIE[self::cookieParent]['user'];
                $userManagement = new UserManager();
                $user = $userManagement->find($id);

                if ($user && !$user->getIsBlocked()) {
                    self::login($user);

                    return true;
                }

                setcookie(self::cookieParent . '[user]', '', time() - $this->userCookieDuration);

                return false;
            }

            return true;
        }

        return false;
    }
}
