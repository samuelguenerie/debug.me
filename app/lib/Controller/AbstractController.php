<?php

 namespace Plugo\Controller;

 use JetBrains\PhpStorm\NoReturn;

 abstract class AbstractController {
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