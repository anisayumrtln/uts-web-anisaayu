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

if (empty($id_barang_pilih)) {
    echo "<script>alert('Pilih barang terlebih dahulu!'); window.location='dashboard.php';</script>";
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

    <?php if ($metode == 'Tunai') : ?>
        <div class="item-row no-print" style="margin-top: 5px; margin-bottom: 5px;">
            <span>TUNAI (INPUT)</span>
            <input type="text" id="uang_bayar" placeholder="Masukkan angka..." style="width: 110px; font-family: monospace; text-align: right;">
        </div>
        
        <div class="line" style="border-top: 1px dotted #000; margin: 5px 0;"></div>
        
        <div class="item-row" style="font-weight: bold; font-size: 15px;">
            <span>KEMBALIAN</span>
            <span id="teks_kembalian">Rp 0</span>
        </div>
    <?php endif; ?>

    <div class="line"></div>

    <?php if ($metode == 'QRIS'): ?>
        <div style="text-align: center; margin-top: 15px;">
            <p style="margin: 5px 0;"><strong>Silakan Scan QRIS di Bawah:</strong></p>
            <img src="img/qris.jpeg" alt="QRIS Pembayaran" style="width: 160px; border: 1px solid #ddd; padding: 5px;">
            <p style="font-size: 10px; color: #555; margin-top: 5px;">Minimarket Anisa - Payment Gateway</p>
        </div>
        <div class="line"></div>
    <?php elseif ($metode == 'Transfer'): ?>
        <div style="text-align: center; margin-top: 15px;">
            <p style="margin: 5px 0;"><strong>Transfer ke Rekening:</strong></p>
            <p style="margin: 0; font-weight: bold;">BCA: 1234567890</p>
            <p style="margin: 0;">Anisa Ayu</p>
        </div>
        <div class="line"></div>
    <?php endif; ?>

    <div class="footer">
        <p>Terima Kasih Telah Belanja!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>

    <div class="no-print">
        <a href="#" onclick="window.print()" class="btn" style="background: #28a745; margin-bottom: 10px;">Cetak Struk Sekarang</a>
        <a href="dashboard.php" class="btn">Kembali ke Dashboard</a>
    </div>

    <script>
    const inputBayar = document.getElementById('uang_bayar');
    if (inputBayar) {
        inputBayar.addEventListener('keyup', function(e) {
            let nomor = this.value.replace(/[^,\d]/g, '').toString();
            let split = nomor.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            this.value = rupiah;
        
            hitungKembalian(<?= $total_akhir ?>);
        });
    }

    function hitungKembalian(total) {
        let bayarRaw = document.getElementById('uang_bayar').value.replace(/\./g, '');
        let bayar = parseInt(bayarRaw) || 0;
    
        let kembali = bayar - total;
        let teks = document.getElementById('teks_kembalian');

        if (bayar === 0) {
            teks.innerText = "Rp 0";
        } else if (kembali >= 0) {
            teks.innerText = "Rp " + kembali.toLocaleString('id-ID');
        } else {
            teks.innerText = "Rp 0 (Uang Kurang!)";
        }
    }
    </script>

</body>
</html>