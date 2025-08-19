<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;

class RegisterControllerEmloyee extends Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function create()
  {
    $data = [
      'old' => $this->getSavedFormValues(),
      'errors' => session_get_once('errors')
    ];

    $this->sendPage('auth/registerEmloyee', $data);
  }

  public function store()
  {
    $this->saveFormValues($_POST, ['password', 'password_confirmation']);

    $data = $this->filterUserData($_POST);
    if ($data['role']=='doctor'){
      $newUser = new Doctor(PDO());
    }elseif($data['role']=='' || $data['role'] == 'patient'){
      $newUser = new Patient(PDO());
    }else{
      $newUser = new User(PDO());
    }
    $model_errors = $newUser->validate($data);
    if (empty($model_errors)) {
      $newUser->fill($data)->save();
      $messages = ['success' => 'Đang ký thành công vui lòng đăng nhập bàng tài khoản cửa bạn!'];
      redirect('/admin/staffs', ['messages' => $messages]);
    }

    // Dữ liệu không hợp lệ...
    redirect('/registerEmloyee', ['errors' => $model_errors]);
  }
  

  protected function filterUserData(array $data)
  {
    return [
      'name' => $data['name'] ?? null,
      'email' => filter_var($data['email'], FILTER_VALIDATE_EMAIL),
      'password' => $data['password'] ?? null,
      'password_confirmation' => $data['password_confirmation'] ?? null,
      'phone' => $data['phone'] ?? null,
      'address' => $data['address'] ?? null,
      'role' => $data['role'] ?? null, // Lưu vai trò của người dùng
      'experience' => $data['experience'] ?? null, // Thêm trường experience nếu cần cho bác sĩ
      'age' => $data['age'] ?? null, // Thêm trường age nếu cần cho bác sĩ
      'sex' => $data['sex'] ?? null, // Thêm trường sex nếu cần cho bác sĩ
      'level' => $data['level'] ?? null // Thêm trường level nếu cần cho bác sĩ
    ];
  }
}