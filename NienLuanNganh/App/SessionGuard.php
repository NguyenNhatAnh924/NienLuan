<?php

namespace App;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;

class SessionGuard
{
  protected $user;

  public function login(User $user, array $credentials)
  {
      $verified = password_verify($credentials['password'], $user->password);
      if ($verified) {
          $_SESSION['user_id'] = $user->user_id;
          $_SESSION['user_role'] = $user->role;
      }
      return $verified;
  }

  public function user()
  {
    if (!$this->user && $this->isUserLoggedIn()) {
      $this->user = (new User(PDO()))->where('id', $_SESSION['user_id']);
    }
    return $this->user;
  }
  
    public function doctor()
  {
    if (!$this->user && $this->isUserLoggedIn()) {
      $this->user = (new Doctor(PDO()))->where('id', $_SESSION['user_id']);
    }
    return $this->user;
  }
  
  public function logout()
  {
      $this->user = null;
      session_unset();
      session_destroy();
  }
  

  public function isUserLoggedIn()
  {
    return isset($_SESSION['user_id']);
  }

  
}