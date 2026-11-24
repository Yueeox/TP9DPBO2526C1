# TP9DPBO2526C1

# JANJI

Saya Wingko Prajna dengan NIM 2503358 mengerjakan Tugas Praktikum 9 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

---

## Dokumentasi Program (`project/`)

Aplikasi pada folder `project/` merupakan sistem manajemen **Daftar Kandidat Karyawan** dan **Daftar Departemen** berbasis PHP dan MySQL yang menerapkan pola arsitektur **Model-View-Presenter (MVP)** edisi **Strict Passive View (*Dumb View*)**.

### 1. Struktur Folder

```text
project/
├── index.php                          # Entry point & router modul Kandidat
├── departemen.php                     # Entry point & router modul Departemen
├── mvp_db.sql                         # Skrip SQL database mvp_kandidat_db & tabel kandidat
├── models/
│   ├── DB.php                         # Wrapper koneksi PDO & eksekusi prepared statement
│   ├── Kandidat.php                   # Entitas domain Kandidat
│   ├── Departemen.php                 # Entitas domain Departemen
│   ├── KontrakModel.php               # Interface Model Kandidat
│   ├── KontrakModelDepartemen.php     # Interface Model Departemen
│   ├── TabelKandidat.php              # Implementasi CRUD tabel kandidat
│   └── TabelDepartemen.php            # Implementasi CRUD tabel departemen
├── presenters/
│   ├── KontrakPresenter.php           # Interface Presenter Kandidat
│   ├── KontrakPresenterDepartemen.php # Interface Presenter Departemen
│   ├── PresenterKandidat.php          # Logika presentasi & orkestrasi modul Kandidat
│   └── PresenterDepartemen.php        # Logika presentasi & orkestrasi modul Departemen
├── views/
│   ├── KontrakView.php                # Interface Strict Passive View
│   ├── ViewKandidat.php               # Renderer template HTML Kandidat
│   └── ViewDepartemen.php             # Renderer template HTML Departemen
└── template/
    ├── skin.html                      # Template halaman daftar kandidat
    ├── form.html                      # Template form tambah/ubah kandidat
    ├── skin_departemen.html           # Template halaman daftar departemen
    └── form_departemen.html           # Template form tambah/ubah departemen
```

---

### 2. Cara Program Bekerja (Arsitektur MVP Strict Passive View)

Program memisahkan tanggung jawab ke dalam empat lapisan utama:

1. **Entry Point (`index.php` & `departemen.php`)**
   - Menginisialisasi objek Model (`TabelKandidat` / `TabelDepartemen`), View (`ViewKandidat` / `ViewDepartemen`), dan Presenter (`PresenterKandidat` / `PresenterDepartemen`) dengan *dependency injection*.
   - Bertindak sebagai router HTTP yang memisahkan request `POST` (aksi CRUD) dan `GET` (menampilkan daftar atau form).
2. **Model (`models/`)**
   - `DB.php` mengelola koneksi database MySQL (`mvp_kandidat_db`) menggunakan **PDO** dengan *prepared statement* (`executeQuery()` dan `getAllResult()`).
   - `TabelKandidat` dan `TabelDepartemen` mewarisi `DB` serta mengimplementasikan kontrak masing-masing (`KontrakModel` / `KontrakModelDepartemen`).
   - Hasil query dari database dipetakan menjadi objek entitas (`Kandidat` atau `Departemen`) melalui `mapRowToKandidat()` dan `mapRowToDepartemen()`.
3. **Presenter (`presenters/`)**
   - Menjadi penghubung antara Model dan View tanpa bergantung pada implementasi konkret (bergantung pada `KontrakModel` / `KontrakModelDepartemen` dan `KontrakView`).
   - Mengambil data dari Model, menghitung total data, merakit baris HTML `<tr>...</tr>` pada method `buildRowHtml()` (termasuk *escaping* `htmlspecialchars()` dan format tampilan), lalu mengirimkannya ke View.
   - Mengarahkan ulang (*redirect*) ke halaman daftar setelah operasi `tambah`, `ubah`, atau `hapus` selesai.
4. **Strict Passive View (`views/` & `template/`)**
   - View tidak mengenal entitas domain (`Kandidat` / `Departemen`), tidak melakukan perulangan (*loop*), dan tidak menghitung agregat.
   - `renderDaftar()` dan `renderForm()` hanya membaca file template HTML lalu mengganti *placeholder token* (seperti `{{TABLE_ROWS}}`, `{{TOTAL_DATA}}`, `{{FORM_TITLE}}`, `{{ACTION}}`, dan nilai input form) menggunakan `str_replace()`.

---

### 3. Alur Program

#### A. Alur Request `GET` (Menampilkan Halaman & Form)
1. **Halaman Daftar (`?screen=list` atau tanpa parameter)**
   - Entry point memanggil `$presenter->tampilkanKandidat()` (atau `tampilkanDepartemen()`).
   - Presenter memanggil `$model->getAllKandidat()` (`ORDER BY skorTes DESC, id ASC`) atau `$model->getAllDepartemen()` (`ORDER BY namaDepartemen ASC, id ASC`).
   - Presenter menghitung jumlah data dan merakit seluruh baris `<tr>` via `buildRowHtml()` (atau menampilkan pesan kosong jika belum ada data).
   - Presenter memanggil `$view->renderDaftar($rowsHtml, $totalData)` dan hasilnya di-`echo` ke browser.
2. **Halaman Form Tambah (`?screen=add`)**
   - Entry point memanggil `$presenter->tampilkanFormKandidat()` (atau `tampilkanFormDepartemen()`) tanpa argumen ID.
   - Presenter memanggil `$view->renderForm("Tambah ...", "tambah", [])` sehingga form tampil kosong dengan `action="tambah"`.
3. **Halaman Form Ubah (`?screen=edit&id={id}`)**
   - Entry point memanggil `$presenter->tampilkanFormKandidat($id)` (atau `tampilkanFormDepartemen($id)`).
   - Presenter mengambil data via `$model->getKandidatById($id)` / `$model->getDepartemenById($id)`. Jika data tidak ditemukan (`null`), user diarahkan kembali ke halaman daftar.
   - Jika ditemukan, data diekstrak ke array `$formData` dan dikirim ke `$view->renderForm("Ubah ...", "ubah", $formData)`.

#### B. Alur Request `POST` (Operasi CRUD)
Setiap pengiriman form atau klik tombol **Hapus** menggunakan metode `POST` dengan pola *Post-Redirect-Get*:
1. **Tambah (`action = 'tambah'`)**: Entry point membaca input `$_POST` -> memanggil `$presenter->tambahKandidat(...)` / `tambahDepartemen(...)` -> Model menjalankan query `INSERT` -> Redirect ke halaman daftar (`index.php` / `departemen.php`).
2. **Ubah (`action = 'ubah'`)**: Entry point membaca `id` dan input `$_POST` -> memanggil `$presenter->ubahKandidat(...)` / `ubahDepartemen(...)` -> Model menjalankan query `UPDATE ... WHERE id = :id` -> Redirect ke halaman daftar.
3. **Hapus (`action = 'hapus'`)**: Tombol hapus di tabel memicu konfirmasi JavaScript (`confirm(...)`) lalu mengirim `id` via `POST` -> Entry point memanggil `$presenter->hapusKandidat($id)` / `hapusDepartemen($id)` -> Model menjalankan query `DELETE ... WHERE id = :id` -> Redirect ke halaman daftar.

#### C. Navigasi Antar Modul
- Pada halaman **Daftar Kandidat** (`index.php` / `template/skin.html`), tersedia tombol navigasi menuju **Daftar Departemen** (`departemen.php`).
- Pada halaman **Daftar Departemen** (`departemen.php` / `template/skin_departemen.html`), tersedia tombol navigasi kembali menuju **Daftar Kandidat** (`index.php`).

---

### 4. Dokumentasi

| Keterangan | Link Google Drive (Screenrecord) |
| :--- | :--- |
| Demo & Alur Program | [https://drive.google.com/file/d/13I0TxP5nbV7sIm1S0kmRQ06RiRDSZNxr/view?usp=sharing](#) |