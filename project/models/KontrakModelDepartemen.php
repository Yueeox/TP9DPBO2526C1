<?php
// Kontrak interface untuk Model Departemen agar Presenter tidak bergantung pada implementasi konkret
interface KontrakModelDepartemen {
    // Mengambil seluruh data departemen sebagai array objek Departemen
    public function getAllDepartemen(): array;

    // Mengambil satu data departemen berdasarkan ID
    public function getDepartemenById(int $id): ?Departemen;

    // Menambahkan data departemen baru ke database
    public function addDepartemen(string $namaDepartemen, string $kepalaDepartemen, string $lokasi, int $jumlahKaryawan): void;

    // Memperbarui data departemen berdasarkan ID
    public function updateDepartemen(int $id, string $namaDepartemen, string $kepalaDepartemen, string $lokasi, int $jumlahKaryawan): void;

    // Menghapus data departemen berdasarkan ID
    public function deleteDepartemen(int $id): void;
}
?>
