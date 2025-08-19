<?php

namespace App\Models;

use PDO;

class Receptionist extends User
{
    public string $department;         // Bộ phận làm việc
    public string $shift_time;          // Ca trực
    public string $degree;              // Bằng cấp
    public string $level;               // Trình độ
    public string $sex;                 // Giới tính
    public float $salary;               // Lương
    public string $employment_status;   // Trạng thái làm việc

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    public function save(): bool
    {
        $result = false;

        if (parent::save()) {
            $statement = $this->db->prepare(
                'SELECT count(*) FROM receptionist WHERE user_id = :user_id'
            );
            $statement->execute(['user_id' => $this->user_id]);

            if ($statement->fetchColumn() == 0) {
                $statement = $this->db->prepare(
                    'INSERT INTO receptionist (user_id, department, shift_time, degree, level, sex, salary, employment_status)
                     VALUES (:user_id, :department, :shift_time, :degree, :level, :sex, :salary, :employment_status)'
                );
                $result = $statement->execute([
                    'user_id' => $this->user_id,
                    'department' => $this->department,
                    'shift_time' => $this->shift_time,
                    'degree' => $this->degree,
                    'level' => $this->level,
                    'sex' => $this->sex,
                    'salary' => $this->salary,
                    'employment_status' => $this->employment_status,
                ]);
            } else {
                $statement = $this->db->prepare(
                    'UPDATE receptionist
                     SET department = :department,
                         shift_time = :shift_time,
                         degree = :degree,
                         level = :level,
                         sex = :sex,
                         salary = :salary,
                         employment_status = :employment_status
                     WHERE user_id = :user_id'
                );
                $result = $statement->execute([
                    'user_id' => $this->user_id,
                    'department' => $this->department,
                    'shift_time' => $this->shift_time,
                    'degree' => $this->degree,
                    'level' => $this->level,
                    'sex' => $this->sex,
                    'salary' => $this->salary,
                    'employment_status' => $this->employment_status,
                ]);
            }
        }

        return $result;
    }

    public function find(int $id): ?Receptionist
    {
        $statement = $this->db->prepare(
            'SELECT u.*, 
                    r.department, 
                    r.shift_time,
                    r.degree AS receptionist_degree,
                    r.level AS receptionist_level,
                    r.sex AS receptionist_sex,
                    r.salary AS receptionist_salary, 
                    r.employment_status AS receptionist_status
             FROM users u
             LEFT JOIN receptionist r ON u.id = r.user_id
             WHERE r.user_id = :id'
        );
        $statement->execute(['id' => $id]);
    
        if ($row = $statement->fetch()) {
            $this->fillFromDbRow($row);
            return $this;
        }
    
        return null;
    }
    

    protected function fillFromDbRow(array $row): Receptionist
    {
        parent::fillFromDbRow($row);
        $this->department = $row['department'] ?? '';
        $this->shift_time = $row['shift_time'] ?? '';
        $this->sex = $row['receptionist_sex'] ?? '';
        $this->level = $row['receptionist_level'] ?? '';
        $this->degree = $row['receptionist_degree'] ?? '';        
        $this->salary = $row['receptionist_salary'] ?? 0;
        $this->employment_status = $row['receptionist_status'] ?? '';
        return $this;
    }

    public function delete(): bool
    {
        $statement = $this->db->prepare('DELETE FROM receptionist WHERE user_id = :user_id');
        return $statement->execute(['user_id' => $this->user_id]);
    }

    public function fill(array $data): Receptionist
    {
        parent::fill($data);

        $this->department = $data['department'] ?? '';
        $this->shift_time = $data['shift_time'] ?? '';
        $this->degree = $data['degree'] ?? '';
        $this->level = $data['level'] ?? '';
        $this->sex = $data['sex'] ?? '';
        $this->salary = $data['salary'] ?? 0;
        $this->employment_status = $data['employment_status'] ?? '';

        return $this;
    }

    public function isEmailInUse(string $email): bool
    {
        $statement = $this->db->prepare('SELECT count(*) FROM users WHERE email = :email');
        $statement->execute(['email' => $email]);
        return $statement->fetchColumn() > 0;
    }
}