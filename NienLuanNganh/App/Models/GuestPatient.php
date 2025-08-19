<?php

namespace App\Models;

use PDO;
use DateTime;
use Exception;

class GuestPatient
{
    private PDO $db;

    public int $id;
    public string $name;
    public string $phone;
    public string $address;
    public string $gender;
    public string $created_at;
    public string $updated_at;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    // Nhận dữ liệu từ form (array) để gán vào object
    public function fill(array $data): GuestPatient
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : 0;
        $this->name = $data['patient_name'] ?? '';
        $this->gender = $data['gender'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->address = $data['address'] ?? '';
        $this->created_at = $data['created_at'] ?? '';
        $this->updated_at = $data['updated_at'] ?? '';
        return $this;
    }

    // Điền dữ liệu từ db vào object
    protected function fillFromDbRow(array $row): void
    {
        $this->id = (int)$row['id'];
        $this->name = $row['name'];
        $this->gender = $row['gender'];
        $this->phone = $row['phone'];
        $this->address = $row['address'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }

    // Thêm mới
    public function save(): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO guest_patients (name, gender, phone, address) 
         VALUES (:name, :gender, :phone, :address)'
        );

        $result = $stmt->execute([
            'name' => $this->name,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'address' => $this->address
        ]);

        // Nếu chèn thành công, lưu lại ID mới
        if ($result) {
            $this->id = $this->db->lastInsertId();
        }

        return $result;
    }


    // Cập nhật thông tin
    public function update(): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE guest_patients 
             SET name = :name, gender = :gender, phone = :phone, address = :address 
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'address' => $this->address
        ]);
    }

    // Xóa
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM guest_patients WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    // Lấy khách theo ID
    public function find(int $id): ?GuestPatient
    {
        $stmt = $this->db->prepare('SELECT * FROM guest_patients WHERE id = :id');
        $stmt->execute(['id' => $id]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->fillFromDbRow($row);
            return $this;
        }

        return null;
    }

    // Lấy toàn bộ danh sách
    public function all(): array
    {
        $guests = [];
        $stmt = $this->db->query('SELECT * FROM guest_patients ORDER BY created_at DESC');

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $guest = new GuestPatient($this->db);
            $guest->fillFromDbRow($row);
            $guests[] = $guest;
        }

        return $guests;
    }

    // Kiểm tra dữ liệu đầu vào
    public function validate(array $data): array
    {
        $errors = [];

        $validPhone = preg_match('/^(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})\b$/', $data['phone'] ?? '');
        if (!$validPhone) {
            $errors['phone'] = 'Invalid phone number.';
        }
        return $errors;
    }
}