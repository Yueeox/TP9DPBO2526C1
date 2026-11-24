<?php
require_once __DIR__ . '/KontrakView.php';

// ViewDepartemen menerapkan Strict Passive View: tanpa looping, tanpa akses Model/Entity, hanya mengganti token HTML
class ViewDepartemen implements KontrakView {
    private string $skinPath;
    private string $formPath;

    // Konstruktor untuk menentukan path file template HTML daftar dan form departemen
    public function __construct(string $skinPath = '', string $formPath = '') {
        $this->skinPath = $skinPath ?: __DIR__ . '/../template/skin_departemen.html';
        $this->formPath = $formPath ?: __DIR__ . '/../template/form_departemen.html';
    }

    // Membaca template daftar dan mengganti placeholder {{TABLE_ROWS}} serta {{TOTAL_DATA}}
    public function renderDaftar(string $tableRowsHtml, int $totalData): string {
        $template = file_get_contents($this->skinPath);

        $tokens = [
            '{{TABLE_ROWS}}' => $tableRowsHtml,
            '{{TOTAL_DATA}}' => (string)$totalData,
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }

    // Membaca template form dan mengganti seluruh placeholder input departemen
    public function renderForm(string $title, string $action, array $formData = []): string {
        $template = file_get_contents($this->formPath);

        $tokens = [
            '{{FORM_TITLE}}'        => htmlspecialchars($title),
            '{{ACTION}}'            => htmlspecialchars($action),
            '{{ID}}'                => htmlspecialchars((string)($formData['id'] ?? '')),
            '{{NAMA_DEPARTEMEN}}'   => htmlspecialchars($formData['namaDepartemen'] ?? ''),
            '{{KEPALA_DEPARTEMEN}}' => htmlspecialchars($formData['kepalaDepartemen'] ?? ''),
            '{{LOKASI}}'            => htmlspecialchars($formData['lokasi'] ?? ''),
            '{{JUMLAH_KARYAWAN}}'   => htmlspecialchars((string)($formData['jumlahKaryawan'] ?? 0)),
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }
}
?>
