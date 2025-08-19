<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\Doctor;
use App\Models\DoctorWorkSchedule;
use App\Models\MedicalHistoryMedicine;
use App\Models\Medicine;
use App\Models\Medicines;
use App\Models\Patient;
use App\Models\Receptionist;
use App\Models\Service;
use App\Models\Services;
use App\Models\User;
use App\Models\WorkSession;
use League\Plates\Template\Func;
use League\Plates\Template\Functions;
use PDO;
use Random\Engine\Secure;
use DateTime;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $service = new Service(PDO());
        $serviceData = $service->allActive();
        $doctors = new Doctor(PDO());
        $doctorsData = $doctors->allDoctor();
        // var_dump($doctorsData); exit();
        $this->sendPage('contacts/Trangchu', [
            'contacts' => AUTHGUARD()->user() ?? [],
            'serviceData' => $serviceData,
            'doctorsData' => $doctorsData
        ]);
    }


    public function create()
    {
        if (!AUTHGUARD()->isUserLoggedIn()) {
            redirect('/login');
        }

        error_log("Create contact page is being called.");
        $this->sendPage('contacts/appointment', [
            'errors' => session_get_once('errors'),
            'doctor' => AUTHGUARD()->user() ?? []
        ]);
    }

    public function DoctorAppointments()
    {
        $appointment = new Appointment(PDO());
        $data = $appointment->getAppointmentsByDoctor(AUTHGUARD()->user()->user_id);
        $medical = new Medicines(PDO());
        $dataMedical = $medical->all();
        $service = new Service(PDO());
        $dataService = $service->allActive();
        $medicalHistory = new MedicalHistory(PDO());
        $dataHistory = $medicalHistory->all();
        $medicalHistoryMedical = new MedicalHistoryMedicine(PDO());
        $dataMedicalHistoryMedical = $medicalHistoryMedical->getByMedicalHistoryAll();
        $this->sendPage('contacts/Bacsi', [
            'appointments' => $data,
            'medicals' => $dataMedical,
            'services' => $dataService,
            'medicalHistory' => $dataHistory,
            'dataMedicalHistoryMedical' => $dataMedicalHistoryMedical
        ]);
    }

    public function ReceptionAppointments()
    {
        $appointment = new Appointment(PDO());
        $data = $appointment->getAppointments();
        $doctors = new Doctor(PDO());
        $dataDoctors = $doctors->all();
        $this->sendPage('contacts/Letan', [
            'doctors' => $dataDoctors,
            'appointments' => $data
        ]);
    }

    public function PatientAppointments($userID)
    {
        $appointment = new Appointment(PDO());
        if (AUTHGUARD()->user()->role == 'doctor') {
            $data = $appointment->getUniqueAppointmentsByDoctor($userID);
        } else {
            $data = $appointment->getAppointmentsByPatient($userID);
        }
        $doctors = new Doctor(PDO());
        $dataDoctors = $doctors->all();
        $medicalHistory = new MedicalHistory(PDO());
        $dataHistory = $medicalHistory->findByPatientId($userID);
        // var_dump($data); exit();
        $medicalHistoryMedical = new MedicalHistoryMedicine(PDO());
        $dataMedicalHistoryMedical = $medicalHistoryMedical->getByMedicalHistoryAll();
        $this->sendPage('contacts/LichHenBenhNhan', [
            'doctors' => $dataDoctors,
            'appointments' => $data,
            'medicalHistory' => $dataHistory,
            'dataMedicalHistoryMedical' => $dataMedicalHistoryMedical
        ]);
    }

    public function DoctorHistory()
    {
        $appointment = new Appointment(PDO());
        $data = $appointment->getUniqueAppointmentsByDoctor(AUTHGUARD()->user()->user_id);
        $medical = new Medicines(PDO());
        $dataMedical = $medical->all();
        $service = new Service(PDO());
        $dataService = $service->allActive();
        $medicalHistory = new MedicalHistory(PDO());
        $dataHistory = $medicalHistory->all();
        $medicalHistoryMedical = new MedicalHistoryMedicine(PDO());
        $dataMedicalHistoryMedical = $medicalHistoryMedical->getByMedicalHistoryAll();
        // var_dump($data); exit;
        $this->sendPage('contacts/LichSuKhamBenh', [
            'appointments' => $data,
            'medicals' => $dataMedical,
            'services' => $dataService,
            'medicalHistory' => $dataHistory,
            'dataMedicalHistoryMedical' => $dataMedicalHistoryMedical
        ]);
    }

    public function Profile($userID)
    {

        $user = AUTHGUARD()->doctor()->find($userID);

        // Kiểm tra sự tồn tại
        if (!$user) {
            $this->sendNotFound();
        }

        $form_values = $this->getSavedFormValues();
        $data = [
            'errors' => session_get_once('errors'),
            'user' => (!empty($form_values)) ?
                array_merge($form_values, ['id' => $user->user_id]) :
                (array) $user
        ];

        $this->sendPage('contacts/Taikhoan', $data);
    }

    public function schedule($userID)
    {
        $user = AUTHGUARD()->user()->find($userID);

        if (!$user) {
            $this->sendNotFound();
        }

        // Lấy toàn bộ lịch làm việc
        $workSchedule = new DoctorWorkSchedule(PDO());
        $doctor_WorkSchedule = $workSchedule->getAllSchedulesByDoctor($userID);
        $sessions = new WorkSession(PDO());
        $work_sessions = $sessions->all();
        // var_dump($doctor_WorkSchedule); exit();
        $this->sendPage('contacts/LichLamViec', [
            'doctor_WorkSchedule' => $doctor_WorkSchedule,
            'work_sessions' => $work_sessions
        ]);
    }


    public function Admin()
    {
        $appointment = new Appointment(PDO());
        $appointmentData = $appointment->getMonthlyAppointmentStats(2025);
        $statustData = $appointment->getAppointmentStatusStats();
        $folow_upData = $appointment->getFollowUpStats();
        $appointment_patient = $appointment->getAppointments();
        $employer = new Doctor(PDO());
        $employerData = $employer->all();
        $service = new Service(PDO());
        $serviceData = $service->all();
        $wordSession = new DoctorWorkSchedule(PDO());
        $dws = $wordSession->all();
        $sessions = new WorkSession(PDO());
        $work_sessions = $sessions->all();
        $doctor_dws = $wordSession->getDoctorWorkDaysThisMonth();
        $medicalHistory = new MedicalHistory(PDO());
        $dataHistory = $medicalHistory->all();
        $medicalHistoryMedical = new MedicalHistoryMedicine(PDO());
        $dataMedicalHistoryMedical = $medicalHistoryMedical->getByMedicalHistoryAll();
        // var_dump($dws);
        // exit();
        $this->sendPage('contacts/admin', [
            'appointmentData' => $appointmentData,
            'statustData' => $statustData,
            'folow_upData' => $folow_upData,
            'employerData' => $employerData,
            'serviceData' => $serviceData,
            'doctor_WorkSchedule' => $dws,
            'doctor_dws' => $doctor_dws,
            'work_sessions' => $work_sessions,
            'medicalHistory' => $dataHistory,
            'appointments' => $appointment_patient,
            'dataMedicalHistoryMedical' => $dataMedicalHistoryMedical
        ]);
    }

    public function Payment()
    {

        if (!AUTHGUARD()->isUserLoggedIn()) {
            $_SESSION['errors'] = 'Hảy đăng nhập trước khi đăng ký nhé!';
            redirect('/');
        }

        $appointmentData = $this->filterAppointmenttData($_POST);

        $this->sendPage('contacts/Thanhtoan', [
            'appointmentData' => $appointmentData
        ]);
    }

    protected function filterAppointmenttData(array $data)
    {
        return [
            'appointment_id' => $this->e($data['appointment_id'] ?? ''),
            'patient_type' => $this->e($data['patient_type'] ?? ''),
            'doctor_id' => $this->e($data['doctor_id'] ?? ''),  // id bác sĩ, phải là số hợp lệ
            'appointment_date' => $this->e($data['appointment_date'] ?? ''),
            'session' => $this->e($data['session'] ?? ''),      // 'Sáng' hoặc 'Chiều'
            'phone' => $this->e($data['phone'] ?? ''),
            'symptoms' => $this->e($data['symptoms'] ?? ''),    // mô tả triệu chứng
            'follow_up' => isset($data['follow_up']) && $data['follow_up'] ? 1 : 0,
            'status' => $this->e($data['status'] ?? 'pending'), // mặc định 'pending'
        ];
    }



    protected function filterHistoryData(array $data)
    {
        return [
            'patient_id'     => $this->e($data['patient_id'] ?? ''),
            'appointment_id' => $this->e($data['appointment_id']),
            'test_id'        => $this->e($data['test_id'] ?? ''),
            'diagnosis'      => $this->e($data['diagnosis'] ?? ''),
            'treatment_plan' => $this->e($data['treatment_plan'] ?? ''),
            'follow_up'      => $this->e($data['follow_up'] ?? ''),
            'notes'          => $this->e($data['notes'] ?? '')
        ];
    }





    protected function filterContactData(array $data): array
    {
        return [
            'user_id'           => $this->e($data['user_id'] ?? ''),
            'name'              => $this->e($data['name'] ?? ''),
            'email'             => $this->e($data['email'] ?? ''),
            'phone'             => $this->e($data['phone'] ?? ''),
            'address'           => $this->e($data['address'] ?? ''),
            'age'               => $this->e($data['age'] ?? ''),
            'sex'               => $this->e($data['sex'] ?? ''),
            'experience'        => $this->e($data['experience'] ?? ''),
            'level'             => $this->e($data['level'] ?? ''),
            'degree'            => $this->e($data['degree'] ?? ''),
            'salary'            => $this->e($data['salary'] ?? 0),
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

    protected function filterUserData2(array $data)
    {
        return [
            'name' => $data['namepatient_name'] ?? null,
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

        redirect('/doctor/appointments');
    }

    public function cancelAppointment($appointment_id)
    {
        $appointmentModel = new Appointment(PDO());
        if ($appointmentModel->cancel($appointment_id)) {
            $_SESSION['success_message'] = 'Cuộc hẹn đã được hủy thành công.';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }
        redirect('/patient/appointments');
    }

    public function RcancelAppointment($appointment_id)
    {
        $appointmentModel = new Appointment(PDO());
        if ($appointmentModel->cancel($appointment_id)) {
            $_SESSION['success_message'] = 'Cuộc hẹn đã được hủy thành công.';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }
        redirect('/admin/appointments');
    }

    public function RcancelAppointment1($appointment_id)
    {
        $appointmentModel = new Appointment(PDO());
        if ($appointmentModel->cancel($appointment_id)) {
            $_SESSION['success_message'] = 'Cuộc hẹn đã được hủy thành công.';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }
        redirect('/admin/appointments');
    }
}