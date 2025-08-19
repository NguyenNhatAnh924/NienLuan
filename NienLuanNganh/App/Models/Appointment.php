<?php

namespace App\Models;

use PDO;
use DateTime;
use Exception;

class Appointment
{
    private PDO $db;

    public int $appointment_id;
    public ?int $patient_id = null;
    public ?int $guest_id = null;
    public ?int $doctor_id = null;
    public ?string $patient_address = null;
    public string $patient_name;       // Tên bệnh nhân (lấy từ users)
    public string $guest_name;
    public string $doctor_name;
    public string $appointment_date;   // Ngày hẹn khám (Y-m-d)
    public string $session;            // Buổi khám (Sáng, Chiều)
    public bool $follow_up;            // Tái khám
    public string $status;             // pending, confirmed, completed, cancelled
    public ?string $phone = null;
    public string $symptoms;           // Mô tả triệu chứng
    public string $created_at;
    public string $updated_at;
    public string $patient_type;


    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    // Thiết lập bệnh nhân cho cuộc hẹn
    public function setUser(User $user): Appointment
    {
        $this->patient_id = $user->user_id;
        return $this;
    }

    public function ReciptionsetUser(GuestPatient $guest): Appointment
    {
        $this->guest_id = $guest->id;
        return $this;
    }

    // Lấy 1 bác sĩ ngẫu nhiên
    public function getRandomDoctorId(): ?int
    {
        $statement = $this->db->query('SELECT doctor_id 
        FROM doctors d
        JOIN users u ON u.id = d.doctor_id
        WHERE u.role = "doctor"
        ORDER BY RAND() LIMIT 1');
        $doctor = $statement->fetch(PDO::FETCH_ASSOC);
        return $doctor ? (int)$doctor['doctor_id'] : null;
    }

    // Lưu cuộc hẹn vào CSDL
    public function save(): bool
    {
        if ($this->doctor_id <= 0) {
            $this->doctor_id = $this->getRandomDoctorId();
            if (!$this->doctor_id) {
                throw new Exception("Không có bác sĩ nào trong hệ thống.");
            }
        }

        $statement = $this->db->prepare(
            'INSERT INTO appointments (patient_id, doctor_id, appointment_date, session, follow_up, status, phone, symptoms)
             VALUES (:patient_id, :doctor_id, :appointment_date, :session, :follow_up, :status, :phone, :symptoms)'
        );

        return $statement->execute([
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'appointment_date' => $this->appointment_date,
            'session' => $this->session,
            'follow_up' => $this->follow_up ? 1 : 0,
            'status' => $this->status,
            'phone' => $this->phone,
            'symptoms' => $this->symptoms
        ]);
    }


    public function Receptionsave(): bool
    {
        // 1. Chọn ngẫu nhiên bác sĩ nếu chưa có
        if (empty($this->doctor_id)) {
            $this->doctor_id = $this->getRandomDoctorId();
            if (!$this->doctor_id) {
                throw new Exception("Không có bác sĩ nào trong hệ thống.");
            }
        }

        // 2. Bắt buộc phải có 1 trong 2: patient_id hoặc guest_id
        if (empty($this->patient_id) && empty($this->guest_id)) {
            throw new Exception("Cần có patient_id hoặc guest_id.");
        }

        // 3. Chuẩn bị dữ liệu chung
        $params = [
            'doctor_id' => $this->doctor_id,
            'appointment_date' => $this->appointment_date,
            'session' => $this->session,
            'follow_up' => $this->follow_up ? 1 : 0,
            'status' => $this->status,
            'phone' => $this->phone,
            'symptoms' => is_array($this->symptoms) ? implode(', ', $this->symptoms) : $this->symptoms,
            'patient_type' => $this->patient_id ? 'user' : 'guest',
        ];

        // 4. Xác định cột và giá trị cần thêm tuỳ vào loại bệnh nhân
        $columns = ['doctor_id', 'appointment_date', 'session', 'follow_up', 'status', 'phone', 'symptoms', 'patient_type'];
        $placeholders = [':doctor_id', ':appointment_date', ':session', ':follow_up', ':status', ':phone', ':symptoms', ':patient_type'];

        if (!empty($this->patient_id)) {
            $columns[] = 'patient_id';
            $placeholders[] = ':patient_id';
            $params['patient_id'] = $this->patient_id;
        } else {
            $columns[] = 'guest_id';
            $placeholders[] = ':guest_id';
            $params['guest_id'] = $this->guest_id;
        }

        // 5. Gộp cột và placeholder thành chuỗi SQL
        $sql = sprintf(
            "INSERT INTO appointments (%s) VALUES (%s)",
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        // 6. Thực thi
        $statement = $this->db->prepare($sql);
        return $statement->execute($params);
    }


    public function find(int $appointment_id): ?Appointment
    {
        $statement = $this->db->prepare(
            'SELECT 
            a.*, 
            CASE 
                WHEN a.patient_type = "user" THEN u.name
                WHEN a.patient_type = "guest" THEN g.name
                ELSE NULL
            END AS patient_name,
            CASE 
                WHEN a.patient_type = "user" THEN u.phone
                WHEN a.patient_type = "guest" THEN g.phone
                ELSE NULL
            END AS phone,
            duser.name AS doctor_name
        FROM appointments a
        LEFT JOIN users u ON a.patient_type = "user" AND a.patient_id = u.id
        LEFT JOIN guest_patients g ON a.patient_type = "guest" AND a.guest_id = g.id
        LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
        LEFT JOIN users duser ON d.doctor_id = duser.id
        WHERE a.id = :appointment_id'
        );

        $statement->execute(['appointment_id' => $appointment_id]);

        if ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $this->fillFromDbRow($row);
            return $this;
        }

        return null;
    }


    // Cập nhật cuộc hẹn
    public function update(): bool
    {
        if ($this->doctor_id == 0) {
            $this->doctor_id = $this->getRandomDoctorId();
            if (!$this->doctor_id) {
                throw new Exception("Không có bác sĩ nào trong hệ thống.");
            }
        }
        $statement = $this->db->prepare(
            'UPDATE appointments SET
            doctor_id = :doctor_id,
            phone = :phone,
            appointment_date = :appointment_date,
            session = :session,
            symptoms = :symptoms
         WHERE id = :appointment_id'
        );

        return $statement->execute([
            'appointment_id'   => $this->appointment_id,
            'doctor_id'        => $this->doctor_id,
            'phone'            => $this->phone,
            'appointment_date' => $this->appointment_date,
            'session'          => $this->session,
            'symptoms'         => $this->symptoms,
        ]);
    }


    // Đánh dấu cuộc hẹn hoàn thành
    public function complete(int $appointment_id): bool
    {
        $statement = $this->db->prepare('UPDATE appointments SET status = :status WHERE id = :appointment_id');
        return $statement->execute([
            'status' => 'completed',
            'appointment_id' => $appointment_id,
        ]);
    }

    // Hủy cuộc hẹn
    public function cancel(int $appointment_id): bool
    {
        $statement = $this->db->prepare('UPDATE appointments SET status = :status WHERE id = :appointment_id');
        return $statement->execute([
            'status' => 'cancelled',
            'appointment_id' => $appointment_id,
        ]);
    }

    // Phục hồi cuộc hẹn sang trạng thái pending
    public function restore(int $appointment_id): bool
    {
        $statement = $this->db->prepare('UPDATE appointments SET status = :status WHERE id = :appointment_id');
        return $statement->execute([
            'status' => 'pending',
            'appointment_id' => $appointment_id,
        ]);
    }

    // Điền dữ liệu từ db vào object
    protected function fillFromDbRow(array $row): void
    {
        $this->appointment_id = (int)$row['id'];
        $this->patient_id = (int)$row['patient_id'];
        $this->doctor_id = (int)$row['doctor_id'];

        $this->guest_id         = (int)$row['guest_id'];
        $this->patient_type     = $row['patient_type'] ?? 'user'; // 'user' hoặc 'guest'
        $this->guest_name = $row['guest_name'] ?? 'Unknown';

        $this->patient_address = $row['patient_address'] ?? '';
        $this->patient_name = $row['patient_name'] ?? 'Unknown';
        $this->doctor_name = $row['doctor_name'] ?? 'Unknown';
        $this->appointment_date = $row['appointment_date'];
        $this->session = $row['session'];
        $this->follow_up = (bool)$row['follow_up'];
        $this->status = $row['status'];
        $this->phone = $row['phone'];
        $this->symptoms = $row['symptoms'] ?? '';
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }

    // Nhận dữ liệu từ form (array) để gán vào object
    public function fill(array $data): Appointment
    {

        $this->doctor_id = isset($data['doctor_id']) ? (int)$data['doctor_id'] : 0;
        $this->appointment_date = $data['appointment_date'] ?? '';
        $this->session = $data['session'] ?? '';
        $this->follow_up = !empty($data['follow_up']) && $data['follow_up'] == 1;
        $this->status = $data['status'] ?? 'pending';
        $this->phone = $data['phone'] ?? '';
        $this->symptoms = $data['symptoms'] ?? '';
        $this->patient_type = $data['patient_type'] ?? '';
        return $this;
    }

    // Kiểm tra cuộc hẹn đã tồn tại chưa (cùng bệnh nhân, bác sĩ, ngày và buổi khám)
    public function isAppointmentExists(?int $patient_id, int $doctor_id, string $appointment_date, string $session): bool
    {

        if ($patient_id === null) {
            // Nếu là khách vãng lai thì bỏ qua kiểm tra trùng lịch
            return false;
        }

        $statement = $this->db->prepare(
            'SELECT COUNT(*) FROM appointments 
             WHERE patient_id = :patient_id 
             AND appointment_date = :appointment_date
             AND session = :session'
        );

        $statement->execute([
            'patient_id' => $patient_id,
            'appointment_date' => $appointment_date,
            'session' => $session,
        ]);

        return $statement->fetchColumn() > 0;
    }

    // Lấy danh sách cuộc hẹn của bệnh nhân
    public function getAppointmentsByPatient(int $patient_id): array
    {
        $appointments = [];
        $statement = $this->db->prepare(
            'SELECT 
             a.*, 
             u.name AS patient_name,
             duser.name AS doctor_name
         FROM appointments a
         JOIN users u ON a.patient_id = u.id
         JOIN doctors d ON a.doctor_id = d.doctor_id
         JOIN users duser ON duser.id = d.doctor_id
         WHERE a.patient_id = :patient_id
         ORDER BY a.appointment_date DESC'
        );

        $statement->execute(['patient_id' => $patient_id]);

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $appointment = new Appointment($this->db);
            $appointment->fillFromDbRow($row);
            $appointment->doctor_name = $row['doctor_name'];   // Gán tên bác sĩ (bổ sung nếu muốn)
            $appointments[] = $appointment;
        }

        return $appointments;
    }


    // Lấy danh sách cuộc hẹn của bác sĩ
    public function getAppointmentsByDoctor(int $doctor_id): array
    {
        $appointments = [];

        $statement = $this->db->prepare(
            'SELECT 
            a.*, 
            CASE 
                WHEN a.patient_type = "guest" THEN g_patient.name
                ELSE u_patient.name
            END AS patient_name,
            u_doctor.name AS doctor_name
         FROM appointments a
         LEFT JOIN users u_patient ON a.patient_id = u_patient.id
         LEFT JOIN guest_patients g_patient ON a.guest_id = g_patient.id
         JOIN doctors d ON a.doctor_id = d.doctor_id
         JOIN users u_doctor ON d.doctor_id = u_doctor.id
         WHERE a.doctor_id = :doctor_id AND a.status = "pending"
         ORDER BY a.appointment_date ASC,
                  FIELD(a.session, "Sáng", "Chiều")'
        );

        $statement->execute(['doctor_id' => $doctor_id]);

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $appointment = new Appointment($this->db);
            $appointment->fillFromDbRow($row);
            $appointments[] = $appointment;
        }

        return $appointments;
    }
    public function getUniqueAppointmentsByDoctor(int $doctor_id): array
    {
        $appointments = [];

        $statement = $this->db->prepare(
            'SELECT 
            a.*, 
            duser.name AS doctor_name,
            patient_user.address AS patient_address,
            CASE 
                WHEN a.patient_type = "user" THEN patient_user.name
                WHEN a.patient_type = "guest" THEN guest.name
                ELSE "Không xác định"
            END AS patient_name
        FROM appointments a
        JOIN users duser ON a.doctor_id = duser.id
        LEFT JOIN users patient_user ON a.patient_type = "user" AND a.patient_id = patient_user.id
        LEFT JOIN guest_patients guest ON a.patient_type = "guest" AND a.guest_id = guest.id
        WHERE a.doctor_id = :doctor_id
        AND a.status = "completed"
        GROUP BY a.doctor_id, a.patient_type, COALESCE(a.patient_id, a.guest_id)
        ORDER BY patient_name'
        );

        $statement->execute(['doctor_id' => $doctor_id]);

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $appointment = new Appointment($this->db);
            $appointment->fillFromDbRow($row); // giờ thì không bị lỗi nữa vì đã có a.*
            $appointment->doctor_name = $row['doctor_name'];
            $appointment->patient_name = $row['patient_name'];
            $appointments[] = $appointment;
        }

        return $appointments;
    }

    // Lấy tất cả cuộc hẹn
    public function getAppointments(): array
    {
        $appointments = [];

        $statement = $this->db->prepare(
            'SELECT 
            a.*, 
            u.address AS patient_address,
            CASE 
                WHEN a.patient_type = "user" THEN u.name
                WHEN a.patient_type = "guest" THEN g.name
                ELSE NULL
            END AS patient_name,

            CASE 
                WHEN a.patient_type = "guest" THEN g.name
                ELSE NULL
            END AS guest_name,

            CASE 
                WHEN a.patient_type = "user" THEN u.phone
                WHEN a.patient_type = "guest" THEN g.phone
                ELSE NULL
            END AS phone,

            duser.name AS doctor_name
        FROM appointments a
        LEFT JOIN users u ON a.patient_id = u.id
        LEFT JOIN guest_patients g ON a.guest_id = g.id
        LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
        LEFT JOIN users duser ON d.doctor_id = duser.id
        ORDER BY a.appointment_date DESC'
        );

        $statement->execute();

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $appointment = new Appointment($this->db);
            $appointment->fillFromDbRow($row);
            $appointments[] = $appointment;
        }

        return $appointments;
    }


    // Kiểm tra dữ liệu đầu vào có hợp lệ không
    public function validate(): bool
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $now = new DateTime(); // thời gian hiện tại
        $appointmentDate = DateTime::createFromFormat('Y-m-d', $this->appointment_date);

        if (!$appointmentDate) {
            throw new Exception("Ngày hẹn không hợp lệ.");
        }

        // Nếu ngày hẹn < hôm nay
        if ($appointmentDate < (clone $now)->setTime(0, 0)) {
            throw new Exception("Ngày hẹn không được trong quá khứ.");
        }

        // Nếu ngày hẹn là hôm nay, kiểm tra theo buổi
        if ($appointmentDate->format('Y-m-d') === $now->format('Y-m-d')) {
            $hour = (int)$now->format('H');
            if ($this->session === 'Sáng' && $hour >= 13) {
                throw new Exception("Không thể đặt lịch buổi sáng cho hôm nay vì đã quá 13h.");
            }

            if ($hour >= 17) {
                throw new Exception("Không thể đặt lịch hôm nay vì đã quá 17h.");
            }
        }

        return true;
    }

    public function validateUpdate(): array
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $errors = [];

        $appointmentDate = DateTime::createFromFormat('Y-m-d', $this->appointment_date);
        $now = new DateTime();

        if (!$appointmentDate) {
            $errors[] = 'Ngày khám không hợp lệ.';
        } elseif ($appointmentDate < $now && $appointmentDate->format('Y-m-d') !== $now->format('Y-m-d')) {
            $errors[] = 'Ngày khám phải là hôm nay hoặc trong tương lai.';
        } elseif ($appointmentDate->format('Y-m-d') === $now->format('Y-m-d')) {
            $currentHour = (int) $now->format('H');

            if ($this->session === 'Sáng' && $currentHour >= 12) {
                $errors[] = 'Buổi sáng đã kết thúc.';
            } elseif ($this->session === 'Chiều' && $currentHour >= 17) {
                $errors[] = 'Buổi chiều đã kết thúc.';
            }
        }

        return $errors;
    }
    //Thống kê theo tháng
    public function getMonthlyAppointmentStats(int $year): array
    {
        $sql = "
        SELECT 
            MONTH(appointment_date) AS month,
            COUNT(*) AS total_appointments,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_appointments,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_appointments,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_appointments
        FROM appointments
        WHERE YEAR(appointment_date) = :year
        GROUP BY MONTH(appointment_date)
    ";

        $statement = $this->db->prepare($sql);
        $statement->execute(['year' => $year]);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Tạo đủ 12 tháng (1–12), gán 0 nếu không có dữ liệu
        $statsByMonth = [];
        for ($month = 1; $month <= 12; $month++) {
            $statsByMonth[$month] = [
                'month' => $month,
                'total_appointments' => 0,
                'completed_appointments' => 0,
                'pending_appointments' => 0,
                'cancelled_appointments' => 0,
            ];
        }

        // Ghi đè dữ liệu có trong DB
        foreach ($results as $row) {
            $month = (int)$row['month'];
            $statsByMonth[$month] = [
                'month' => $month,
                'total_appointments' => (int)$row['total_appointments'],
                'completed_appointments' => (int)$row['completed_appointments'],
                'pending_appointments' => (int)$row['pending_appointments'],
                'cancelled_appointments' => (int)$row['cancelled_appointments'],
            ];
        }

        return array_values($statsByMonth); // Trả mảng theo thứ tự 1-12
    }

    //Thống kê theo trạng thái
    public function getAppointmentStatusStats(): array
    {
        $stmt = $this->db->prepare("
        SELECT status, COUNT(*) AS count
        FROM appointments
        GROUP BY status
    ");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Đảm bảo có đủ 4 loại, kể cả khi số lượng = 0
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        $data = [];
        foreach ($statuses as $status) {
            $found = array_filter($result, fn($r) => $r['status'] === $status);
            $data[$status] = $found ? (int)array_values($found)[0]['count'] : 0;
        }

        return $data;
    }

    // Thống kê  theo tái khám
    public function getFollowUpStats(): array
    {
        $stmt = $this->db->prepare("
        SELECT follow_up, COUNT(*) AS count
        FROM appointments
        GROUP BY follow_up
    ");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'new' => 0,
            'follow_up' => 0
        ];

        foreach ($result as $row) {
            if ((int)$row['follow_up'] === 1) {
                $data['follow_up'] = (int)$row['count'];
            } else {
                $data['new'] = (int)$row['count'];
            }
        }

        return $data;
    }
}