<?php

namespace App\Models;

use PDO;
use Exception;

class Medicines
{
    private PDO $db;

    public int $id;
    public string $name;
    public string $description;
    public string $dosage_form; // dạng bào chế: viên, siro, thuốc xịt, v.v.
    public string $strength;     // hàm lượng hoạt chất
    public string $unit;         // đơn vị (mg, ml...)
    public float $price;
    public bool $available;      // còn hàng hay không (true = còn hàng, false = hết hàng)

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    // Gán dữ liệu từ mảng đầu vào vào các thuộc tính của đối tượng
    public function fill(array $data): static
    {
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->dosage_form = $data['dosage_form'] ?? '';
        $this->strength = $data['strength'] ?? '';
        $this->unit = $data['unit'] ?? '';
        $this->price = isset($data['price']) ? (float)$data['price'] : 0.0;
        $this->available = isset($data['available']) ? (bool)$data['available'] : true;

        return $this;
    }

    // Lưu thuốc mới vào cơ sở dữ liệu
    public function save(): bool
    {
        $statement = $this->db->prepare(
            'INSERT INTO medicines (name, description, dosage_form, strength, unit, price, available)
             VALUES (:name, :description, :dosage_form, :strength, :unit, :price, :available)'
        );

        return $statement->execute([
            'name' => $this->name,
            'description' => $this->description,
            'dosage_form' => $this->dosage_form,
            'strength' => $this->strength,
            'unit' => $this->unit,
            'price' => $this->price,
            'available' => $this->available
        ]);
    }

    // Tìm thuốc theo ID và gán dữ liệu vào đối tượng hiện tại
    public function find(int $id): ?static
    {
        $statement = $this->db->prepare('SELECT * FROM medicines WHERE id = :id');
        $statement->execute(['id' => $id]);

        if ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $this->id = (int)$row['id'];
            $this->fill($row);
            return $this;
        }

        return null;
    }

    // Tìm thuốc theo tên (tương đối, không phân biệt hoa thường)
    public function findByName(string $name): array
    {
        $statement = $this->db->prepare('SELECT * FROM medicines WHERE name LIKE :name ORDER BY name');
        $statement->execute(['name' => "%{$name}%"]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật thông tin thuốc trong cơ sở dữ liệu
    public function update(): bool
    {
        $statement = $this->db->prepare(
            'UPDATE medicines 
             SET name = :name, description = :description, dosage_form = :dosage_form, 
                 strength = :strength, unit = :unit, price = :price, available = :available
             WHERE id = :id'
        );

        return $statement->execute([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'dosage_form' => $this->dosage_form,
            'strength' => $this->strength,
            'unit' => $this->unit,
            'price' => $this->price,
            'available' => $this->available
        ]);
    }

    // Xóa thuốc khỏi cơ sở dữ liệu
    public function delete(): bool
    {
        $statement = $this->db->prepare('DELETE FROM medicines WHERE id = :id');
        return $statement->execute(['id' => $this->id]);
    }

    // Lấy danh sách tất cả các thuốc
    public function all(): array
    {
        $statement = $this->db->query('SELECT * FROM medicines ORDER BY name');
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}