<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\Doctor;
use App\Models\DoctorWorkSchedule;
use App\Models\Patient;
use App\Models\Receptionist;
use App\Models\Services;
use App\Models\User;
use PDO;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }



    protected function filterUserData(array $data): array
    {
        return [
            'user_id'           => $this->e($data['user_id'] ?? ''),
            'username'          => $this->e($data['username'] ?? ''),
            'name'              => $this->e($data['name'] ?? ''),
            'email'             => $this->e($data['email'] ?? ''),
            'phone'             => $this->e($data['phone'] ?? ''),
            'address'           => $this->e($data['address'] ?? ''),
            'specialization'    => $this->e($data['specialization'] ?? ''),
            'working_time'      => $this->e($data['working_time'] ?? ''),
            'experience_years'  => $this->e($data['experience_years'] ?? ''),
            'introduction'      => $this->e($data['introduction'] ?? ''),
            'gender'            => $this->e($data['gender'] ?? ''),
            'date_of_birth'     => $this->e($data['dob'] ?? ''),
            'password'     => $this->e($data['password'] ?? ''),
            'password_confirmation'     => $this->e($data['password_confirmation'] ?? ''),
            'role'              => $this->e($data['position'] ?? ''), // map từ form "position"
            'work_status'       => $this->e($data['status'] ?? 'Đang làm việc'),
            'avatar'            => $_FILES['avatar']['name'] ?? '', // tên file, không escape
        ];
    }



    protected function filterPatientData(array $data)
    {
        return [
            'email' => $this->e($data['email'] ?? ''),
            'name' => $this->e($data['name'] ?? ''),
            'phone' => $this->e($data['phone'] ?? ''),
            'address' => $this->e($data['address'] ?? ''),
            'role' => $this->e($data['position']),
            'age' => $this->e($data['age'] ?? ''),
            'medical_history' => $this->e($data['medical_history'] ?? ''),
            'date_of_birth' => $this->e($data['date_of_birth'] ?? ''),
            'blood_type' => $this->e($data['blood_type'] ?? ''),
            'insurance_number' => $this->e($data['insurance_number'] ?? ''),
            'allergies' => $this->e($data['allergies'] ?? ''),
            'emergency_contact_name' => $this->e($data['emergency_contact_name'] ?? ''),
            'emergency_contact_phone' => $this->e($data['emergency_contact_phone'] ?? '')
        ];
    }

    protected function fillterSchedule(array $data)
    {
        return [
            "id" => $this->e($data['scheduleID'] ?? ''),
            'doctor_id' => $this->e($data['doctor_id'] ?? ''),
            'specific_date' => $this->e($data['specific_date'] ?? ''),
            'session_id' => $this->e($data['session_id'] ?? ''),
            'notes' => $this->e($data['notes'] ?? ''),
            'status' => $this->e($data['status'] ?? 'pending')
        ];
    }

    public function update($userID, $role)
    {
        // Tạo đối tượng bác sĩ và tìm thông tin
        $newUser = new Doctor(PDO());
        $user = $newUser->find($userID);

        if (!$user) {
            $user = new Doctor(PDO());
        }

        // Lọc dữ liệu từ form
        if ($role === 'doctor' || $role === 'admin' || $role === 'reception') {
            $data = $this->filterUserData($_POST);
        } else {
            $data = $this->filterPatientData($_POST);
        }
        // Giữ nguyên avatar cũ nếu không upload mới
        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $data['avatar'] = $user->avatar;
        }

        // ===== Avatar Upload xử lý tại đây =====
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['avatar']['tmp_name'];
            $fileName = basename($_FILES['avatar']['name']);
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

            // Đổi tên file tránh trùng lặp
            $newFileName = uniqid('avatar_') . '.' . $fileExt;
            $uploadDir = __DIR__ . '/../../public/assets/img/person/';
            $uploadPath = $uploadDir . $newFileName;

            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($fileTmp, $uploadPath)) {
                $data['avatar'] = $newFileName;
            } else {
                $_SESSION['error_message'][] = "Không thể tải lên ảnh đại diện.";
            }
        }

        // Validate dữ liệu
        $model_errors = $user->validateUpdate($data);

        if (empty($model_errors)) {
            $user->fill($data);
            $user->save();

            $_SESSION['success_message'] = 'Cập nhật thành công';
            if (AUTHGUARD()->user()->role === 'admin') {
                redirect('/admin');
            } else {
                redirect('/profile/' . AUTHGUARD()->user()->user_id);
            }
        }

        // Có lỗi xảy ra
        $_SESSION['error_message'] = $model_errors;
        $this->saveFormValues($_POST);

        if (AUTHGUARD()->user()->role === 'admin') {
            redirect('/admin');
        } else {
            redirect('/profile/' . AUTHGUARD()->user()->user_id);
        }
    }

    public function save()
    {
        $this->saveFormValues($_POST, ['password', 'password_confirmation']);
        $data = $this->filterUserData($_POST);
        $newUser = new Doctor(PDO());
        $model_errors = $newUser->validate($data);
        if (empty($model_errors)) {
            $newUser->fill($data)->save();
            $messages = ['success' => 'Đang ký thành công vui lòng đăng nhập bàng tài khoản cửa bạn!'];
            redirect('/admin', ['messages' => $messages]);
        }
        redirect('/admin', ['errors' => $model_errors]);
    }


    public function saveSchedule()
    {
        $data = $this->fillterSchedule($_POST);
        $doctorChedule = new DoctorWorkSchedule(PDO());
        $model_errors = $doctorChedule->validate($data);

        if (empty($model_errors)) {
            $doctorChedule->fill($data)->save();

            if (AUTHGUARD()->user()->role == 'admin') {
                $_SESSION['success'] = 'Đăng ký lịch thành công.'; // ✅ Đúng key
                redirect('/admin');
            } else {
                $_SESSION['success'] = 'Đăng ký lịch thành công.'; // ✅ Đúng key
                redirect("/doctor/schedule/" . AUTHGUARD()->user()->user_id);
            }
        }

        if (AUTHGUARD()->user()->role == 'admin') {
            $_SESSION['errors'] = $model_errors; // ✅ Ghi đúng key
            redirect('/admin');
        } else {
            $_SESSION['errors'] = $model_errors; // ✅ Ghi đúng key
            redirect("/doctor/schedule/" . AUTHGUARD()->user()->user_id);
        }
    }

    public function updateSchedule($chedileID)
    {
        $data = $this->fillterSchedule($_POST);
        $dws = new DoctorWorkSchedule(PDO());
        $model_errors = $dws->validate($data);
        if (empty($model_errors)) {
            $dwsdata = $dws->find($chedileID);
            $dwsdata->fill($data);
            $dwsdata->doctor_id = AUTHGUARD()->user()->user_id;
            $dwsdata->update();
            $_SESSION['success'] = 'Cập nhật lịch thành công.'; // ✅ Đúng key
            redirect("/doctor/schedule/" . AUTHGUARD()->user()->user_id);
        }

        $_SESSION['errors'] = $model_errors; // ✅ Ghi đúng key
        redirect('/admin');
    }

    public function adminupdateSchedule()
    {
        $data = $this->fillterSchedule($_POST);
        $dws = new DoctorWorkSchedule(PDO());
        $model_errors = $dws->validateUpdate($data);
        if (empty($model_errors)) {
            $dwsdata = $dws->find($data['id']);
            $dwsdata->fill($data);
            $dwsdata->update();
            $_SESSION['success'] = 'Đăng ký lịch thành công.'; // ✅ Đúng key
            redirect('/admin');
        }

          $_SESSION['errors'] = $model_errors; // ✅ Ghi đúng key
        redirect('/admin');
    }


    public function cancelSchedule($cheduleID)
    {
        $dws = new DoctorWorkSchedule(PDO());
        $dwsData = $dws->find($cheduleID);
        $dwsData->delete();
        redirect("/doctor/schedule/" . AUTHGUARD()->user()->user_id);
    }


    protected function e($message)
    {
        return htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    }

    protected function isCsrfTokenValid($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}