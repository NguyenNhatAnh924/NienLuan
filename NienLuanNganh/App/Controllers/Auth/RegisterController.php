<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Receptionist;

class RegisterController extends Controller
{
  public function __construct()
  {
    if (AUTHGUARD()->isUserLoggedIn()) {
      redirect('/home');
    }

    parent::__construct();
  }

  public function create()
  {
    $data = [
      'old' => $this->getSavedFormValues(),
      'errors' => session_get_once('errors')
    ];

    $this->sendPage('auth/register', $data);
  }

  public function store()
  {
    $this->saveFormValues($_POST, ['password', 'password_confirmation']);

    $data = $this->filterUserData($_POST);
    if ($data['role']=='doctor'){
      $newUser = new Doctor(PDO());
    }elseif($data['role']=='' || $data['role'] == 'patient'){
      $newUser = new User(PDO());
    }elseif($data['role'] == 'admin'){
      $newUser = new Admin(PDO());
    }elseif($data['role'] == 'receptionist'){
      $newUser = new Receptionist(PDO());
    }else{
      $newUser = new User(PDO());
    }
    $model_errors = $newUser->validate1($data);
    if (empty($model_errors)) {
      $newUser->fill($data)->save();

      $messages = ['success' => 'Đang ký thành công vui lòng đăng nhập bàng tài khoản cửa bạn!'];
      redirect('/login', ['messages' => $messages]);
    }

    // Dữ liệu không hợp lệ...
    redirect('/register', ['errors' => $model_errors]);
  }


protected function filterUserData(array $data)
{
    return [
        'username' => $data['username'] ?? null,
        'name'     => $data['name'] ?? null,
        'email'    => filter_var($data['email'], FILTER_VALIDATE_EMAIL),
        'password' => $data['password'] ?? null,
        'password_confirmation' => $data['password_confirmation'] ?? null,
        'phone'    => $data['phone'] ?? null,
        'address'  => $data['address'] ?? null,
        'role'     => $data['role'] ?? 'patient' // Nếu không chọn role thì mặc định là 'patient'
    ];
}

}