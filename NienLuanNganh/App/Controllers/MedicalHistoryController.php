<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\Doctor;
use App\Models\MedicalHistoryMedicine;
use App\Models\MedicalHistoryService;
use App\Models\Patient;
use App\Models\Receptionist;
use App\Models\Services;
use App\Models\User;
use PDO;

class MedicalHistoryController extends Controller
{

    public function add()
    {
        if (!AUTHGUARD()->isUserLoggedIn()) {
            $_SESSION['errors'] = 'Hãy đăng nhập trước khi đăng ký nhé!';
            redirect('/');
        }

        // Bước 1: Lọc dữ liệu từ form
        $dataHistory           = $this->filterHistory($_POST);
        $dataHistoryMedical    = $this->filterMedicalHistoryMedicines($_POST);
        $dataHistoryServices   = $this->filterMedicalHistoryServices($_POST);

        // Bước 2: Khởi tạo đối tượng bệnh án và gán dữ liệu
        $newHistory = new MedicalHistory(PDO());
        $newHistory->fill($dataHistory);

        // Bước 3: Lưu bệnh án tùy theo loại bệnh nhân
        $saved = $dataHistory['patient_type'] === 'user'
            ? $newHistory->save()
            : $newHistory->saveForGuest();

        if (!$saved) {
            $_SESSION['errors'] = 'Không thể lưu bệnh án. Vui lòng thử lại.';
            $this->saveFormValues($_POST);
            redirect('/doctor/appointments');
        }

        // Bước 4: Lưu thuốc và dịch vụ
        $allSaved = true;

        foreach ($dataHistoryMedical as $item) {
            $medicine = new MedicalHistoryMedicine(PDO());
            $medicine->medical_history_id = $newHistory->id;
            $medicine->fill($item);

            if (!$medicine->save()) {
                $allSaved = false;
                break;
            }
        }

        if ($allSaved) {
            foreach ($dataHistoryServices as $item) {
                $service = new MedicalHistoryService(PDO());
                $service->medical_history_id = $newHistory->id;
                $service->fill($item);

                if (!$service->save()) {
                    $allSaved = false;
                    break;
                }
            }
        }

        // Bước 5: Xử lý kết quả
        if ($allSaved) {
            $_SESSION['success_message'] = 'Đã thêm bệnh án thành công';
        } else {
            $_SESSION['errors'] = 'Không thể lưu thuốc hoặc dịch vụ. Vui lòng thử lại.';
            $this->saveFormValues($_POST);
        }

        // Bước 6: Điều hướng
        redirect('/doctor/appointments');
    }


    protected function filterHistory(array $data)
    {
        return [
            'appointment_id' => $this->e($data['appointment_id']),
            'doctor_id' => $this->e($data['doctor_id'] ?? ''),
            'patient_id'     => $this->e($data['patient_id'] ?? ''),
            'guest_id'  => $this->e($data['guest_id'] ?? ''),
            'patient_type'  => $this->e($data['patient_type'] ?? ''),
            'diagnosis'        => $this->e($data['diagnosis'] ?? ''),
            'treatment_plan' => $this->e($data['treatment_plan'] ?? ''),
            'notes'          => $this->e($data['notes'] ?? '')
        ];
    }

    protected function filterMedicalHistoryMedicines(array $data): array
    {
        $medicines = [];

        if (!empty($data['medicines']) && is_array($data['medicines'])) {
            foreach ($data['medicines'] as $medicine) {
                $medicine_id = (int)($medicine['medicine_id'] ?? 0);
                if ($medicine_id > 0) {
                    $medicines[] = [
                        'medicine_id' => $medicine_id,
                        'quantity' => (int)($medicine['quantity'] ?? 0),
                        'dosage' => $this->e($medicine['dosage'] ?? ''),
                        'notes' => $this->e($medicine['usage'] ?? ''),
                    ];
                }
            }
        }

        return $medicines;
    }

    protected function filterMedicalHistoryServices(array $data): array
    {
        $services = [];

        if (!empty($data['services']) && is_array($data['services'])) {
            foreach ($data['services'] as $service) {
                $service_id = (int)($service['id'] ?? 0);
                if ($service_id > 0) {
                    $services[] = [
                        'service_id' => $service_id,
                        'quantity' => (int)($service['quantity'] ?? 1),
                        'notes' => $this->e($service['note'] ?? ''),
                    ];
                }
            }
        }

        return $services;
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