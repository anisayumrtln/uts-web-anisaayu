<?php
date_default_timezone_set('Asia/Jakarta');
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

    <div style="background-color: white; padding: 15px; border-radius: 10px; margin: 20px auto; width: 95%; border: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="text-align: left;">
            <form method="POST" style="display: flex; flex-direction: column; gap: 5px;">
                <label style="color: #666; font-size: 14px;">Petugas Kasir:</label>
                <div style="display: flex; gap: 5px;">
                    <input type="text" name="nama_baru" placeholder="Ketik nama..." style="padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                    <button type="submit" style="padding: 5px 10px; background-color: #1E104E; color: white; border: none; border-radius: 4px; cursor: pointer;">Simpan</button>
                </div>
                <strong style="font-size: 18px; color: #1E104E; margin-top: 5px;">
                    <?php 
                        $tampil_nama = isset($_POST['nama_baru']) && !empty($_POST['nama_baru']) ? $_POST['nama_baru'] : "Anisa Ayu";
                        echo $tampil_nama; 
                    ?>
                </strong>
            </form>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0; color: #666; font-size: 14px;">Waktu:</p>
            <strong style="font-size: 16px; color: #1E104E;"><?php echo date('d F Y | H:i'); ?></strong>
        </div>
    </div>

    <a href="tambah.php" class="tambah">[+] Tambah Barang Baru</a>
    <form action="proses_beli_banyak.php" method="POST">

<table>
    <thead>
        <tr>
            <th>Pilih</th>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Jumlah</th> <th>Stok</th>
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
                <input type="checkbox" name="id_barang[]" value="<?= $row['id']; ?>" style="transform: scale(1.2);">
            </td>
            <td><?= $no++; ?></td>
            <td><?= $row['nama_barang']; ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
            <td>
                <input type="number" name="jumlah[<?= $row['id']; ?>]" value="1" min="1" max="<?= $row['stok']; ?>" style="width: 50px;">
            </td>
            <td><?= $row['stok']; ?></td>
            <td>
                <a href="jual.php?id=<?= $row['id']; ?>" class="btn-jual">Jual Saja</a>
                <a href="edit.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
    
</table>
        <div style="text-align: right;">

            <button type="submit" class="btn-beli-banyak">
                🛒 Beli Barang yang Dipilih & Cetak Struk
            </button>
        </div>
    </form>

</body>
</html>