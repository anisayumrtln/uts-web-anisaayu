<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Jakarta');
include 'koneksi.php';

$metode = $_POST['metode_bayar'] ?? 'Tunai';
$id_barang_pilih = $_POST['id_barang'] ?? [];
$jumlah_pilih = $_POST['jumlah'] ?? [];
$uang_bayar_raw = $_POST['uang_dibayar'] ?? '0';
$uang_bayar_angka = (int)str_replace('.', '', $uang_bayar_raw);

if (empty($id_barang_pilih)) {
    echo "<script>alert('Pilih barang terlebih dahulu!'); window.location='transaksi.php';</script>";
    exit;
}

$items = [];
foreach ($id_barang_pilih as $id) {
    $qty = $jumlah_pilih[$id];
    $items[] = "$id:$qty";
}

$total_akhir = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <style>
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 300px; 
            margin: 20px auto; 
            font-size: 14px;
        }
        .header { text-align: center; margin-bottom: 10px; }
        .line { border-top: 1px dashed #000; margin: 10px 0; }
        .item-row { display: flex; justify-content: space-between; margin: 5px 0; }
        .total { font-weight: bold; margin-top: 10px; border-top: 1px solid #000; padding-top: 5px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        
        @media print {
            .no-print { display: none; }
        }
        
        .btn {
            display: block; width: 100%; padding: 10px; background: #6c757d;
            color: white; text-align: center; text-decoration: none; border-radius: 5px; margin-top: 20px;
        }
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

    <?php 
    foreach ($items as $item) : 
        $pecah = explode(':', $item); 
        $id = $pecah[0];
        $qty = (int)$pecah[1];

        $query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
        $row = mysqli_fetch_assoc($query);
        
        $subtotal = $row['harga'] * $qty;
        $total_akhir += $subtotal;
    ?>
        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-top: 5px;">
            <span style="font-weight: bold;"><?= $row['nama_barang']; ?></span>
            <span>Rp <?= number_format($subtotal, 0, ',', '.'); ?></span>
        </div>
        
        <?php if ($qty > 1): ?>
            <div style="font-size: 12px; color: #555; padding-left: 5px; margin-bottom: 5px; text-align: left;">
                x<?= $qty; ?> @ Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
            </div>
        <?php endif; ?>
        
    <?php endforeach; ?>

    <div class="line"></div>

    <div class="item-row" style="font-weight: bold;">
        <span>TOTAL</span>
        <span>Rp <?= number_format($total_akhir, 0, ',', '.'); ?></span>
    </div>

    <div class="item-row">
        <span>METODE BAYAR</span>
        <span style="font-weight: bold; text-transform: uppercase;"><?= $metode; ?></span>
    </div>

    <?php if ($metode == 'Tunai') : 
        $kembalian_akhir = $uang_bayar_angka - $total_akhir;
    ?>
        <div class="item-row" style="margin-top: 5px; margin-bottom: 5px;">
            <span>TUNAI</span>
            <span>Rp <?= number_format($uang_bayar_angka, 0, ',', '.'); ?></span>
        </div>
        
        <div class="line" style="border-top: 1px dotted #000; margin: 5px 0;"></div>
        
        <div class="item-row" style="font-weight: bold; font-size: 15px;">
            <span>KEMBALIAN</span>
            <span>
                <?php if ($kembalian_akhir >= 0) : ?>
                    Rp <?= number_format($kembalian_akhir, 0, ',', '.'); ?>
                <?php else : ?>
                    Rp 0 (Uang Kurang!)
                <?php endif; ?>
            </span>
        </div>
    <?php endif; ?>

    <div class="line"></div>

    <div class="footer">
        <p>Terima Kasih Telah Belanja!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>

    <div class="no-print">
        <a href="#" onclick="window.print()" class="btn" style="background: #28a745; margin-bottom: 10px; font-weight: bold;">Cetak Struk Sekarang</a>
        <a href="transaksi.php" class="btn">Kembali ke Transaksi</a>
    </div>

</body>
</html>