<?php
require_once __DIR__ . '/KontrakPresenterDepartemen.php';
require_once __DIR__ . '/../models/KontrakModelDepartemen.php';
require_once __DIR__ . '/../views/KontrakView.php';

// PresenterDepartemen bertugas mengambil data dari Model, merakit baris HTML tabel, dan mengirimkannya ke Passive View
class PresenterDepartemen implements KontrakPresenterDepartemen {
    private KontrakModelDepartemen $model;
    private KontrakView $view;

    // Konstruktor untuk menyuntikkan dependensi Model dan View
    public function __construct(KontrakModelDepartemen $model, KontrakView $view) {
        $this->model = $model;
        $this->view = $view;
    }

    // Helper untuk merakit satu baris HTML <tr>...</tr> untuk tabel departemen
    private function buildRowHtml(int $no, Departemen $departemen): string {
        $id = (int)$departemen->getId();
        $namaDepartemen = htmlspecialchars($departemen->getNamaDepartemen());
        $kepalaDepartemen = htmlspecialchars($departemen->getKepalaDepartemen());
        $lokasi = htmlspecialchars($departemen->getLokasi());
        $jumlahKaryawan = number_format($departemen->getJumlahKaryawan()) . ' Orang';

        return "<tr>
            <td class='col-id'>{$no}</td>
            <td><strong>{$namaDepartemen}</strong></td>
            <td>{$kepalaDepartemen}</td>
            <td>{$lokasi}</td>
            <td>{$jumlahKaryawan}</td>
            <td class='col-actions'>
                <a href='departemen.php?screen=edit&id={$id}' class='btn btn-edit'>Edit</a>
                <form method='post' action='departemen.php' style='display:inline;' onsubmit=\"return confirm('Hapus departemen ini?')\">
                    <input type='hidden' name='action' value='hapus'>
                    <input type='hidden' name='id' value='{$id}'>
                    <button type='submit' class='btn btn-delete'>Hapus</button>
                </form>
            </td>
        </tr>";
    }

    // Mengambil seluruh data departemen, menghitung total, merakit HTML baris, dan merender ke View
    public function tampilkanDepartemen(): string {
        $listDepartemen = $this->model->getAllDepartemen();
        $totalDepartemen = count($listDepartemen);

        $rowsHtml = '';
        if (empty($listDepartemen)) {
            $rowsHtml = '<tr><td colspan="6" style="text-align:center;color:#6b7280;padding:24px;">Belum ada data departemen.</td></tr>';
        } else {
            $no = 1;
            foreach ($listDepartemen as $departemen) {
                $rowsHtml .= $this->buildRowHtml($no, $departemen);
                $no++;
            }
        }

        return $this->view->renderDaftar($rowsHtml, $totalDepartemen);
    }

    // Menyiapkan data form tambah atau edit lalu mengirimkannya ke View
    public function tampilkanFormDepartemen(?int $id = null): string {
        if ($id !== null) {
            $departemen = $this->model->getDepartemenById($id);
            if ($departemen === null) {
                header("Location: departemen.php");
                exit();
            }

            $formData = [
                'id'               => $departemen->getId(),
                'namaDepartemen'   => $departemen->getNamaDepartemen(),
                'kepalaDepartemen' => $departemen->getKepalaDepartemen(),
                'lokasi'           => $departemen->getLokasi(),
                'jumlahKaryawan'   => $departemen->getJumlahKaryawan(),
            ];
            return $this->view->renderForm("Ubah Data Departemen", "ubah", $formData);
        }

        return $this->view->renderForm("Tambah Departemen Baru", "tambah", []);
    }

    // Memanggil model untuk menambahkan departemen baru lalu redirect ke halaman daftar departemen
    public function tambahDepartemen(string $namaDepartemen, string $kepalaDepartemen, string $lokasi, int $jumlahKaryawan): void {
        $this->model->addDepartemen($namaDepartemen, $kepalaDepartemen, $lokasi, $jumlahKaryawan);
        header("Location: departemen.php");
        exit();
    }

    // Memanggil model untuk mengubah data departemen lalu redirect ke halaman daftar departemen
    public function ubahDepartemen(int $id, string $namaDepartemen, string $kepalaDepartemen, string $lokasi, int $jumlahKaryawan): void {
        $this->model->updateDepartemen($id, $namaDepartemen, $kepalaDepartemen, $lokasi, $jumlahKaryawan);
        header("Location: departemen.php");
        exit();
    }

    // Memanggil model untuk menghapus data departemen lalu redirect ke halaman daftar departemen
    public function hapusDepartemen(int $id): void {
        $this->model->deleteDepartemen($id);
        header("Location: departemen.php");
        exit();
    }
}
?>
