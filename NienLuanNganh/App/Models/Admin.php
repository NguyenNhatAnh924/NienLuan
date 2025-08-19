<?php

namespace App\Models;

use PDO;

class Admin extends User 
{
    public string $position;         // Vị trí quản lý
    public float $experience;        // Kinh nghiệm
    public string $sex;              // Giới tính
    public string $level;            // Cấp bậc quản lý
    public string $degree;           // Bằng cấp
    public float $salary;            // Lương
    public string $employment_status; // Trạng thái làm việc

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);  
    }

    // Lưu thông tin admin vào bảng users và admin
    public function save(): bool
    {
        $result = false;
       if(parent::save()){
        $statement = $this->db->prepare(
            'SELECT count(*) FROM admin WHERE user_id = :user_id'
        );
        $statement->execute(['user_id' => $this->user_id]);
        $count = $statement->fetchColumn(); // <-- thêm dòng này
        
        if ($count == 0) { // <-- sửa lại điều kiện
            // Nếu chưa có, thêm vào bảng admin
            $statement = $this->db->prepare(
                'INSERT INTO admin (user_id, position, experience, sex, level, degree, salary, employment_status)
                 VALUES (:user_id, :position, :experience, :sex, :level, :degree, :salary, :employment_status)'
            );
            $result = $statement->execute([
                'user_id' => $this->user_id,
                'position' => $this->position,
                'experience' => $this->experience,
                'sex' => $this->sex,
                'level' => $this->level,
                'degree' => $this->degree,
                'salary' => $this->salary,
                'employment_status' => $this->employment_status,
            ]);
        } else {
            // Nếu đã có admin, thực hiện update
            $statement = $this->db->prepare(
                'UPDATE admin 
                 SET position = :position, experience = :experience, sex = :sex, 
                     level = :level, degree = :degree, salary = :salary, 
                     employment_status = :employment_status
                 WHERE user_id = :user_id'
            );
            $result = $statement->execute([
                'user_id' => $this->user_id,
                'position' => $this->position,
                'experience' => $this->experience,
                'sex' => $this->sex,
                'level' => $this->level,
                'degree' => $this->degree,
                'salary' => $this->salary,
                'employment_status' => $this->employment_status,
            ]);
        }
       }
        
    
        return $result;
    }
      
    // Tìm thông tin admin theo id
    public function find(int $id): ?Admin
    {
        $statement = $this->db->prepare(
            'SELECT u.*, 
                    a.position AS admin_position, 
                    a.experience AS admin_experience, 
                    a.sex AS admin_sex, 
                    a.level AS admin_level, 
                    a.degree AS admin_degree, 
                    a.salary AS admin_salary, 
                    a.employment_status AS admin_status
             FROM users u
             LEFT JOIN admin a ON u.id = a.user_id
             WHERE a.user_id = :id'
        );
        $statement->execute(['id' => $id]);
    
        if ($row = $statement->fetch()) {
            $this->fillFromDbRow($row);
            return $this;
        }
    
        return null;
    }
    

    // Điền thông tin admin từ dòng dữ liệu DB
    protected function fillFromDbRow(array $row): Admin
    {
        parent::fillFromDbRow($row);
        $this->position = $row['admin_position'] ?? '';
        $this->experience = $row['admin_experience'] ?? 0; // <-- sửa ở đây
        $this->sex = $row['admin_sex'] ?? '';
        $this->level = $row['admin_level'] ?? '';
        $this->degree = $row['admin_degree'] ?? '';
        $this->salary = $row['admin_salary'] ?? 0;
        $this->employment_status = $row['admin_status'] ?? '';
        return $this;
    }
    

    // Xóa admin khỏi cơ sở dữ liệu
    public function delete(): bool
    {
        $statement = $this->db->prepare('DELETE FROM admin WHERE user_id = :user_id');
        return $statement->execute(['user_id' => $this->user_id]);
    }

    // Điền thông tin từ dữ liệu mảng
    public function fill(array $data): Admin
    {
        parent::fill($data);

        $this->position = $data['position'] ?? '';
        $this->experience = $data['experience'] ?? 0;
        $this->sex = $data['sex'] ?? '';
        $this->level = $data['level'] ?? '';
        $this->degree = $data['degree'] ?? '';
        $this->salary = $data['salary'] ?? 0;
        $this->employment_status = $data['employment_status'] ?? '';

        return $this;
    }

    // Kiểm tra email đã tồn tại chưa
    public function isEmailInUse(string $email): bool
    {
        $statement = $this->db->prepare('SELECT count(*) FROM users WHERE email = :email');
        $statement->execute(['email' => $email]);
        return $statement->fetchColumn() > 0;
    }
}