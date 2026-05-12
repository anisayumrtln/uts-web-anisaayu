<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if (!empty($nama) && !empty($harga) && !empty($stok)) {
        $query = "INSERT INTO produk (nama_barang, harga, stok) VALUES ('$nama', '$harga', '$stok')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Data berhasil ditambah!'); window.location='dashboard.php';</script>";
        }
    } else {
        echo "Semua data wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang - UTS</title>
</head>
<body>
    <h2>Tambah Produk Minimarket</h2>
    <form method="POST" action="">
        Nama Barang: <input type="text" name="nama_barang" required><br><br>
        Harga: <input type="number" name="harga" required><br><br>
        Stok: <input type="number" name="stok" required><br><br>
        <button type="submit" name="submit">Simpan Produk</button>
    </form>
</body>
</html>