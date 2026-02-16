<?php

namespace app\controllers;

use app\models\UserModel;
use flight\Engine;
use PDOException;

class ApiExampleController
{
  protected Engine $app;

  public function __construct($app)
  {
    $this->app = $app;
  }

  public function getUsers()
  {
    try {
      $db = $this->app->db();             
      $userModel = new UserModel($db);
      $users = $userModel->getAllExcept(0);

      $this->app->json($users, 200, true, 'utf-8', JSON_PRETTY_PRINT);
    } catch (PDOException $e) {
      $this->app->json([
        'error' => 'Database error',
        'message' => $e->getMessage()
      ], 500);
    }
  }

  public function getUser($id)
  {
    try {
      $db = $this->app->db();              
      $userModel = new UserModel($db);
      $user = $userModel->getById($id);

      if ($user) {
        $this->app->json($user, 200, true, 'utf-8', JSON_PRETTY_PRINT);
      } else {
        $this->app->json([
          'error' => 'User not found',
          'id' => (int)$id
        ], 404);
      }
    } catch (PDOException $e) {
      $this->app->json([
        'error' => 'Database error',
        'message' => $e->getMessage()
      ], 500);
    }
  }
}
