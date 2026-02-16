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
    $mail = $_POST['mail'] ?? ($_POST['email'] ?? '');
    $mdp  = $_POST['mdp']  ?? ($_POST['password'] ?? '');

    if ($mail === '' || $mdp === '') {
      Flight::render('login', ['error' => 'Champs manquants (mail/mdp)']);
      return;
    }


    $pdo = Flight::db();
    $user = UserModel::checkLogin($pdo, $mail, $mdp);

    if ($user) {
      $_SESSION['user'] = $user;
      Flight::redirect('/accueil');
      return;
    }

    Flight::render('login', ['error' => 'Login incorrect']);
  }
}
