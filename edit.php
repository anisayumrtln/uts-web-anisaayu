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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang - UTS Anisa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Tambahan khusus untuk form edit agar rapi di tengah */
        .container {
            background-color: white;
            padding: 30px;
            border: 2px solid #333;
            box-shadow: 10px 10px 0px #333;
            max-width: 400px;
            margin: 50px auto;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #333;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #1E104E;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center; color: #1E104E;">Edit Data Barang</h2>
        <form method="POST">
            <label>Nama Barang:</label>
            <input type="text" name="nama_barang" value="<?= $row['nama_barang']; ?>" required>
            
            <label>Harga:</label>
            <input type="number" name="harga" value="<?= $row['harga']; ?>" required>
            
            <label>Stok:</label>
            <input type="number" name="stok" value="<?= $row['stok']; ?>" required>
            
            <button type="submit" name="update">SIMPAN PERUBAHAN</button>
            <a href="dashboard.php" style="display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none;">Kembali</a>
        </form>
    </div>
</body>
</html>