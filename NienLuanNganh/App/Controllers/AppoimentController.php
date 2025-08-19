<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\Doctor;
use App\Models\GuestPatient;
use App\Models\Patient;
use App\Models\Receptionist;
use App\Models\Services;
use App\Models\User;
use PDO;

class AppoimentController extends Controller
{

    public function store()
    {

        if (!AUTHGUARD()->isUserLoggedIn()) {
            $_SESSION['errors'] = 'Hảy đăng nhập trước khi đăng ký nhé!';
            redirect('/');
        }
        // Bước 1: Lọc dữ liệu từ form
        $data = $this->filterAppointmenttData($_POST);

        // Bước 2: Khởi tạo đối tượng Appointment và gán DB
        $newAppointment = new Appointment(PDO());

        // Bước 3: Gán thông tin bệnh nhân hiện tại (từ session / auth)
        $user = AUTHGUARD()->user();
        $newAppointment->setUser($user);

        // Bước 4: Gán dữ liệu và kiểm tra hợp lệ
        try {
            $newAppointment->fill($data)->validate(); // Hàm validate bạn phải định nghĩa trong class Appointment
        } catch (\Exception $e) {
            // Bước 5: Ghi nhận lỗi và quay về form
            $_SESSION['errors'] = [$e->getMessage()];
            $this->saveFormValues($_POST);
            redirect('/');
        }

        // Bước 6: Kiểm tra cuộc hẹn trùng lặp trước khi lưu (rất quan trọng)
        if ($newAppointment->isAppointmentExists(
            $newAppointment->patient_id,
            $newAppointment->doctor_id,
            $newAppointment->appointment_date,
            $newAppointment->session
        )) {
            $_SESSION['errors'] = ['Cuộc hẹn đã tồn tại với thông tin tương tự.'];
            $this->saveFormValues($_POST);
            redirect('/');
        }

        // Bước 7: Lưu vào CSDL
        if ($newAppointment->save()) {
            $_SESSION['success_message'] = 'Đăng ký thành công';
        } else {
            $_SESSION['errors'] = 'Không thể lưu cuộc hẹn. Vui lòng thử lại.';
            $this->saveFormValues($_POST);
            redirect('/');
        }

        // Bước 8: Điều hướng sau khi thành công
        redirect('/');
    }

    public function updateAppoiment()
    {
        $data = $this->filterAppointmenttData($_POST);
        $appoiment = new Appointment(PDO());
        $dataAppoiment = $appoiment->find($data['appointment_id']);

        $dataAppoiment->symptoms = $data['symptoms'];
        $dataAppoiment->session = $data['session'];
        $dataAppoiment->appointment_date = $data['appointment_date'];
        $dataAppoiment->phone = $data['phone'];
        $dataAppoiment->doctor_id = $data['doctor_id'];

        $model_errors = $dataAppoiment->validateUpdate();

        if (empty($model_errors)) {
            if ($dataAppoiment->update()) {
                $_SESSION['success_message'] = 'Sửa thành công';
            } else {
                $_SESSION['error_message'] = 'Cập nhật thất bại. Vui lòng thử lại.';
            }
        }

        // Redirect hợp nhất, tránh trùng lặp
        if (AUTHGUARD()->user()->role === 'reception') {
            redirect('/reception/appointments', ['errors' => $model_errors]);
        } else {
            redirect('/patient/appointments/' . AUTHGUARD()->user()->user_id, ['errors' => $model_errors]);
        }
    }


    public function ReceptionStore()
    {

        if (!AUTHGUARD()->isUserLoggedIn()) {
            $_SESSION['errors'] = 'Hảy đăng nhập trước khi đăng ký nhé!';
            redirect('/');
        }

        // Bước 1: Lọc dữ liệu từ form
        $data = $this->filterReceptionAppointmenttData($_POST);

        $newGuest = new GuestPatient(PDO());

        $model_errors = $newGuest->validate($data);

        if (empty($model_errors)) {
            $newGuest->fill($data)->save();
        } else {
            redirect('/reception/appointments', ['errors' => $model_errors]);
        }
        // Bước 2: Khởi tạo đối tượng Appointment và gán DB
        $newAppointment = new Appointment(PDO());

        // Bước 3: Gán thông tin bệnh nhân hiện tại (từ session / auth)
        $newAppointment->fill($data);
        $newAppointment->guest_id = $newGuest->id;

        // Bước 4: Gán dữ liệu và kiểm tra hợp lệ
        try {
            $newAppointment->validate(); // Hàm validate bạn phải định nghĩa trong class Appointment
        } catch (\Exception $e) {
            // Bước 5: Ghi nhận lỗi và quay về form
            $_SESSION['errors'] = [$e->getMessage()];
            $this->saveFormValues($_POST);
            redirect('/reception/appointments');
        }

        // Bước 6: Kiểm tra cuộc hẹn trùng lặp trước khi lưu (rất quan trọng)
        if ($newAppointment->isAppointmentExists(
            $newAppointment->patient_id,
            $newAppointment->doctor_id,
            $newAppointment->appointment_date,
            $newAppointment->session
        )) {
            $_SESSION['errors'] = 'Cuộc hẹn đã tồn tại với thông tin tương tự.';
            $this->saveFormValues($_POST);
            redirect('/reception/appointments');
        }

        // Bước 7: Lưu vào CSDL
        if ($newAppointment->Receptionsave()) {
            $_SESSION['success_message'] = 'Đăng ký thành công';
        } else {
            $_SESSION['errors'] = 'Không thể lưu cuộc hẹn. Vui lòng thử lại.';
            $this->saveFormValues($_POST);
            redirect('/reception/appointments');
        }

        // Bước 8: Điều hướng sau khi thành công
        redirect('/reception/appointments');
    }


    protected function filterAppointmenttData(array $data)
    {
        return [
            
            'appointment_id' => $this->e($data['appointment_id'] ?? ''),
            'patient_type' => $this->e($data['patient_type'] ?? ''),
            'doctor_id' => $this->e($data['doctor_id'] ?? ''), // id bác sĩ, phải là số hợp lệ
            'appointment_date' => $this->e($data['appointment_date'] ?? ''),
            'session' => $this->e($data['session'] ?? ''), // 'Sáng' hoặc 'Chiều'
            'phone' => $this->e($data['phone'] ?? ''),
            'symptoms' => $this->e($data['symptoms'] ?? ''), // mô tả triệu chứng
            'follow_up' => isset($data['follow_up']) && $data['follow_up'] ? 1 : 0,
            'status' => $this->e($data['status'] ?? 'pending'), // mặc định 'pending'
        ];
    }

    protected function filterReceptionAppointmenttData(array $data)
    {
        return [
            'patient_type' => $this->e($data['patient_type'] ?? 'user'), // mặc định là 'user'
            'patient_name' => $this->e($data['patient_name'] ?? ''),
            'phone' => $this->e($data['phone'] ?? ''),
            'address' => $this->e($data['address'] ?? ''),
            'gender' => $this->e($data['gender'] ?? ''),
            'insurance_number' => $this->e($data['insurance_number'] ?? ''),
            'doctor_id' => $this->e($data['doctor_id'] ?? ''),
            'appointment_date' => $this->e($data['appointment_date'] ?? ''),
            'session' => $this->e($data['session'] ?? ''),
            'symptoms' => $this->e($data['symptoms'] ?? ''),
            'follow_up' => isset($data['follow_up']) && $data['follow_up'] ? 1 : 0,
            'status' => $this->e($data['status'] ?? 'pending'), // default
        ];
    }


    protected function filterContactData(array $data): array
    {
        return [
            'user_id' => $this->e($data['user_id'] ?? ''),
            'name' => $this->e($data['name'] ?? ''),
            'email' => $this->e($data['email'] ?? ''),
            'phone' => $this->e($data['phone'] ?? ''),
            'address' => $this->e($data['address'] ?? ''),
            'age' => $this->e($data['age'] ?? ''),
            'sex' => $this->e($data['sex'] ?? ''),
            'experience' => $this->e($data['experience'] ?? ''),
            'level' => $this->e($data['level'] ?? ''),
            'degree' => $this->e($data['degree'] ?? ''),
            'salary' => $this->e($data['salary'] ?? 0),
            'employment_status' => $this->e($data['employmentStatus'] ?? ''),
        ];
    }


    protected function filterPatientData(array $data)
    {
        return [
            'email' => $this->e($data['email'] ?? ''),
            'name' => $this->e($data['name'] ?? ''),
            'phone' => $this->e($data['phone'] ?? ''),
            'address' => $this->e($data['address'] ?? ''),
            'role' => $this->e($data['role']),
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



    protected function e($message)
    {
        return htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    }

    protected function isCsrfTokenValid($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public function completed_appointment($appointmentID)
    {
        $appointmentModel = new Appointment(PDO());
        if ($appointmentModel->complete($appointmentID)) {
            $_SESSION['success_message'] = 'Đã hoàn tất cuộc hẹn';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }

        $role = AUTHGUARD()->user()->role;

        if ($role === 'reception') {
            redirect('/reception/appointments');
        } elseif ($role === 'patient') {
            redirect('/patient/appointments/' . AUTHGUARD()->user()->user_id);
        } elseif ($role === 'doctor') {
            redirect('/doctor/appointments');
        } else {
            // Trường hợp không xác định, chuyển về trang chủ hoặc trang báo lỗi
            redirect('/');
        }
    }

    public function cancaled_appointment($appointment_id)
    {
        $appointmentModel = new Appointment(PDO());
        if ($appointmentModel->cancel($appointment_id)) {
            $_SESSION['success_message'] = 'Cuộc hẹn đã được hủy thành công.';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }
        $role = AUTHGUARD()->user()->role;

        if ($role === 'reception') {
            redirect('/reception/appointments');
        } elseif ($role === 'patient') {
            redirect('/patient/appointments/' . AUTHGUARD()->user()->user_id);
        } elseif ($role === 'doctor') {
            redirect('/doctor/appointments');
        } else {
            // Trường hợp không xác định, chuyển về trang chủ hoặc trang báo lỗi
            redirect('/');
        }
    }


    public function restore_appointment($appointment_id)
    {
        $appointmentModel = new Appointment(PDO());
        if ($appointmentModel->restore($appointment_id)) {
            $_SESSION['success_message'] = 'Cuộc hẹn đã được khôi phục thành công.';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }
        $role = AUTHGUARD()->user()->role;

        if ($role === 'reception') {
            redirect('/reception/appointments');
        } elseif ($role === 'patient') {
            redirect('/patient/appointments/' . AUTHGUARD()->user()->user_id);
        } elseif ($role === 'doctor') {
            redirect('/doctor/appointments');
        } else {
            // Trường hợp không xác định, chuyển về trang chủ hoặc trang báo lỗi
            redirect('/');
        }
    }
}