<?php
require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';

$model = new TabelKandidat('localhost', 'mvp_kandidat_db', 'root', '');
$view = new ViewKandidat();
$presenter = new PresenterKandidat($model, $view);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Menangani aksi CRUD berdasarkan nilai $_POST['action'] yang dikirimkan form
    $action = $_POST['action'] ?? '';
    if ($action === 'tambah') {
        // Mengambil input form dan memanggil presenter untuk menambah kandidat baru
        $presenter->tambahKandidat(
            trim($_POST['nama'] ?? ''),
            trim($_POST['posisi'] ?? ''),
            trim($_POST['domisili'] ?? ''),
            (int)($_POST['skorTes'] ?? 0),
            (int)($_POST['pengalamanTahun'] ?? 0)
        );
    } elseif ($action === 'ubah') {
        // Mengambil ID dan input form lalu memanggil presenter untuk mengubah data kandidat
        $presenter->ubahKandidat(
            (int)($_POST['id'] ?? 0),
            trim($_POST['nama'] ?? ''),
            trim($_POST['posisi'] ?? ''),
            trim($_POST['domisili'] ?? ''),
            (int)($_POST['skorTes'] ?? 0),
            (int)($_POST['pengalamanTahun'] ?? 0)
        );
    } elseif ($action === 'hapus') {
        // Mengambil ID kandidat lalu memanggil presenter untuk menghapus data
        $presenter->hapusKandidat((int)($_POST['id'] ?? 0));
    }
    header("Location: index.php");
    exit();
} else {
    $screen = $_GET['screen'] ?? 'list';
    if ($screen === 'add') {
        echo $presenter->tampilkanFormKandidat();
    } elseif ($screen === 'edit' && isset($_GET['id'])) {
        echo $presenter->tampilkanFormKandidat((int)$_GET['id']);
    } else {
        echo $presenter->tampilkanKandidat();
    }
}
?>

