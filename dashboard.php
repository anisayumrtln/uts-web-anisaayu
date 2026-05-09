<?php
include 'koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir - UTS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #F8FAFC; }
        table { border-collapse: collapse; width: 100%; background-color: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #020202; padding: 12px; text-align: left; }
        th { background-color: #89A8B2; color: white; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #B3C8CF; }
        tr:nth-child(odd) { background-color: #E9ECEF; }
        tr:hover { background-color: #F0EBE3; }
        
        .tambah { 
            display: inline-block; padding: 10px 15px; background-color: #81A6C6; 
            color: white; text-decoration: none; border-radius: 5px; margin-bottom: 15px; font-weight: bold;
        }
        
        .btn-edit { background-color: #9AA6B2; color: black; padding: 5px 10px; border-radius: 4px; text-decoration: none; margin-right: 5px; }
        .btn-hapus { background-color: #BCCCDC; color: black; padding: 5px 10px; border-radius: 4px; text-decoration: none; }
    </style>
</head>
<body>

    <h2 style="text-align: center; font-size: 32px; color: #1E104E;">Daftar Produk Minimarket</h2>
    <a href="tambah.php" class="tambah">[+] Tambah Barang Baru</a>
    
    <table>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th> </tr>
<?php 
$no = 1; 
while($row = mysqli_fetch_assoc($query)) : 
?>

        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['nama_barang']; ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
            <td><?= $row['stok']; ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
<?php endwhile; 
?>

    </table>
</body>
</html>
