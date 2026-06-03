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

if (empty($id_barang_pilih)) {
    echo "<script>alert('Pilih barang terlebih dahulu!'); window.location='transaksi.php';</script>";
    exit;
}

$total_akhir = 0;
foreach ($id_barang_pilih as $id) {
    $qty = (int)$jumlah_pilih[$id];
    $query = mysqli_query($conn, "SELECT harga FROM produk WHERE id = $id");
    $row = mysqli_fetch_assoc($query);
    $total_akhir += ($row['harga'] * $qty);
}

if ($metode == 'Tunai') {
    ?>
    <form id='formLangsung' action='struk_banyak.php' method='POST'>
        <input type='hidden' name='metode_bayar' value='Tunai'>
        <input type='hidden' name='uang_dibayar' value='<?= $uang_bayar_raw; ?>'>
        <?php foreach ($id_barang_pilih as $id) { ?>
            <input type='hidden' name='id_barang[]' value='<?= $id; ?>'>
            <input type='hidden' name='jumlah[<?= $id; ?>]' value='<?= $jumlah_pilih[$id]; ?>'>
        <?php } ?>
    </form>
    <script>document.getElementById('formLangsung').submit();</script>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Pembayaran Non-Tunai</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .payment-box {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
            box-sizing: border-box;
        }
        .total-tagihan {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        .btn-konfirmasi {
            width: 100%;
            padding: 14px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 25px;
        }
        .btn-konfirmasi:hover { background: #218838; }
        .btn-batal {
            display: inline-block;
            margin-top: 15px;
            color: #dc3545;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="payment-box">
    <img src="img/logo_toko.jpeg" style="width: 70px; height: 70px; object-fit: contain; margin-bottom: 10px;">
    <h2 style="margin: 0; color: #1E104E;">Payment Gateway</h2>
    <p style="color: #666; margin: 5px 0 20px 0;">Sistem Pembayaran Elektronik Minimarket Anisa</p>
    
    <div style="font-size: 14px; color: #555;">TOTAL TAGIHAN</div>
    <div class="total-tagihan">Rp <?= number_format($total_akhir, 0, ',', '.'); ?></div>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

    <?php if ($metode == 'QRIS'): ?>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border: 1px solid #e9ecef;">
            <p style="margin: 0 0 15px 0; font-weight: bold; color: #333;">Silakan Scan QRIS Melalui E-Wallet / Mobile Banking:</p>
            <img src="img/qris.jpeg" alt="QRIS Code" style="width: 220px; border: 4px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.05); background: white;">
            <p style="font-size: 11px; color: #777; margin: 10px 0 0 0;">NMID: ID102030405060 - MINIMARKET ANISA</p>
        </div>

    <?php elseif ($metode == 'Transfer'): ?>
        <div style="background: #f8f9fa; padding: 25px; border-radius: 12px; border: 1px solid #e9ecef; text-align: left;">
            <p style="margin: 0 0 15px 0; font-weight: bold; color: #333; text-align: center;">Silakan Transfer ke Rekening Toko:</p>
            <div style="margin-bottom: 10px;">
                <span style="font-size: 12px; color: #777; display: block;">Nama Bank:</span>
                <strong style="font-size: 16px; color: #1E104E;">Bank BRI</strong>
            </div>
            <div style="margin-bottom: 10px;">
                <span style="font-size: 12px; color: #777; display: block;">Nomor Rekening:</span>
                <strong style="font-size: 20px; color: #333; font-family: monospace; letter-spacing: 1px;">1234567890</strong>
            </div>
            <div>
                <span style="font-size: 12px; color: #777; display: block;">Nama Penerima:</span>
                <strong style="font-size: 16px; color: #333;">Anisa Ayu Martalina</strong>
            </div>
        </div>
    <?php endif; ?>

    <form action="struk_banyak.php" method="POST">
        <input type="hidden" name="metode_bayar" value="<?= $metode; ?>">
        <input type="hidden" name="uang_dibayar" value="<?= $uang_bayar_raw; ?>">
        <?php foreach ($id_barang_pilih as $id) { ?>
            <input type="hidden" name="id_barang[]" value="<?= $id; ?>">
            <input type="hidden" name="jumlah[<?= $id; ?>]" value="<?= $jumlah_pilih[$id]; ?>">
        <?php } ?>
        
        <button type="submit" class="btn-konfirmasi">✅ Pembayaran Sukses & Cetak Struk</button>
    </form>

    <a href="transaksi.php" class="btn-batal">❌ Batalkan Transaksi</a>
</div>

</body>
</html>