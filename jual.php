<?php
include 'koneksi.php';
$id = $_GET['id'];

mysqli_query($conn, "UPDATE produk SET stok = stok - 1 WHERE id = $id");

header("location:struk.php?id=$id");
?>