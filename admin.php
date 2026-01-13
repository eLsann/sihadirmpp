<?php 
include 'config.php'; 
// Pastikan session dimulai untuk mengecek role admin
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { 
    header("Location: index.php"); 
    exit();
}
date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SIHADIR MPP BKPSDM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        body { background-color: #f1f2f5; font-family: 'Inter', sans-serif; }
        
        /* Header Biru Gradasi Navy */
        .header-blue {
            background: linear-gradient(135deg, #1f07fa 0%, #070136 100%);
            padding: 50px 0 100px;
            color: white;
            text-align: center;
            position: relative;
            font-family: 'Poppins', sans-serif;
        }

        .logout-link {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white !important;
            text-decoration: none;
            font-weight: bold;
            z-index: 1000;
        }

        .container-custom { max-width: 1300px; margin: -60px auto 50px; position: relative; z-index: 5; }

        /* Card Navigasi Atas */
        .action-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(7, 1, 54, 0.15);
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 30px rgba(7, 1, 54, 0.1);
        }

        /* Styling Tabel */
        .table thead th {
            color: #070136;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            border-bottom: 2px solid #f1f2f5;
        }

        /* Badge Styling */
        .badge-kategori { border: 1px solid #070136; color: #070136; background: white; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-ai-warn { background: #fff0f0; color: #d90429; border: 1px solid #fbc4c4; padding: 5px 10px; border-radius: 8px; font-weight: bold; font-size: 11px; }
        .badge-ai-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; padding: 5px 10px; border-radius: 8px; font-weight: bold; font-size: 11px; }

        .img-absensi { width: 55px; height: 45px; border-radius: 8px; object-fit: cover; border: 2px solid #070136; cursor: pointer; transition: 0.2s; }
        .img-absensi:hover { transform: scale(1.1); }
        
        .name-column { color: #070136; font-weight: 700; font-size: 15px; }
        .time-text { color: #070136; font-weight: 700; font-size: 16px; }
    </style>
</head>
<body>

    <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>

    <div class="header-blue">
        <img src="https://cdn-icons-png.flaticon.com/512/2666/2666505.png" width="50" class="mb-2">
        <h2 class="fw-bold">SIHADIR MPP BKPSDM</h2>
        <p class="opacity-75">Sistem Hadir Digital untuk Peserta Magang, PKL, dan Penelitian</p>
    </div>

    <div class="container container-custom">
        <div class="action-card">
            <h5 class="m-0 fw-bold" style="color: #070136;"><i class="fas fa-list me-2"></i>Riwayat Kehadiran</h5>
            <div class="d-flex gap-2">
                <a href="admin.php" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold">
                    <i class="fas fa-sync-alt"></i> Refresh
                </a>
                <a href="ekspor_excel.php" class="btn btn-success btn-sm rounded-pill px-4 fw-bold">
                    <i class="fas fa-file-excel"></i> Unduh Spreadsheet
                </a>
            </div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAMA</th>
                            <th>KATEGORI</th>
                            <th>STATUS AI</th>
                            <th>LOKASI & AKURASI</th>
                            <th>WAKTU</th>
                            <th class="text-center">FOTO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($conn, "SELECT * FROM absensi ORDER BY id DESC");
                        if (!$sql) { die("Gagal Query: " . mysqli_error($conn)); }
                        
                        while($row = mysqli_fetch_array($sql)){
                            $analisis = $row['analisis_ai'] ?? 'N/A';
                            $is_late = (strpos(strtolower($analisis), 'terlambat') !== false);
                            $foto = $row['foto'];
                        ?>
                        <tr>
                            <td class="text-muted fw-bold"><?= $row['id']; ?></td>
                            <td class="name-column"><?= strtoupper($row['nama']); ?></td>
                            <td><span class="badge-kategori"><?= strtoupper($row['kategori']); ?></span></td>
                            <td>
                                <span class="<?= $is_late ? 'badge-ai-warn' : 'badge-ai-success'; ?>">
                                    <?= $is_late ? '⚠️' : '✅'; ?> <?= $analisis; ?>
                                </span>
                            </td>
                            <td style="font-size: 11px;">
                                <i class="fas fa-map-marker-alt text-danger"></i> <?= $row['koordinat']; ?><br>
                                <span class="text-muted">Akurasi: <?= $row['akurasi_meter']; ?>m</span>
                            </td>
                            <td>
                                <div class="time-text"><?= date('H:i', strtotime($row['waktu_absen'])); ?> WIB</div>
                                <div class="text-muted" style="font-size: 11px;"><?= date('d-m-Y', strtotime($row['waktu_absen'])); ?></div>
                            </td>
                            <td class="text-center">
                                <?php if(!empty($foto) && file_exists("uploads/$foto")): ?>
                                    <img src="uploads/<?= $foto; ?>" class="img-absensi" onclick="window.open(this.src)">
                                <?php else: ?>
                                    <span class="text-muted small">Tidak Ada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="text-center py-4">
            <h6 style="font-family: 'Poppins', sans-serif; color: #070136; font-weight: 700; letter-spacing: 0.5px;">
                Dibuat oleh Aulia Annisa (auliaannnnn_)
            </h6>
            <p style="color: #070136; font-size: 11px; opacity: 0.7; font-family: 'Inter', sans-serif;">
                SIHADIR MPP BKPSDM &copy; 2026
            </p>
        </footer>
    </div>
</body>
</html>