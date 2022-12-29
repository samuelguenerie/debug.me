<?php

namespace App\Controller;

use Plugo\Controller\AbstractController;

class MainController extends AbstractController {
    /**
     * @return string
     */
    public function home(): string
    {
        return $this->renderView('main/home.php');
    }

    /**
     * @return string
     */
    public function termsOfUse(): string
    {
        return $this->renderView('main/terms_of_use.php');
    }

    /**
     * @return string
     */
    public function privacyPolicy(): string
    {
        return $this->renderView('main/privacy_policy.php');
    }
}