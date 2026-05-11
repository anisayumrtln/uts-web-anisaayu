<?php
include 'koneksi.php';
$id = $_GET['id'];

// 1. Kurangi stok di database
mysqli_query($conn, "UPDATE produk SET stok = stok - 1 WHERE id = $id");

// 2. Langsung lempar ke halaman struk biar bisa dicetak
header("location:struk.php?id=$id");
?>