<?php
require_once __DIR__ . '/models/TabelDepartemen.php';
require_once __DIR__ . '/views/ViewDepartemen.php';
require_once __DIR__ . '/presenters/PresenterDepartemen.php';

// Inisialisasi Model, View, dan Presenter untuk modul Departemen
$model = new TabelDepartemen('localhost', 'mvp_kandidat_db', 'root', '');
$view = new ViewDepartemen();
$presenter = new PresenterDepartemen($model, $view);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Menangani aksi CRUD berdasarkan nilai $_POST['action'] yang dikirimkan form
    $action = $_POST['action'] ?? '';
    if ($action === 'tambah') {
        // Mengambil input form dan memanggil presenter untuk menambah departemen baru
        $presenter->tambahDepartemen(
            trim($_POST['namaDepartemen'] ?? ''),
            trim($_POST['kepalaDepartemen'] ?? ''),
            trim($_POST['lokasi'] ?? ''),
            (int)($_POST['jumlahKaryawan'] ?? 0)
        );
    } elseif ($action === 'ubah') {
        // Mengambil ID dan input form lalu memanggil presenter untuk mengubah data departemen
        $presenter->ubahDepartemen(
            (int)($_POST['id'] ?? 0),
            trim($_POST['namaDepartemen'] ?? ''),
            trim($_POST['kepalaDepartemen'] ?? ''),
            trim($_POST['lokasi'] ?? ''),
            (int)($_POST['jumlahKaryawan'] ?? 0)
        );
    } elseif ($action === 'hapus') {
        // Mengambil ID departemen lalu memanggil presenter untuk menghapus data
        $presenter->hapusDepartemen((int)($_POST['id'] ?? 0));
    }
    header("Location: departemen.php");
    exit();
} else {
    // Menentukan tampilan halaman berdasarkan parameter query screen
    $screen = $_GET['screen'] ?? 'list';
    if ($screen === 'add') {
        echo $presenter->tampilkanFormDepartemen();
    } elseif ($screen === 'edit' && isset($_GET['id'])) {
        echo $presenter->tampilkanFormDepartemen((int)$_GET['id']);
    } else {
        echo $presenter->tampilkanDepartemen();
    }
}
?>
