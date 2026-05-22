<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include 'koneksi.php';

$id = $_GET['id'] ?? 0;
$qty = (int)($_GET['qty'] ?? 1);
$metode = $_GET['metode'] ?? 'Tunai';
$uang_bayar = (int)($_GET['uang_bayar'] ?? 0);
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Produk tidak ditemukan!";
    exit;
}

$total = $data['harga'] * $qty;

if ($metode === 'QRIS') {
    $uang_bayar = $total;
}
$kembalian = $uang_bayar - $total;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 300px; margin: 20px auto; font-size: 14px; }
        .header { text-align: center; margin-bottom: 10px; }
        .line { border-top: 1px dashed #000; margin: 10px 0; }
        .item-row { display: flex; justify-content: space-between; margin: 5px 0; }
        .total { font-weight: bold; margin-top: 10px; border-top: 1px solid #000; padding-top: 5px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        @media print { .no-print { display: none; } }
        .btn { display: block; width: 100%; padding: 10px; background: #6c757d; color: white; text-align: center; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="header">
        <img src="img/logo_toko.jpeg" style="width: 80px; height: 80px; object-fit: contain; margin-bottom: 10px;">
        <h3 style="margin: 5px 0; font-weight: bold;">MINIMARKET ANISA</h3>
        <p style="margin: 0;">Jl. Jambangan-Suroboyo</p>
        <p style="margin: 0;"><?= date('d/m/Y H:i:s'); ?></p>
        <p style="margin: 0;">Kasir: <?= $_SESSION['username'] ?? 'Anisa'; ?></p>
    </div>

    <div class="line"></div>

    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-top: 5px;">
        <span style="font-weight: bold;"><?= $data['nama_barang']; ?></span>
        <span>Rp <?= number_format($total, 0, ',', '.'); ?></span>
    </div>

    <?php if ($qty > 1): ?>
        <div style="font-size: 12px; color: #555; padding-left: 5px; margin-bottom: 5px; text-align: left;">
            x<?= $qty; ?> @ Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
        </div>
    <?php endif; ?>

    <div class="line"></div>

    <div class="item-row" style="font-weight: bold;">
        <span>TOTAL</span>
        <span>Rp <?= number_format($total, 0, ',', '.'); ?></span>
    </div>
    
    <div class="item-row">
        <span>METODE BAYAR</span>
        <span style="font-weight: bold; text-transform: uppercase;"><?= $metode; ?></span>
    </div>

    <?php if ($metode === 'Tunai' || $metode === 'Tentunya') : ?>
        <div class="item-row">
            <span>TUNAI (CASH)</span>
            <span>Rp <?= number_format($uang_bayar, 0, ',', '.'); ?></span>
        </div>
        <div class="line" style="border-top: 1px dotted #000; margin: 5px 0;"></div>
        <div class="item-row" style="font-weight: bold; font-size: 15px;">
            <span>KEMBALIAN</span>
            <span>Rp <?= number_format($kembalian >= 0 ? $kembalian : 0, 0, ',', '.'); ?></span>
        </div>
    <?php else : ?>
        <div class="item-row" style="font-style: italic; color: #555;">
            <span>STATUS</span>
            <span>LUNAS (NON-TUNAI)</span>
        </div>
    <?php endif; ?>

    <div class="line"></div>

    <div class="footer">
        <p>Terima Kasih Telah Belanja!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>

    <div class="no-print">
        <a href="#" onclick="window.print()" class="btn" style="background: #28a745; margin-bottom: 10px;">Cetak Struk Sekarang</a>
        <a href="dashboard.php" class="btn">Kembali ke Dashboard</a>
    </div>

    <script>window.print();</script>
</body>
</html>