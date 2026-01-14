<?php
include 'config.php';

// Set zona waktu agar sinkron dengan WIB
date_default_timezone_set('Asia/Jakarta');

/* =========================
   AMBIL DATA FORM
========================= */
$nama          = mysqli_real_escape_string($conn, $_POST['nama']);
$kategori      = $_POST['kategori'];
$status_hadir  = $_POST['status_hadir'];
$keterangan    = isset($_POST['keterangan']) 
                 ? mysqli_real_escape_string($conn, $_POST['keterangan']) 
                 : null;
$koordinat     = $_POST['koordinat'];
$akurasi       = $_POST['akurasi'];
$jam_sekarang  = date('H:i'); // Mengambil jam saat ini (Format 24 jam)

/* =========================
   LOGIKA ANALISIS AI
========================= */
$ai_notes = [];

// 1. Validasi Waktu: Terlambat jika > 07:30
if ($jam_sekarang > "07:30") {
    $ai_notes[] = "Terlambat";
} else {
    $ai_notes[] = "Tepat Waktu";
}

// 2. Validasi Jarak/Akurasi Lokasi
if ($akurasi > 100) {
    $ai_notes[] = "Lokasi Kurang Akurat";
} else {
    $ai_notes[] = "Lokasi Valid";
}

// Menggabungkan hasil analisis menjadi satu string untuk database
$status_ai = implode(" | ", $ai_notes);

/* =========================
   PROSES FOTO / FILE
========================= */
$nama_file = "";

/* === HADIR → FOTO SELFIE === */
if ($status_hadir == "Hadir" && !empty($_POST['foto_base64'])) {
    $foto_data = $_POST['foto_base64'];
    $foto_data = str_replace('data:image/jpeg;base64,', '', $foto_data);
    $foto_data = str_replace(' ', '+', $foto_data);
    $data = base64_decode($foto_data);

    // Menyimpan foto di folder uploads/
    $nama_file = "Selfie_" . time() . ".jpg";
    file_put_contents("uploads/" . $nama_file, $data);
}

/* === IZIN / SAKIT → FILE SURAT === */
if (($status_hadir == "Izin" || $status_hadir == "Sakit") && isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == 0) {
    $ext = pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION);
    $nama_file = "Surat_" . time() . "." . $ext;
    move_uploaded_file($_FILES['file_surat']['tmp_name'], "uploads/" . $nama_file);
}

/* =========================
   SIMPAN KE DATABASE
========================= */
// Memasukkan hasil analisis AI ke kolom analisis_ai
$query = "INSERT INTO absensi 
(nama, kategori, status_hadir, keterangan, foto, koordinat, akurasi_meter, analisis_ai, waktu_absen)
VALUES
('$nama', '$kategori', '$status_hadir', '$keterangan', '$nama_file', '$koordinat', '$akurasi', '$status_ai', NOW())";

mysqli_query($conn, $query);

/* =========================
   REDIRECT
========================= */
// Kembali ke dashboard setelah sukses
header("Location: dashboard.php");

/* =========================
   PROSES FOTO DENGAN WATERMARK
========================= */
$nama_file = "";

if ($status_hadir == "Hadir" && !empty($_POST['foto_base64'])) {
    // 1. Decode foto dari Base64
    $foto_data = $_POST['foto_base64'];
    $foto_data = str_replace('data:image/jpeg;base64,', '', $foto_data);
    $foto_data = str_replace(' ', '+', $foto_data);
    $data = base64_decode($foto_data);

    // 2. Buat resource gambar dari data decode
    $image = imagecreatefromstring($data);
    
    // 3. Tentukan warna teks (Putih) dan warna bayangan (Hitam) agar terbaca
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);

    // 4. Siapkan teks watermark (Tanggal & Jam Real-time)
    $text = "SIHADIR MPP - " . date('d/m/Y H:i:s') . " WIB";
    
    // 5. Tambahkan teks ke gambar (posisi pojok kiri bawah)
    // imagestring(image, font_size, x, y, string, color)
    imagestring($image, 5, 12, 12, $text, $black); // Shadow
    imagestring($image, 5, 10, 10, $text, $white); // Main Text

    // 6. Simpan gambar hasil watermark ke folder uploads
    $nama_file = "Selfie_" . time() . ".jpg";
    imagejpeg($image, "uploads/" . $nama_file, 90); // Kualitas 90%

    // 7. Bersihkan memori
    imagedestroy($image);
}
exit;
?>
