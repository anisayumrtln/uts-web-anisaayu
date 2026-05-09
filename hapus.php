<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = mysqli_query($conn, "DELETE FROM produk WHERE id = '$id'");

if ($query) {
    echo "<script>alert('Data berhasil dihapus!'); window.location='dashboard.php';</script>";
} else {
    echo "Gagal menghapus data: " . mysqli_error($conn);
}
?>