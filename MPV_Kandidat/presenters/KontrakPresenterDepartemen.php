<?php
// Kontrak interface untuk Presenter Departemen
interface KontrakPresenterDepartemen {
    // Menampilkan daftar seluruh departemen
    public function tampilkanDepartemen(): string;

    // Menampilkan form tambah atau ubah departemen
    public function tampilkanFormDepartemen(?int $id = null): string;

    // Menangani penambahan data departemen
    public function tambahDepartemen(string $namaDepartemen, string $kepalaDepartemen, string $lokasi, int $jumlahKaryawan): void;

    // Menangani perubahan data departemen
    public function ubahDepartemen(int $id, string $namaDepartemen, string $kepalaDepartemen, string $lokasi, int $jumlahKaryawan): void;

    // Menangani penghapusan data departemen
    public function hapusDepartemen(int $id): void;
}
?>
