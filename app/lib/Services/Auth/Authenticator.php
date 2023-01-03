<?php

namespace Plugo\Services\Auth;

use App\Entity\User;
use App\Manager\UserManager;
use Exception;
use Plugo\Manager\AbstractManager;

class Authenticator extends AbstractManager
{
    public const AUTHENTICATOR_USER = 'user';
    private int $userCookieDuration = 60 * 60 * 24 * 7;

    /**
     * @param User $user
     * @param bool $loginInSession
     * @return bool
     */
    public function login(User $user, bool $loginInSession = true): bool
    {
        if ($_SESSION[self::AUTHENTICATOR_USER] = $user) {
            if (!$loginInSession) {
                setcookie(appName . '[' . self::AUTHENTICATOR_USER . ']', $user->getId(), time() + $this->userCookieDuration);
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
        if (isset($_SESSION[self::AUTHENTICATOR_USER])) {
            unset($_SESSION[self::AUTHENTICATOR_USER]);

            if (isset($_COOKIE[appName][self::AUTHENTICATOR_USER])) {
                setcookie(appName . '[' . self::AUTHENTICATOR_USER . ']', '', time() - $this->userCookieDuration);
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
        if (isset($_COOKIE[appName][self::AUTHENTICATOR_USER]) || isset($_SESSION[self::AUTHENTICATOR_USER])) {
            if (!isset($_SESSION[self::AUTHENTICATOR_USER])) {
                $id = $_COOKIE[appName][self::AUTHENTICATOR_USER];
                $userManagement = new UserManager();
                $user = $userManagement->find($id);

                if ($user && !$user->getIsBlocked()) {
                    self::login($user);

                    return true;
                }

                setcookie(appName . '[' . self::AUTHENTICATOR_USER . ']', '', time() - $this->userCookieDuration);

                return false;
            }

            return true;
        }

        return false;
    }
}
