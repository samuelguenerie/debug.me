<?php

namespace Plugo\Services\Auth;

use Plugo\Manager\AbstractManager;

class Authenticator extends AbstractManager
{
    private function register($class, $data)
    {
    }

    private function login($class, $data)
    {
        /*
        $query = "SELECT * FROM '.$this->classToTable($class).' WHERE email = '" . $data['email'] . "'";
        $stmt = $this->executeQuery($query, ['id' => $id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $stmt->fetch();

        verification mot de passe 
        if($data['password'] === sha($data['password'])){

        }

        utlisation possible de readOneBy() pour passer le parametre email et password

        si tout bon yooo jb ;)
         $_SESSION['logged'] = TRUE;

        */
    }

    private function logout()
    {
        $_SESSION['logged'] = FALSE;

        $_SESSION['id'] = $this->getId();
    }
}
