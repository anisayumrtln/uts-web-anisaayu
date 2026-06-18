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

$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Custom styling agar Select2 serasi dengan desain form Anda */
        .select2-container--default .select2-selection--single {
            height: 36px !important;
            border: 1px solid #ccc !important;
            border-radius: 4px !important;
            display: flex;
            align-items: center;
        }
        .select2-container {
            width: 100% !important;
        }
    </style>
</head>
<body>

<?php if (isset($_GET['status'])): ?>
    <?php 
        $pesan = "";
        if ($_GET['status'] == 'sukses_tambah') {
            $pesan = "Berhasil! Produk baru telah ditambahkan ke toko.";
        } elseif ($_GET['status'] == 'sukses_edit') {
            $pesan = "Berhasil! Data produk telah diperbarui.";
        } elseif ($_GET['status'] == 'sukses_hapus') {
            $pesan = "Berhasil! Produk telah dihapus dari sistem.";
        }
    ?>
    <?php if ($pesan != ""): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "<?= $pesan; ?>",
                showConfirmButton: false,
                timer: 3000
            });
            window.location.href = "kelola_produk.php";
        </script>
    <?php endif; ?>
<?php endif; ?>

<div style="padding-top: 20px; padding-bottom: 40px; background-color: #f4f7f6; min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-top: 0px;">
    <div style="width: 95%; max-width: 1100px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        <h2 style="font-weight: bold; color: #1E104E; margin-bottom: 5px; text-align: center;">Kelola Stok & Data Produk</h2>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 30px;">
            <h4 style="margin-top: 0; margin-bottom: 15px; color: #1E104E;">➕ Tambah Produk Baru</h4>
            <form id="formTambahProduk" action="kelola_produk.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div style="flex: 2; min-width: 200px;">
                    <label style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px;">Nama Barang:</label>
                    <input type="text" name="nama_barang" id="tambah_nama" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; height: 36px;">
                </div>
                <div style="flex: 1.5; min-width: 150px;">
                    <label style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px;">Kategori:</label>
                    <select name="kategori" id="tambah_kategori" class="select2-aktif" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Kebutuhan Pokok">Kebutuhan Pokok</option>
                        <option value="Kosmetik">Kosmetik</option>
                    </select>
                </div>
                <div style="flex: 1.2; min-width: 120px;">
                    <label style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px;">Harga (Rp):</label>
                    <input type="number" name="harga" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; height: 36px;">
                </div>
                <div style="flex: 1; min-width: 80px;">
                    <label style="display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px;">Stok:</label>
                    <input type="number" name="stok" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; height: 36px;">
                </div>
                <button type="submit" name="tambah_produk" onclick="konfirmasiTambah(event)" style="padding: 9px 20px; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; height: 36px;">
                    Simpan Produk
                </button>
            </form>
        </div>

        <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; justify-content: flex-start;">
            <button type="button" onclick="filterKategoriStok('Semua')" class="btn-filter-stok" id="stok-Semua" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #1E104E; color: white;">Semua</button>
            <button type="button" onclick="filterKategoriStok('Makanan')" class="btn-filter-stok" id="stok-Makanan" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">Makanan</button>
            <button type="button" onclick="filterKategoriStok('Minuman')" class="btn-filter-stok" id="stok-Minuman" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">Minuman</button>
            <button type="button" onclick="filterKategoriStok('Kebutuhan Pokok')" class="btn-filter-stok" id="stok-Kebutuhan Pokok" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">Kebutuhan Pokok</button>
            <button type="button" onclick="filterKategoriStok('Kosmetik')" class="btn-filter-stok" id="stok-Kosmetik" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">Kosmetik</button>
        </div>

        <div style="margin-bottom: 25px; display: flex; justify-content: flex-start;">
            <input type="text" id="inputCari" onkeyup="fungsiCari()" placeholder="🔍 Cari nama produk di tabel..." style="width: 100%; max-width: 400px; padding: 11px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);">
        </div>

        <div style="overflow-x: auto;">
            <table id="tabelProduk" style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; text-align: left;">
                        <th style="padding: 12px; width: 50px; text-align: center;">ID</th>
                        <th style="padding: 12px; width: 70px; text-align: center;">Gambar</th>
                        <th style="padding: 12px;">Nama Barang</th>
                        <th style="padding: 12px; width: 220px;">Kategori</th>
                        <th style="padding: 12px; width: 140px;">Harga (Rp)</th>
                        <th style="padding: 12px; width: 100px;">Stok</th>
                        <th style="padding: 12px; width: 160px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while($row = mysqli_fetch_assoc($query)) { 
                        $file_gambar = "img/" . $row['nama_barang'] . ".JPEG";
                        if (!file_exists($file_gambar)) { $file_gambar = "img/" . $row['nama_barang'] . ".jpeg"; }
                        if (!file_exists($file_gambar)) { $file_gambar = "img/" . $row['nama_barang'] . ".jpg"; }
                        if (!file_exists($file_gambar)) { $file_gambar = "img/" . $row['nama_barang'] . ".png"; }
                        if (!file_exists($file_gambar)) { $file_gambar = "img/no-image.png"; }
                    ?>
                    <tr class="baris-stok" data-kategori="<?= $row['kategori']; ?>" style="border-bottom: 1px solid #e9ecef; background: white;">
                        <form action="kelola_produk.php" method="POST">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            
                            <td style="padding: 12px; text-align: center; color: #666;">
                                <?= $row['id']; ?>
                            </td>

                            <td style="padding: 6px; text-align: center;">
                                <img src="<?= $file_gambar; ?>" style="width: 45px; height: 45px; object-fit: contain; border-radius: 4px; border: 1px solid #eee;">
                            </td>
                            
                            <td style="padding: 8px 12px;">
                                <input type="text" name="nama_barang" value="<?= $row['nama_barang']; ?>" required style="width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                            </td>
                            
                            <td style="padding: 8px 12px;">
                                <select name="kategori" class="select2-aktif" required>
                                    <option value="Makanan" <?= ($row['kategori'] == 'Makanan') ? 'selected' : ''; ?>>Makanan</option>
                                    <option value="Minuman" <?= ($row['kategori'] == 'Minuman') ? 'selected' : ''; ?>>Minuman</option>
                                    <option value="Kebutuhan Pokok" <?= ($row['kategori'] == 'Kebutuhan Pokok') ? 'selected' : ''; ?>>Kebutuhan Pokok</option>
                                    <option value="Kosmetik" <?= ($row['kategori'] == 'Kosmetik') ? 'selected' : ''; ?>>Kosmetik</option>
                                </select>
                            </td>
                            
                            <td style="padding: 8px 12px;">
                                <input type="number" name="harga" value="<?= $row['harga']; ?>" required style="width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                            </td>
                            
                            <td style="padding: 8px 12px;">
                                <input type="number" name="stok" value="<?= $row['stok']; ?>" required style="width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; text-align: center;">
                            </td>
                            
                            <td style="padding: 12px; text-align: center;">
                                <button type="submit" name="edit_produk" class="btn btn-warning" style="padding: 6px 12px; font-size: 13px; background: #ffc107; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; color: #212529;" 
                                        onclick="konfirmasiUpdate(event, this)">
                                    Update
                                </button>
                                <a href="kelola_produk.php?hapus=<?= $row['id']; ?>" class="btn btn-danger" style="padding: 6px 12px; font-size: 13px; background: #dc3545; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; margin-left: 5px;" 
                                   onclick="konfirmasiHapus(event, this)">
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

<script>
// INISIALISASI SELECT2 KETIKA HALAMAN SELESAI DIMUAT
$(document).ready(function() {
    $('.select2-aktif').select2();
});

let katStokTerpilih = "Semua";

function filterKategoriStok(kategori) {
    katStokTerpilih = kategori;
    
    const semuaTombol = document.querySelectorAll('.btn-filter-stok');
    semuaTombol.forEach(tombol => {
        tombol.style.background = "#e9ecef";
        tombol.style.color = "#333";
    });
    
    const tombolAktif = document.getElementById('stok-' + kategori);
    if(kategori === 'Semua') { tombolAktif.style.background = "#1E104E"; tombolAktif.style.color = "white"; }
    else if(kategori === 'Makanan') { tombolAktif.style.background = "#28a745"; tombolAktif.style.color = "white"; }
    else if(kategori === 'Minuman') { tombolAktif.style.background = "#007bff"; tombolAktif.style.color = "white"; }
    else if(kategori === 'Kebutuhan Pokok') { tombolAktif.style.background = "#ffc107"; tombolAktif.style.color = "#212529"; }
    else if(kategori === 'Kosmetik') { tombolAktif.style.background = "#e83e8c"; tombolAktif.style.color = "white"; }

    jalankanFilterStokGabungan();
}

function fungsiCari() {
    jalankanFilterStokGabungan();
}

function jalankanFilterStokGabungan() {
    let kataKunci = document.getElementById("inputCari").value.toUpperCase();
    let table = document.getElementById("tabelProduk");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let kategoriBaris = tr[i].getAttribute("data-kategori");
        let tdInput = tr[i].getElementsByTagName("input")[1]; 
        
        if (tdInput) {
            let namaProduk = tdInput.value.toUpperCase();
            
            let cocokKategori = (katStokTerpilih === "Semua" || kategoriBaris === katStokTerpilih);
            let cocokKataKunci = (namaProduk.indexOf(kataKunci) > -1);

            if (cocokKategori && cocokKataKunci) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

// ==========================================
// FUNGSI KONFIRMASI SWEETALERT JAVASCRIPT
// ==========================================

function konfirmasiTambah(e) {
    let form = document.getElementById('formTambahProduk');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    e.preventDefault();

    let namaProduk = document.getElementById('tambah_nama').value;

    Swal.fire({
        title: 'Tambah Produk Baru?',
        text: "Apakah Anda yakin ingin menambahkan '" + namaProduk + "' ke dalam daftar data stok?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        color: '#212529'
    }).then((result) => {
        if (result.isConfirmed) {
            let hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'tambah_produk';
            hiddenInput.value = 'true';
            form.appendChild(hiddenInput);
            
            form.submit();
        }
    });
}

function konfirmasiUpdate(e, tombol) {
    e.preventDefault();
    
    let baris = tombol.closest('tr');
    let inputNama = baris.querySelector('input[name="nama_barang"]');
    let namaProduk = inputNama ? inputNama.value : "produk ini";

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data produk '" + namaProduk + "' akan diperbarui!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal',
        color: '#212529'
    }).then((result) => {
        if (result.isConfirmed) {
            tombol.closest('form').submit();
        }
    });
}

function konfirmasiHapus(e, elemenA) {
    e.preventDefault();
    
    let baris = elemenA.closest('tr');
    let inputNama = baris.querySelector('input[name="nama_barang"]');
    let namaProduk = inputNama ? inputNama.value : "produk ini";
    let urlHapus = elemenA.getAttribute('href');

    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Produk '" + namaProduk + "' akan dihapus permanen!",
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = urlHapus;
        }
    });
}
</script>     
</body>
</html>