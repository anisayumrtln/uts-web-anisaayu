<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
include 'koneksi.php';

if (isset($_POST['import'])) {
    $file_tmp = $_FILES['file_excel']['tmp_name'];
    
    $spreadsheet = IOFactory::load($file_tmp);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    
    for ($i = 2; $i <= count($sheetData); $i++) {
    $nama_barang = mysqli_real_escape_string($conn, $sheetData[$i]['B']); 
    $kategori    = mysqli_real_escape_string($conn, $sheetData[$i]['C']); 
    $harga       = (int)$sheetData[$i]['D'];                             
    $stok        = (int)$sheetData[$i]['E'];                             
    
    if (!empty($nama_barang)) {
        
        $cek_produk = mysqli_query($conn, "SELECT id FROM produk WHERE nama_barang = '$nama_barang'");
        
        if (mysqli_num_rows($cek_produk) > 0) {
        
            $query = "UPDATE produk SET kategori='$kategori', harga='$harga', stok='$stok' WHERE nama_barang='$nama_barang'";
        } else {
        
            $query = "INSERT INTO produk (nama_barang, kategori, harga, stok) 
                      VALUES ('$nama_barang', '$kategori', '$harga', '$stok')";
        }
        mysqli_query($conn, $query);
    }
}
    echo "<script>alert('Data Produk Berhasil di-Import!'); window.location='kelola_produk.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data Produk</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            width: 90%;
            max-width: 550px;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            text-align: center;
        }
        h2 {
            color: #1E104E; 
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 24px;
        }
        p {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .form-group {
            background: #f8f9fa;
            border: 2px dashed #ccc;
            padding: 30px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        .form-group:hover {
            border-color: #007bff;
            background: #f1f7ff;
        }
        input[type="file"] {
            font-size: 14px;
            color: #555;
        }
        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .btn {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            box-sizing: border-box;
            display: inline-block;
        }
        .btn-import {
            background: #007bff;
            color: white;
            box-shadow: 0 2px 4px rgba(0,123,255,0.2);
        }
        .btn-import:hover {
            background: #0056b3;
        }
        .btn-kembali {
            background: #6c757d;
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .btn-kembali:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📥 Import Data via Excel</h2>
    <p>Silakan pilih file Excel (.xlsx / .xls) yang berisi format data produk minimarket yang sesuai untuk diunggah.</p>
    
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <input type="file" name="file_excel" accept=".xlsx, .xls" required>
        </div>
        
        <div class="btn-container">
            <button type="submit" name="import" class="btn btn-import">🚀 Mulai Proses Import</button>
            <a href="kelola_produk.php" class="btn btn-kembali">⬅️ Kembali ke Kelola Produk</a>
        </div>
    </form>
</div>

</body>
</html>