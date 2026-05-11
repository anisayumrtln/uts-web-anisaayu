<?php
include 'koneksi.php';
$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT stok FROM produk WHERE id=$id");
$data = mysqli_fetch_assoc($result);

if ($data['stok'] > 0) {
    mysqli_query($conn, "UPDATE produk SET stok = stok - 1 WHERE id=$id");
    header("location:dashboard.php?pesan=berhasil_jual");
} else {
    header("location:dashboard.php?pesan=stok_habis");
}
?>