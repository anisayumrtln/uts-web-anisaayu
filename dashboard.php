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
        <img src="img/logo_toko.jpeg" alt="Logo Minimarket" style="width: 60px; height: 60px; object-fit: contain; border-radius: 8px;">
        <h2 style="margin: 0;">DATA PRODUK MINIMARKET</h2>
        <span class="user-badge">Kasir: <strong><?= $_SESSION['username']; ?></strong></span>
        <span id="jam" style="margin-left: 15px; font-weight: bold; color: #89A8B2;"></span>

        <script>
            function updateJam() {
                const now = new Date();
                document.getElementById('jam').innerText = now.toLocaleTimeString('id-ID');
            }
            setInterval(updateJam, 1000);
        </script>

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
    <button onclick="window.print()" class="btn-tambah-new" style="background-color: #4A3F75; margin-left: 10px; cursor: pointer;">
        🖨️ CETAK DATA
    </button>
    </div>

    <div class="search-container" style="margin-bottom: 15px;">
        <input type="text" id="inputCari" onkeyup="cariBarang()" placeholder="🔍 Cari nama barang..." class="search-input" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd;">
    </div>

    <div class="category-filters">
        <button onclick="filterKategori('Semua')" class="btn-filter bg-semua">Semua</button>
        <button onclick="filterKategori('Kebutuhan Pokok')" class="btn-filter bg-pokok">Kebutuhan Pokok</button>
        <button onclick="filterKategori('Makanan')" class="btn-filter bg-makanan">Makanan</button>
        <button onclick="filterKategori('Minuman')" class="btn-filter bg-minuman">Minuman</button>
        <button onclick="filterKategori('Kesehatan & Kecantikan')" class="btn-filter bg-kecantikan">Kesehatan & Kecantikan</button>
    </div>

    <form action="struk_banyak.php" method="POST">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Pilih</th> 
                    <th>No</th>
                    <th>Gambar</th> 
                    <th>Nama Barang</th>
                    <th>Kategori</th> <th>Harga</th>
                    <th>Jumlah Beli</th> 
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
    
    <script>
    function cariBarang() {
        var input = document.getElementById("inputCari");
        var filter = input.value.toUpperCase();
        var table = document.querySelector("table");
        var tr = table.getElementsByTagName("tr");

        for (var i = 1; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName("td")[3]; 
            if (td) {
                var txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function filterKategori(kat) {
        var table = document.querySelector("table");
        var tr = table.getElementsByTagName("tr");

        for (var i = 1; i < tr.length; i++) {
            var tdKategori = tr[i].getElementsByTagName("td")[4]; // Indeks 4 adalah kolom Kategori
            if (tdKategori) {
                var txtValue = tdKategori.textContent || tdKategori.innerText;
                if (kat === "Semua" || txtValue === kat) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    </script>
</body>
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
                    <td><?= $row['kategori']; ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td>
                        <input type="number" name="jumlah[<?= $row['id']; ?>]" value="1" min="1" max="<?= $row['stok']; ?>" style="width: 50px;">
                    </td>
                    <td style="<?= ($row['stok'] < 15) ? 'color: red; font-weight: bold;' : ''; ?>">
                        <?= $row['stok']; ?>
                        <?php if($row['stok'] < 15): ?>
                        <br><small style="font-size: 10px;">(Stok Tipis!)</small>
                        <?php endif; ?>
                    </td>

                    <td class="action-columns"> <a href="jual.php?id=<?= $row['id']; ?>" class="btn-jual">Jual</a>
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                        <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin?')">Hapus</a>
                    </td>

                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <label for="metode_bayar"><strong>Metode Pembayaran:</strong></label>
            <select name="metode_bayar" id="metode_bayar" style="padding: 5px; border-radius: 5px;">
                <option value="Tunai">Tunai</option>
                <option value="QRIS">QRIS</option>
                <option value="Transfer">Transfer Bank</option>
            </select>
        </div>

        <button type="submit" class="btn tambah" style="margin-top: 20px; cursor: pointer;">
            🛒 Beli Barang yang Dipilih & Cetak Struk
        </button>
    </form>
</div>