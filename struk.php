<?php
include 'koneksi.php';
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 300px; margin: 20px auto; }
        .header { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .item { margin-top: 10px; display: flex; justify-content: space-between; }
        .footer { text-align: center; border-top: 1px dashed #000; margin-top: 20px; padding-top: 10px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()"> <div class="header">
        <h3>MINIMARKET ANISA</h3>
        <p>Jl. Informatika No. 12024</p>
        <p><?= date('d/m/Y H:i:s'); ?></p>
    </div>

    <div class="item">
        <span><?= $data['nama_barang']; ?></span>
        <span>Rp <?= number_format($data['harga'], 0, ',', '.'); ?></span>
    </div>
    
    <div class="item" style="font-weight: bold;">
        <span>TOTAL</span>
        <span>Rp <?= number_format($data['harga'], 0, ',', '.'); ?></span>
    </div>

    <div class="footer">
        <p>Terima Kasih Telah Belanja!</p>
        <p>Barang yang sudah dibeli<br>tidak dapat ditukar kembali.</p>
        <button class="no-print" onclick="window.location.href='dashboard.php'">Kembali ke Dashboard</button>
    </div>
</body>
</html>