<?php
include 'koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kasir - UTS</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .tambah { padding: 10px; background: blue; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>Daftar Produk Minimarket</h2>
    <a href="tambah.php" class="tambah">[+] Tambah Barang Baru</a>
    
    <table>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th> </tr>

        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['nama_barang']; ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
            <td><?= $row['stok']; ?></td>
            <td>
                <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
        </td>
        </tr>
        <?php endwhile; 
        ?>
    </table>
</body>
</html>
