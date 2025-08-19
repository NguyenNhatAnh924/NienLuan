<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\Doctor;
use App\Models\MedicalHistoryMedicine;
use App\Models\Medicine;
use App\Models\Medicines;
use App\Models\Patient;
use App\Models\Receptionist;
use App\Models\Service;
use App\Models\Services;
use App\Models\User;
use PDO;
use Random\Engine\Secure;

class ServiceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save() {
        $data = $this->fill($_POST);
        $service = new Service(PDO());
        $service->fill($data);
        if($service->save()){
            $_SESSION['success_message'] = 'Thêm mới thành công';
            redirect('/admin');
        }else{
            $_SESSION['error_message'] = 'Thêm mới thất bại';
            redirect('/admin');
        }
    }

    public function update()
    {
        $data = $this->fill($_POST);
        $service = new Service(PDO());
        $serviceData = $service->find($data['service_id']);
        $serviceData->fill($data);
        if($serviceData->update()){
            $_SESSION['success_message'] = 'Cập nhật thành công';
            redirect('/admin');
        }else{
            $_SESSION['error_message'] = 'Cập nhật thất bại';
            redirect('/admin');
        }
        
    }


    public function fill(array $data)
    {
        return [
            'service_id' => $this->e($data['service_id'] ?? ''),
            'service_name' => $this->e($data['service_name'] ?? ''),
            'description' => $this->e($data['description'] ?? ''),
            'service_price' => $this->e($data['service_price'] ?? ''),
            'is_active' => $this->e($data['is_active'] ?? '')
        ];
    }

    protected function e($message)
    {
        return htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    }
}