<?php
// Entitas Departemen untuk merepresentasikan satu baris data pada tabel departemen
class Departemen {
    private ?int $id;
    private string $namaDepartemen;
    private string $kepalaDepartemen;
    private string $lokasi;
    private int $jumlahKaryawan;

    // Konstruktor untuk menginisialisasi seluruh atribut objek Departemen
    public function __construct(
        ?int $id,
        string $namaDepartemen,
        string $kepalaDepartemen,
        string $lokasi,
        int $jumlahKaryawan
    ) {
        $this->id = $id;
        $this->namaDepartemen = $namaDepartemen;
        $this->kepalaDepartemen = $kepalaDepartemen;
        $this->lokasi = $lokasi;
        $this->jumlahKaryawan = $jumlahKaryawan;
    }

    // Getter untuk mengambil nilai masing-masing properti Departemen
    public function getId(): ?int { return $this->id; }
    public function getNamaDepartemen(): string { return $this->namaDepartemen; }
    public function getKepalaDepartemen(): string { return $this->kepalaDepartemen; }
    public function getLokasi(): string { return $this->lokasi; }
    public function getJumlahKaryawan(): int { return $this->jumlahKaryawan; }

    // Setter untuk mengubah nilai properti Departemen
    public function setNamaDepartemen(string $namaDepartemen): void { $this->namaDepartemen = $namaDepartemen; }
    public function setKepalaDepartemen(string $kepalaDepartemen): void { $this->kepalaDepartemen = $kepalaDepartemen; }
    public function setLokasi(string $lokasi): void { $this->lokasi = $lokasi; }
    public function setJumlahKaryawan(int $jumlahKaryawan): void { $this->jumlahKaryawan = $jumlahKaryawan; }
}
?>
