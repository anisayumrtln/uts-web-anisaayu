<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Jakarta');
include 'koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Kasir - UTS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="header-modern">
    <div class="header-left">
        <h2>DATA PRODUK MINIMARKET</h2>
        <span class="user-badge">Kasir: <strong><?= $_SESSION['username']; ?></strong></span>
    </div>
    <div class="header-right">
        <a href="logout.php" class="btn-logout">LOGOUT</a>
    </div>
</div>

<div class="stats-action-row">
    <div class="stat-card blue">
        <div class="stat-content">
            <span>Total Stok</span>
            <h3>
                <?php 
                    $total_stok = mysqli_query($conn, "SELECT SUM(stok) as total FROM produk");
                    $data = mysqli_fetch_assoc($total_stok);
                    echo number_format($data['total'] ?? 0, 0, ',', '.');
                ?>
            </h3>
        </div>
        <div class="stat-icon">📦</div>
    </div>

    <div class="stat-card green">
        <div class="stat-content">
            <span>Jenis Produk</span>
            <h3>
                <?php 
                    $total_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
                    $data = mysqli_fetch_assoc($total_produk);
                    echo $data['total'] ?? 0;
                ?>
            </h3>
        </div>
        <div class="stat-icon">🛍️</div>
    </div>

    <a href="tambah.php" class="btn-tambah-new">
        + TAMBAH BARANG BARU
    </a>
</div>

        <form action="proses_beli_banyak.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th>Pilih</th> <th>No</th>
                    <th>Gambar</th> 
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah Beli</th> <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                while($row = mysqli_fetch_assoc($query)) : 
                ?>
                <tr>
                    <td style="text-align: center;">
                        <input type="checkbox" name="id_barang[]" value="<?= $row['id']; ?>">
                    </td>   
                    <td><?= $no++; ?></td>
                    <td>
                      <img src="img/<?= $row['nama_barang']; ?>.jpeg" width="60" style="border-radius: 8px; border: 1px solid #ddd; object-fit: cover;">
                    </td>
                    <td><?= $row['nama_barang']; ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td>
                        <input type="number" name="jumlah[<?= $row['id']; ?>]" value="1" min="1" max="<?= $row['stok']; ?>" style="width: 50px;">
                    </td>
                    <td><?= $row['stok']; ?></td>
                    <td>
                        <a href="jual.php?id=<?= $row['id']; ?>" class="btn-jual">Jual</a>
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                        <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

            <button type="submit" class="btn tambah" style="margin-top: 20px; cursor: pointer;">
                🛒 Beli Barang yang Dipilih & Cetak Struk
            </button>
    </form>
</div>