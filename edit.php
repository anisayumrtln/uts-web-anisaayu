<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$id = $_GET['id'];

$data  = mysqli_query($conn, "SELECT * FROM produk WHERE id = '$id'");
$row   = mysqli_fetch_array($data);

if (isset($_POST['update'])) {
    $nama  = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $query = mysqli_query($conn, "UPDATE produk SET nama_barang='$nama', harga='$harga', stok='$stok' WHERE id='$id'");

    if ($query) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='dashboard.php';</script>";
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang - UTS</title>
</head>
<body>
    <h2>Edit Data Barang</h2>
    <form method="POST">
        <label>Nama Barang:</label><br>
        <input type="text" name="nama_barang" value="<?= $row['nama_barang']; ?>" required><br><br>
        
        <label>Harga:</label><br>
        <input type="number" name="harga" value="<?= $row['harga']; ?>" required><br><br>
        
        <label>Stok:</label><br>
        <input type="number" name="stok" value="<?= $row['stok']; ?>" required><br><br>
        
        <button type="submit" name="update">Update Data</button>
        <a href="dashboard.php">Batal</a>
    </form>
</body>
</html>