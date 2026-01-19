<?php
/**
 * SIHADIR MPP - Konfigurasi Database
 * 
 * INSTRUKSI:
 * 1. Copy file ini dan rename menjadi 'config.php'
 * 2. Sesuaikan nilai di bawah dengan konfigurasi server Anda
 * 3. Pastikan database sudah dibuat dan di-import dari database.sql
 */

// ============================================
// KONFIGURASI DATABASE
// ============================================
$host = "localhost";        // Host database (biasanya localhost)
$user = "root";             // Username database
$pass = "";                 // Password database (kosong untuk default XAMPP/Laragon)
$db   = "sihadir_mpp";      // Nama database

// ============================================
// KONEKSI DATABASE
// ============================================
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("❌ Koneksi database gagal: " . mysqli_connect_error() . 
        "<br><br>Pastikan:<br>" .
        "1. Web server (Apache/MySQL) sudah running<br>" .
        "2. Database '" . $db . "' sudah dibuat<br>" .
        "3. Kredensial database sudah benar");
}

// ============================================
// KONFIGURASI APLIKASI
// ============================================
date_default_timezone_set('Asia/Jakarta');

// Konstanta aplikasi (sesuaikan jika perlu)
define('JAM_MASUK', '07:30');           // Batas jam masuk (format H:i)
define('RADIUS_KANTOR', 100);            // Radius lokasi kantor dalam meter
define('LAT_KANTOR', -6.123456);         // Latitude kantor
define('LON_KANTOR', 106.123456);        // Longitude kantor

// ============================================
// SESSION
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
