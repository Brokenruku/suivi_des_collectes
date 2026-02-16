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

  public static function showRegister()
  {
    Flight::render('register');
  }

  public static function register()
  {
    $nom = $_POST['nom'] ?? '';
    $mail = $_POST['mail'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    $numero = $_POST['numero'] ?? '';
  //validation
    if ($nom === '' || $mail === '' || $mdp === '') {
      Flight::render('register', ['error' => 'Champs obligatoires manquants (nom, email, mot de passe)']);
      return;
    }

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
      Flight::render('register', ['error' => 'Email invalide']);
      return;
    }

    if (strlen($mdp) < 4) {
      Flight::render('register', ['error' => 'Le mot de passe doit contenir au moins 4 caractères']);
      return;
    }

    $pdo = Flight::db();

    $success = UserModel::registerUser($pdo, $nom, $mail, $mdp, $numero);

    if ($success) {
      Flight::render('register', ['success' => 'Inscription réussi! Vous pouvez maintenant vous connecter.']);
      return;
    }

    Flight::render('register', ['error' => 'Cet email est déjà utilisé']);
  }
}

