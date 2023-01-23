<?php

namespace App\Controller;

use App\Manager\UserManager;
use Plugo\Controller\AbstractController;
use ReflectionException;

class RankingController extends AbstractController {
    /**
     * @return string
     * @throws ReflectionException
     */
    public function index(): string
    {
        $userManager = new UserManager();
        $users = $userManager->findBy([], ['points' => 'DESC']);

        return $this->renderView('ranking/index.php', [
            'title' => 'Classement',
            'users' => $users
        ]);
    }
}
