<?php

namespace App\Models;

use PDO;

class Patient extends User
{
    public string $medical_history;        // Lịch sử bệnh
    public string $date_of_birth;          // Ngày sinh
    public string $blood_type;             // Nhóm máu
    public string $insurance_number;       // Số bảo hiểm
    public string $allergies;              // Dị ứng
    public string $emergency_contact_name; // Tên người liên hệ khẩn cấp
    public string $emergency_contact_phone; // Số điện thoại người liên hệ khẩn cấp
    public string $created_at;             // Ngày tạo thông tin bệnh nhân

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);  
    }

    public function save(): bool
    {
        $result = parent::save(); // Lưu hoặc cập nhật thông tin người dùng trong bảng users
    
        if ($result) {
            // Kiểm tra xem bệnh nhân đã có trong bảng patient chưa
            $statement = $this->db->prepare(
                'SELECT COUNT(*) FROM patient WHERE user_id = :user_id'
            );
            $statement->execute(['user_id' => $this->user_id]);
    
            if ($statement->fetchColumn() == 0) {
                // Nếu chưa tồn tại, thêm mới
                $query = 'INSERT INTO patient (user_id, medical_history, date_of_birth, blood_type, 
                          insurance_number, allergies, emergency_contact_name, emergency_contact_phone) 
                          VALUES (:user_id, :medical_history, :date_of_birth, :blood_type, 
                          :insurance_number, :allergies, :emergency_contact_name, :emergency_contact_phone)';
            } else {
                // Nếu đã tồn tại, cập nhật thông tin
                $query = 'UPDATE patient 
                          SET medical_history = :medical_history, date_of_birth = :date_of_birth, 
                          blood_type = :blood_type, insurance_number = :insurance_number, allergies = :allergies, 
                          emergency_contact_name = :emergency_contact_name, emergency_contact_phone = :emergency_contact_phone 
                          WHERE user_id = :user_id';
            }
    
            // Thực thi truy vấn (INSERT hoặc UPDATE)
            $statement = $this->db->prepare($query);
            $result = $statement->execute([
                'user_id' => $this->user_id,
                'medical_history' => $this->medical_history,
                'date_of_birth' => $this->date_of_birth,
                'blood_type' => $this->blood_type,
                'insurance_number' => $this->insurance_number,
                'allergies' => $this->allergies,
                'emergency_contact_name' => $this->emergency_contact_name,
                'emergency_contact_phone' => $this->emergency_contact_phone
            ]);
        }
    
        return $result;
    }
    

    // Tìm thông tin bệnh nhân theo patient_id
    public function find(int $id): ?Patient
    {
        $statement = $this->db->prepare(
            'SELECT u.*, p.medical_history, p.date_of_birth, p.blood_type, p.insurance_number, 
            p.allergies, p.emergency_contact_name, p.emergency_contact_phone
             FROM users u
             LEFT JOIN patient p ON u.id = p.user_id
             WHERE p.user_id = :id'
        );
        $statement->execute(['id' => $id]);

        if ($row = $statement->fetch()) {
            $this->fillFromDbRow($row);
            return $this;
        }

        return null;
    }
    

    // Điền thông tin vào đối tượng Patient từ kết quả truy vấn DB
    protected function fillFromDbRow(array $row): Patient
    {
        parent::fillFromDbRow($row);  // Điền thông tin người dùng từ lớp cha
        $this->medical_history = $row['medical_history'];
        $this->date_of_birth = $row['date_of_birth'];
        $this->blood_type = $row['blood_type'];
        $this->insurance_number = $row['insurance_number'];
        $this->allergies = $row['allergies'];
        $this->emergency_contact_name = $row['emergency_contact_name'];
        $this->emergency_contact_phone = $row['emergency_contact_phone'];
        $this->created_at = $row['created_at'];

        return $this;
    }

 
    // Xóa bệnh nhân khỏi cơ sở dữ liệu
    public function delete(): bool
    {
        $statement = $this->db->prepare('DELETE FROM patient WHERE user_id = :user_id');
        return $statement->execute(['user_id' => $this->user_id]);  // Dùng user_id thay vì id
    }

    // Điền thông tin bệnh nhân vào đối tượng từ dữ liệu mảng
    public function fill(array $data): Patient
    {
        parent::fill($data);  // Điền thông tin người dùng từ lớp cha

        // Điền thông tin đặc thù của bệnh nhân
        $this->medical_history = $data['medical_history'] ?? '';
        $this->date_of_birth = $data['date_of_birth'] ?? '';
        $this->blood_type = $data['blood_type'] ?? '';
        $this->insurance_number = $data['insurance_number'] ?? '';
        $this->allergies = $data['allergies'] ?? '';
        $this->emergency_contact_name = $data['emergency_contact_name'] ?? '';
        $this->emergency_contact_phone = $data['emergency_contact_phone'] ?? '';

        return $this;
    }

    // Kiểm tra xem email đã tồn tại hay chưa (có thể sử dụng cho việc tạo mới bệnh nhân)
    public function isEmailInUse(string $email): bool
    {
        $statement = $this->db->prepare('SELECT count(*) FROM users WHERE email = :email');
        $statement->execute(['email' => $email]);
        return $statement->fetchColumn() > 0;
    }
}