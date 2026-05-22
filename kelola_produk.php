<?php 
ob_start();
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (isset($_POST['tambah_produk'])) {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']); 
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];

    $insert = mysqli_query($conn, "INSERT INTO produk (nama_barang, kategori, harga, stok) VALUES ('$nama_barang', '$kategori', '$harga', '$stok')");
    if ($insert) {
        header("Location: kelola_produk.php?status=sukses_tambah");
        exit;
    }
}

if (isset($_POST['edit_produk'])) {
    $id = (int)$_POST['id'];
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']); 
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];

    $update = mysqli_query($conn, "UPDATE produk SET nama_barang='$nama_barang', kategori='$kategori', harga='$harga', stok='$stok' WHERE id=$id");
    if ($update) {
        header("Location: kelola_produk.php?status=sukses_edit");
        exit;
    }
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $delete = mysqli_query($conn, "DELETE FROM produk WHERE id=$id");
    if ($delete) {
        header("Location: kelola_produk.php?status=sukses_hapus");
        exit;
    }
}

include 'layouts/header.php'; 
include 'layouts/navbar.php'; 
?>

<div style="padding-top: 130px; padding-bottom: 5px; background-color: #f4f7f6; min-height: 100vh;">
    <div style="width: 90%; max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        <h2 style="font-weight: bold; color: #1E104E; margin-bottom: 25px; text-align: center;">Data Produk & Manajemen Stok Barang</h2>

        <?php if (isset($_GET['status'])): ?>
            <div style="padding: 12px; margin-bottom: 20px; border-radius: 6px; font-weight: 500; text-align: center;
                <?= $_GET['status'] == 'sukses_tambah' ? 'background:#d4edda; color:#155724;' : '' ?>
                <?= $_GET['status'] == 'sukses_edit' ? 'background:#cce5ff; color:#004085;' : '' ?>
                <?= $_GET['status'] == 'sukses_hapus' ? 'background:#f8d7da; color:#721c24;' : '' ?>">
                <?php 
                    if($_GET['status'] == 'sukses_tambah') echo " Produk baru berhasil ditambahkan!";
                    if($_GET['status'] == 'sukses_edit') echo " Data produk berhasil diperbarui!";
                    if($_GET['status'] == 'sukses_hapus') echo " Produk berhasil dihapus dari sistem!";
                ?>
            </div>
        <?php endif; ?>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #e9ecef;">
            <h4 style="margin-top: 0; margin-bottom: 15px; color: #333; font-size: 16px; font-weight: bold;">➕ Tambah Produk Baru</h4>
            <form action="kelola_produk.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div style="flex: 2; min-width: 200px;">
                    <label style="display:block; font-size:12px; font-weight:bold; margin-bottom:5px;">Nama Barang:</label>
                    <input type="text" name="nama_barang"required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                
                <div style="flex: 1.5; min-width: 150px;">
                    <label style="display:block; font-size:12px; font-weight:bold; margin-bottom:5px;">Kategori:</label>
                    <select name="kategori" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background: white;">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Kebutuhan Pokok">Kebutuhan Pokok</option>
                        <option value="Kosmetik">Kosmetik</option>
                    </select>
                </div>

                <div style="flex: 1; min-width: 120px;">
                    <label style="display:block; font-size:12px; font-weight:bold; margin-bottom:5px;">Harga (Rp):</label>
                    <input type="number" name="harga" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="flex: 1; min-width: 90px;">
                    <label style="display:block; font-size:12px; font-weight:bold; margin-bottom:5px;">Stok:</label>
                    <input type="number" name="stok" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="flex: 1; min-width: 130px;">
                    <button type="submit" name="tambah_produk" style="width:100%; padding: 9px; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>

        <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 25px;">

        <div style="margin-bottom: 25px; display: flex; gap: 10px; flex-wrap: wrap;">
            <?php $kategori_aktif = $_GET['filter'] ?? 'Semua'; ?>
            
            <a href="kelola_produk.php" style="text-decoration: none; padding: 10px 18px; border-radius: 25px; font-weight: bold; font-size: 13px; transition: 0.2s;
                <?= $kategori_aktif == 'Semua' ? 'background: #1E104E; color: white;' : 'background: #e9ecef; color: #333;' ?>">
                 Semua (<?= mysqli_num_rows(mysqli_query($conn, "SELECT id FROM produk")); ?>)
            </a>
            
            <a href="kelola_produk.php?filter=Makanan" style="text-decoration: none; padding: 10px 18px; border-radius: 25px; font-weight: bold; font-size: 13px; transition: 0.2s;
                <?= $kategori_aktif == 'Makanan' ? 'background: #28a745; color: white;' : 'background: #e9ecef; color: #333;' ?>">
                 Makanan
            </a>
            
            <a href="kelola_produk.php?filter=Minuman" style="text-decoration: none; padding: 10px 18px; border-radius: 25px; font-weight: bold; font-size: 13px; transition: 0.2s;
                <?= $kategori_aktif == 'Minuman' ? 'background: #007bff; color: white;' : 'background: #e9ecef; color: #333;' ?>">
                 Minuman
            </a>
            
            <a href="kelola_produk.php?filter=Kebutuhan Pokok" style="text-decoration: none; padding: 10px 18px; border-radius: 25px; font-weight: bold; font-size: 13px; transition: 0.2s;
                <?= $kategori_aktif == 'Kebutuhan Pokok' ? 'background: #ffc107; color: #212529;' : 'background: #e9ecef; color: #333;' ?>">
                 Kebutuhan Pokok
            </a>
            
            <a href="kelola_produk.php?filter=Kosmetik" style="text-decoration: none; padding: 10px 18px; border-radius: 25px; font-weight: bold; font-size: 13px; transition: 0.2s;
                <?= $kategori_aktif == 'Kosmetik' ? 'background: #e83e8c; color: white;' : 'background: #e9ecef; color: #333;' ?>">
                 Kosmetik
            </a>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; text-align: left;">
                        <th style="padding: 12px; width: 80px;">Gambar</th>
                        <th style="padding: 12px;">Nama Barang</th>
                        <th style="padding: 12px; width: 180px;">Kategori</th>
                        <th style="padding: 12px; width: 150px;">Harga (Rp)</th>
                        <th style="padding: 12px; width: 120px;">Stok</th>
                        <th style="padding: 12px; width: 160px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($kategori_aktif != 'Semua') {
                        $filter_clean = mysqli_real_escape_string($conn, $kategori_aktif);
                        $query = mysqli_query($conn, "SELECT * FROM produk WHERE kategori = '$filter_clean' ORDER BY id DESC");
                    } else {
                        $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY kategori ASC, id DESC");
                    }

                    if(mysqli_num_rows($query) == 0) {
                        echo "<tr><td colspan='6' style='padding: 20px; text-align: center; color: #888; font-style: italic;'>Belum ada produk di kategori ini.</td></tr>";
                    }

                    while($row = mysqli_fetch_assoc($query)) {
                        $file_gambar = "img/" . $row['nama_barang'] . ".JPEG";
                        if (!file_exists($file_gambar)) { $file_gambar = "img/" . $row['nama_barang'] . ".jpeg"; }
                        if (!file_exists($file_gambar)) { $file_gambar = "img/" . $row['nama_barang'] . ".jpg"; }
                        if (!file_exists($file_gambar)) { $file_gambar = "img/" . $row['nama_barang'] . ".png"; }
                        if (!file_exists($file_gambar)) { $file_gambar = "img/no-image.png"; }
                    ?>
                    <tr style="border-bottom: 1px solid #e9ecef; background: white;">
                        <form action="kelola_produk.php" method="POST">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            
                            <td style="padding: 8px;">
                                <img src="<?= $file_gambar; ?>" style="width: 45px; height: 45px; object-fit: contain; border-radius: 4px; border: 1px solid #eee;">

                            </td>
                            
                            <td style="padding: 8px;">
                                <input type="text" name="nama_barang" value="<?= $row['nama_barang']; ?>" required style="padding: 6px; border: 1px solid #ccc; border-radius: 4px; width: 95%; font-weight: 500;">
                            </td>
                            
                            <td style="padding: 8px;">
                                <select name="kategori" required style="padding: 6px; border: 1px solid #ccc; border-radius: 4px; width: 100%; background: #fff;">
                                    <option value="Makanan" <?= ($row['kategori'] ?? '') == 'Makanan' ? 'selected' : '' ?>>Makanan</option>
                                    <option value="Minuman" <?= ($row['kategori'] ?? '') == 'Minuman' ? 'selected' : '' ?>>Minuman</option>
                                    <option value="Kebutuhan Pokok" <?= ($row['kategori'] ?? '') == 'Kebutuhan Pokok' ? 'selected' : '' ?>>Kebutuhan Pokok</option>
                                    <option value="Kosmetik" <?= ($row['kategori'] ?? '') == 'Kosmetik' ? 'selected' : '' ?>>Kosmetik</option>
                                </select>
                            </td>
                            
                            <td style="padding: 8px;">
                                <input type="number" name="harga" value="<?= $row['harga']; ?>" required style="padding: 6px; border: 1px solid #ccc; border-radius: 4px; width: 90%;">
                            </td>
                            
                            <td style="padding: 8px;">
                                <input type="number" name="stok" value="<?= $row['stok']; ?>" required style="padding: 6px; border: 1px solid #ccc; border-radius: 4px; width: 65px;"> Pcs
                            </td>
                            
                            <td style="padding: 8px; text-align: center;">
                                <button type="submit" name="edit_produk" class="btn btn-warning" style="padding: 6px 12px; font-size: 13px;">
                                    Update
                                </button>
                                <a href="kelola_produk.php?hapus=<?= $row['id']; ?>" class="btn btn-danger" style="padding: 6px 12px; font-size: 13px;" onclick="return confirm('Yakin ingin menghapus produk <?= $row['nama_barang']; ?>?')">
                                    Hapus
                                </a>
                            </td>
                        </form>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php 
include 'layouts/footer.php'; 
ob_end_flush();
?>