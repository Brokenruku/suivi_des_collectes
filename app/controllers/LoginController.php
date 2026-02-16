<?php
namespace app\controllers;

use Flight;
use app\models\UserModel;

class LoginController
{
    public static function showLogin()
    {
        Flight::render('login');
    }

    public static function login()
    {
        $email = $_POST['email'] ?? '';
        $mdp   = $_POST['mdp'] ?? '';

        $pdo  = Flight::db();
        $user = UserModel::checkLogin($pdo, $email, $mdp);

        if ($user) {
            $_SESSION['user'] = $user;
            Flight::redirect('/accueil');
            return;
        }

        Flight::render('login', ['error' => 'Login incorrect']);
    }
}
