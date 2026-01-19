<?php
/**
 * SIHADIR MPP - Hapus Data Individual
 */
include 'config.php';
session_start();

// Cek akses admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { 
    header("Location: index.php");
    exit;
}

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Hapus foto jika ada
    $res = mysqli_query($conn, "SELECT foto FROM absensi WHERE id='$id'");
    $row = mysqli_fetch_array($res);
    if($row && !empty($row['foto']) && file_exists("uploads/".$row['foto'])) {
        unlink("uploads/".$row['foto']);
    }
    
    // Hapus data
    mysqli_query($conn, "DELETE FROM absensi WHERE id='$id'");
    
    header("Location: admin.php?status=deleted");
} else {
    header("Location: admin.php");
}
?>
