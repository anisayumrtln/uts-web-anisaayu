<?php
include 'koneksi.php';

$data = isset($_GET['data']) ? $_GET['data'] : '';

if ($data == '') {
    header("location:dashboard.php");
    exit;
}

$items = explode(',', $data);
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
<body onload="window.print()">

    <div class="header">
        <strong>MINIMARKET ANISA</strong><br>
        Jl. Jambangan-Suroboyo<br>
        <?= date('d/m/Y H:i:s'); ?>
    </div>

    <div class="line"></div>

    <?php 
    foreach ($items as $item) : 
        $pecah = explode(':', $item); 
        $id = $pecah[0];
        $qty = $pecah[1];

        $query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
        $row = mysqli_fetch_assoc($query);
        
        $subtotal = $row['harga'] * $qty;
        $total_akhir += $subtotal;
    ?>
        <div class="item-row">
            <span><?= $row['nama_barang']; ?> (x<?= $qty ?>)</span>
            <span>Rp <?= number_format($subtotal, 0, ',', '.'); ?></span>
        </div>
    <?php endforeach; ?>

    <div class="line"></div>

    <div class="item-row total">
        <span>TOTAL</span>
        <span>Rp <?= number_format($total_akhir, 0, ',', '.'); ?></span>
    </div>

    <div class="footer">
        <p>Terima Kasih Telah Belanja!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>

    <div class="no-print">
        <a href="dashboard.php" class="btn">Kembali ke Dashboard</a>
    </div>

</body>
</html>