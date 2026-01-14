<?php
include 'config.php';
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { exit; }

if(isset($_POST['btn_hapus_masal']) && !empty($_POST['pilih'])){
    foreach($_POST['pilih'] as $id){
        $id = mysqli_real_escape_string($conn, $id);
        $res = mysqli_query($conn, "SELECT foto FROM absensi WHERE id='$id'");
        $row = mysqli_fetch_array($res);
        if($row && !empty($row['foto']) && file_exists("uploads/".$row['foto'])){
            unlink("uploads/".$row['foto']);
        }
        mysqli_query($conn, "DELETE FROM absensi WHERE id='$id'");
    }
    header("Location: admin.php?status=success");
} else {
    header("Location: admin.php?status=error");
}
?>