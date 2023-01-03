<?php

 namespace Plugo\Controller;

 use Exception;
 use JetBrains\PhpStorm\NoReturn;
 use Plugo\Services\Auth\Authenticator;

 abstract class AbstractController {
     /**
      * @throws Exception
      */
     public function __construct()
     {
         if (isset($_COOKIE[appName][Authenticator::AUTHENTICATOR_USER])) {
             try {
                 $authenticator = new Authenticator();

                 $authenticator->checkLogged();

                 unset($authenticator);
             } catch (Exception $e) {
                 throw new Exception($e);
             }
         }
     }

     /**
      * @param string $template
      * @param array $data
      * @return string
      */
     protected function renderView(string $template, array $data = []): string
     {
        $templatePath = dirname(__DIR__, 2) . '/template/pages/' . $template;

        return require_once dirname(__DIR__, 2) . '/template/layout.php';
    }

     /**
      * @param string $name
      * @param array $data
      * @return void
      */
     #[NoReturn] protected function redirectToRoute(string $name, array $data = []): void
     {
        $uri = $_SERVER['SCRIPT_NAME'] . "?page=" . $name;

        if (!empty($data)) {
            $strData = [];

            foreach ($data as $key => $value) {
                $strData[] = urlencode((string)$key) . '=' . urlencode((string)$value);
            }

            $uri .= '&' . implode('&', $strData);
        }

        header("Location: " . $uri);

        die;
    }
}
