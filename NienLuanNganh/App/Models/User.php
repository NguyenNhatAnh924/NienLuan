<?php

namespace App\Models;

use PDO;
use Exception;

class User
{
    protected PDO $db;

    public int $user_id = -1;
    public string $username;
    public string $name;
    public string $password = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';
    public string $avatar = '';
    public string $date_of_birth = '';
    public string $gender = '';
    public string $role = 'patient';
    public string $create_at;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function getID(): int
    {
        return $this->user_id;
    }

    public function findByPhone(string $phone): ?User
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE phone = :phone');
        $statement->execute(['phone' => $phone]);

        if ($row = $statement->fetch()) {
            $this->fillFromDbRow($row);
            return $this;
        }

        return null;
    }

    public function find(int $id): ?User
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $statement->execute(['id' => $id]);

        if ($row = $statement->fetch()) {
            $this->fillFromDbRow($row);
            return $this;
        }

        return null;
    }

    public function save(): bool
    {
        $result = false;

        if ($this->user_id > 0) {
            // Cập nhật
            $stmt = $this->db->prepare('SELECT email FROM users WHERE id = :id');
            $stmt->execute(['id' => $this->user_id]);
            $oldEmail = $stmt->fetchColumn();
            if ($oldEmail !== $this->email && $this->isEmailInUse($this->email, $this->user_id)) {
                throw new Exception('Email đã tồn tại.');
            }

            $query = 'UPDATE users SET name = :name, phone = :phone, email = :email, address = :address, avatar =:avatar, role = :role, gender = :gender, date_of_birth = :dob, update_at = NOW()';

            if (!empty($this->password)) {
                $query .= ', password = :password';
            }

            $query .= ' WHERE id = :id';

            $params = [
                'id' => $this->user_id,
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'avatar' => $this->avatar,
                'role' => $this->role,
                'gender' => $this->gender,
                'dob' => $this->date_of_birth,
            ];

            if (!empty($this->password)) {
                $params['password'] = $this->password;
            }

            $statement = $this->db->prepare($query);
            $result = $statement->execute($params);
        } else {
            // Thêm mới
            if ($this->isEmailInUse($this->email)) {
                throw new Exception('Email đã tồn tại.');
            }

            if ($this->isUsernameInUse($this->username)) {
                throw new Exception('Username đã tồn tại.');
            }

            $statement = $this->db->prepare(
                'INSERT INTO users (username, name, password, phone, email, address, avatar, role, gender, date_of_birth, create_at, update_at)
                 VALUES (:username, :name, :password, :phone, :email, :address, :avatar, :role, :gender, :dob, NOW(), NOW())'
            );


            $result = $statement->execute([
                'username' => $this->username,
                'name' => $this->name,
                'password' => $this->password,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'avatar' => $this->avatar,
                'gender' => $this->gender,
                'dob' => $this->date_of_birth,
                'role' => $this->role,
            ]);

            if ($result) {
                $this->user_id = $this->db->lastInsertId();
            }
        }

        return $result;
    }

    protected function fillFromDbRow(array $row): void
    {
        $this->user_id = (int)$row['id'];
        $this->username = $row['username'];
        $this->name = $row['name'];
        $this->password = $row['password'];
        $this->phone = $row['phone'] ?? '';
        $this->email = $row['email'] ?? '';
        $this->address = $row['address'] ?? '';
        $this->avatar = $row['avatar'] ?? '';
        $this->date_of_birth  = $row['date_of_birth'] ?? '';
        $this->gender  = $row['gender'] ?? '';
        $this->role = $row['role'];
        $this->create_at = $row['create_at'];
    }

    public function fill(array $data): User
    {
        if (isset($data['username'])) {
            $this->username = $data['username'];
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (!empty($data['password'])) {
            $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if (isset($data['phone'])) {
            $this->phone = $data['phone'];
        }
        if (isset($data['email'])) {
            $this->email = $data['email'];
        }
        if (isset($data['address'])) {
            $this->address = $data['address'];
        }
        if(isset($data['avatar'])){
            $this->avatar = $data['avatar'];
        }
        if (isset($data['date_of_birth'])) {
            $this->date_of_birth = $data['date_of_birth'];
        }
        if (isset($data['gender'])) {
            $this->gender = $data['gender'];
        }
        if (isset($data['role'])) {
            $this->role = $data['role'];
        }

        return $this;
    }

    public function validateUpdate(array $data): array
    {
        $errors = [];

        if ($data['experience_years'] < 0) {
            $errors['experience_years'] = 'Kinh nghiệm ít nhất là 0.';
        }

        $validPhone = preg_match('/^(03|05|07|08|09|01[2|6|8|9])([0-9]{8})$/', $data['phone'] ?? '');
        if (!$validPhone) {
            $errors['phone'] = 'Số điện thoại không hợp lệ.';
        }

        return $errors;
    }

    private function isUsernameInUse(string $username): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return $stmt->fetchColumn() > 0;
    }

    public function isEmailInUse(string $email, int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email AND id != :id');
            $stmt->execute(['email' => $email, 'id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
        }

        return $stmt->fetchColumn() > 0;
    }

    public function getUsersExcludingAdmin(): array
    {
        $users = [];
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role != 'admin' AND role != 'patient'");
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $user = new User($this->db);
            $user->fillFromDbRow($row);
            $users[] = $user;
        }

        return $users;
    }

    public function countUsersByRole(): array
    {
        $sql = "SELECT role, COUNT(*) as total FROM users GROUP BY role";
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $counts = [
            'doctor' => 0,
            'reception' => 0,
            'admin' => 0,
        ];

        foreach ($results as $row) {
            $role = $row['role'];
            $total = (int)$row['total'];
            if (isset($counts[$role])) {
                $counts[$role] = $total;
            }
        }

        return $counts;
    }

    public function where(string $column, string $value): ?User
    {
        $statement = $this->db->prepare("SELECT * FROM users WHERE $column = :value");
        $statement->execute(['value' => $value]);
        $row = $statement->fetch();

        if ($row) {
            $this->fillFromDbRow($row);
            return $this;
        }

        return null;
    }

    public function validate(array $data): array
    {
        $errors = [];

        if ($data['experience_years'] < 0) {
            $errors['experience_years'] = 'Kinh nghiệm ít nhất là 0.';
        }

        if (!$data['email']) {
            $errors['email'] = 'Invalid email.';
        } elseif ($this->isEmailInUse($data['email'])) {
            $errors['email'] = 'Email này đã  được sử dụng.';
        }

        if (strlen($data['password']) < 6) {
            $errors['password'] = 'Password Phải có ít nhất 6 ký tự.';
        } elseif ($data['password'] != $data['password_confirmation']) {
            $errors['password'] = 'Xác nhận mật khẩu không khớp.';
        }

        $validPhone = preg_match('/^(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})\b$/', $data['phone'] ?? '');
        if (!$validPhone) {
            $errors['phone'] = 'Invalid phone number.';
        }
        return $errors;
    }
    
        public function validate1(array $data): array
    {
        $errors = [];

        if (!$data['email']) {
            $errors['email'] = 'Invalid email.';
        } elseif ($this->isEmailInUse($data['email'])) {
            $errors['email'] = 'Email này đã  được sử dụng.';
        }

        if (strlen($data['password']) < 6) {
            $errors['password'] = 'Password Phải có ít nhất 6 ký tự.';
        } elseif ($data['password'] != $data['password_confirmation']) {
            $errors['password'] = 'Xác nhận mật khẩu không khớp.';
        }

        $validPhone = preg_match('/^(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})\b$/', $data['phone'] ?? '');
        if (!$validPhone) {
            $errors['phone'] = 'Invalid phone number.';
        }
        return $errors;
    }
}