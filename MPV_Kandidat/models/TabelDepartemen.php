<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModelDepartemen.php';
require_once __DIR__ . '/Departemen.php';

// Model TabelDepartemen untuk operasi CRUD pada tabel departemen
class TabelDepartemen extends DB implements KontrakModelDepartemen {

    // Memanggil konstruktor parent DB untuk membuka koneksi database
    public function __construct(
        string $host = "localhost",
        string $db_name = "mvp_kandidat_db",
        string $username = "root",
        string $password = ""
    ) {
        parent::__construct($host, $db_name, $username, $password);
    }

    // Mengubah array hasil query menjadi objek entitas Departemen
    private function mapRowToDepartemen(array $row): Departemen {
        return new Departemen(
            (int)$row['id'],
            $row['namaDepartemen'],
            $row['kepalaDepartemen'],
            $row['lokasi'],
            (int)$row['jumlahKaryawan']
        );
    }

    // Mengambil seluruh data departemen diurutkan berdasarkan nama departemen
    public function getAllDepartemen(): array {
        $query = "SELECT * FROM departemen ORDER BY namaDepartemen ASC, id ASC";
        $this->executeQuery($query);
        $rows = $this->getAllResult();

        $list = [];
        foreach ($rows as $row) {
            $list[] = $this->mapRowToDepartemen($row);
        }
        return $list;
    }

    // Mengambil satu data departemen berdasarkan ID menggunakan prepared statement
    public function getDepartemenById(int $id): ?Departemen {
        $query = "SELECT * FROM departemen WHERE id = :id LIMIT 1";
        $this->executeQuery($query, [':id' => $id]);
        $rows = $this->getAllResult();
        if (empty($rows)) {
            return null;
        }
        return $this->mapRowToDepartemen($rows[0]);
    }

    // Menjalankan query INSERT untuk menambahkan data departemen baru
    public function addDepartemen(string $namaDepartemen, string $kepalaDepartemen, string $lokasi, int $jumlahKaryawan): void {
        $query = "INSERT INTO departemen (namaDepartemen, kepalaDepartemen, lokasi, jumlahKaryawan) VALUES (:namaDepartemen, :kepalaDepartemen, :lokasi, :jumlahKaryawan)";
        $this->executeQuery($query, [
            ':namaDepartemen' => $namaDepartemen,
            ':kepalaDepartemen' => $kepalaDepartemen,
            ':lokasi' => $lokasi,
            ':jumlahKaryawan' => $jumlahKaryawan,
        ]);
    }

    // Menjalankan query UPDATE untuk memperbarui data departemen berdasarkan ID
    public function updateDepartemen(int $id, string $namaDepartemen, string $kepalaDepartemen, string $lokasi, int $jumlahKaryawan): void {
        $query = "UPDATE departemen SET namaDepartemen = :namaDepartemen, kepalaDepartemen = :kepalaDepartemen, lokasi = :lokasi, jumlahKaryawan = :jumlahKaryawan WHERE id = :id";
        $this->executeQuery($query, [
            ':id' => $id,
            ':namaDepartemen' => $namaDepartemen,
            ':kepalaDepartemen' => $kepalaDepartemen,
            ':lokasi' => $lokasi,
            ':jumlahKaryawan' => $jumlahKaryawan,
        ]);
    }

    // Menjalankan query DELETE untuk menghapus data departemen berdasarkan ID
    public function deleteDepartemen(int $id): void {
        $query = "DELETE FROM departemen WHERE id = :id";
        $this->executeQuery($query, [':id' => $id]);
    }
}
?>
