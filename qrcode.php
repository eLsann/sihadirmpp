<?php
/**
 * SIHADIR MPP - Halaman QR Code
 * Scan QR ini untuk langsung ke halaman absensi
 */
include 'config.php';

// URL Cloudflare Tunnel - GANTI JIKA URL BERUBAH
$absen_url = "https://refurbished-inspection-income-manager.trycloudflare.com/sihadirmpp/index.php";

// Untuk auto-detect (jika tidak pakai tunnel), uncomment baris di bawah:
// $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
// $host = $_SERVER['HTTP_HOST'];
// $path = dirname($_SERVER['PHP_SELF']);
// $absen_url = $protocol . '://' . $host . $path . '/index.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - SIHADIR MPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1b05e4 0%, #070136 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            padding: 20px;
        }
        .qr-card {
            background: white;
            border-radius: 30px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 450px;
            width: 100%;
        }
        .qr-card h1 {
            color: #070136;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .qr-card p {
            color: #666;
            margin-bottom: 30px;
        }
        .qr-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 20px;
        }
        .qr-container img {
            max-width: 250px;
            width: 100%;
        }
        .url-display {
            background: #e8f4ff;
            padding: 15px;
            border-radius: 10px;
            word-break: break-all;
            font-size: 0.85rem;
            color: #1b05e4;
            margin-bottom: 20px;
        }
        .btn-download {
            background: linear-gradient(to right, #1b05e4, #070136);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }
        .btn-download:hover {
            color: white;
            opacity: 0.9;
        }
        .instructions {
            margin-top: 30px;
            text-align: left;
            background: #fff8e1;
            padding: 20px;
            border-radius: 15px;
            font-size: 0.9rem;
        }
        .instructions h5 {
            color: #f57c00;
            margin-bottom: 10px;
        }
        .instructions ol {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>

<div class="qr-card">
    <img src="https://cdn-icons-png.flaticon.com/512/2666/2666505.png" alt="Logo" width="60" style="margin-bottom: 15px;">
    <h1>SIHADIR MPP</h1>
    <p>Scan QR Code di bawah untuk absensi</p>
    
    <div class="qr-container">
        <!-- QR Code dari API gratis -->
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=<?= urlencode($absen_url) ?>" 
             alt="QR Code Absensi" id="qrImage">
    </div>
    
    <div class="url-display">
        <strong>URL:</strong> <?= htmlspecialchars($absen_url) ?>
    </div>
    
    <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=<?= urlencode($absen_url) ?>&format=png" 
       download="QR_SIHADIR_MPP.png" class="btn-download">
        📥 Download QR Code
    </a>
    
    <div class="instructions">
        <h5>📱 Cara Pakai:</h5>
        <ol>
            <li>Buka kamera HP atau aplikasi QR Scanner</li>
            <li>Arahkan ke QR Code di atas</li>
            <li>Klik link yang muncul</li>
            <li>Login dan lakukan absensi!</li>
        </ol>
    </div>
</div>

</body>
</html>
