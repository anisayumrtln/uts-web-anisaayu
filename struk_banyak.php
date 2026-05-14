<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Jakarta');
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
<body 

    <div style="text-align: center; margin-bottom: 20px;">
        <img src="img/logo_toko.jpeg" style="width: 80px; margin-bottom: 10px;">
        <p style="margin: 0;">Jl. Jambangan-Suroboyo</p>
        <p style="margin: 0;"><?= date('d/m/Y H:i:s'); ?></p>
        <p style="margin: 0;">Kasir: <?= $_SESSION['username']; ?></p>
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
    <div class="item-row no-print" style="margin-top: 10px;">
        <span>Uang Bayar:</span>
        <input type="number" id="uang_bayar" oninput="hitungKembalian(<?= $total_akhir ?>)" placeholder="Masukkan angka..." style="width: 100px; font-family: monospace;">
    </div>

    <div class="line"></div>

    <div class="item-row">
        <span>Kembalian:</span>
        <span id="teks_kembalian" style="font-weight: bold;">Rp 0</span>
    </div>

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