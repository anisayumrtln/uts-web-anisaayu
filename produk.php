<?php 
ob_start();
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Jakarta');
include 'koneksi.php';
include 'layouts/header.php'; 
include 'layouts/navbar.php'; 
?>

<style>
    .modal-kasir { 
        display: none; \r\n        position: fixed; 
        z-index: 9999; 
        left: 0; top: 0; 
        width: 100%; height: 100%; 
        background-color: rgba(0,0,0,0.5); 
    }
    .modal-content { 
        background-color: white; 
        margin: 10% auto; 
        padding: 20px; 
        width: 400px; 
        border-radius: 10px; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .modal-content input { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .btn-block { width: 100%; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; }
</style>

<div style="padding-top: 20px; padding-bottom: 40px; background-color: #f4f7f6; min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-top: 0px;">
    <div style="width: 95%; max-width: 1100px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        <h2 style="font-weight: bold; color: #1E104E; margin-bottom: 5px; text-align: center;">Daftar Menu Produk Toko</h2>
        <p style="color: #666; margin: 0 0 25px 0; text-align: center; font-size: 14px;">Pilih kategori menu atau gunakan fitur pencarian untuk menemukan produk dengan cepat.</p>

        <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; justify-content: flex-start;">
            <button type="button" onclick="filterKategoriMenu('Semua')" class="btn-filter-menu" id="menu-Semua" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #1E104E; color: white;">
                 Semua
            </button>
            <button type="button" onclick="filterKategoriMenu('Makanan')" class="btn-filter-menu" id="menu-Makanan" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">
                 Makanan
            </button>
            <button type="button" onclick="filterKategoriMenu('Minuman')" class="btn-filter-menu" id="menu-Minuman" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">
                 Minuman
            </button>
            <button type="button" onclick="filterKategoriMenu('Kebutuhan Pokok')" class="btn-filter-menu" id="menu-Kebutuhan Pokok" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">
                 Kebutuhan Pokok
            </button>
            <button type="button" onclick="filterKategoriMenu('Kosmetik')" class="btn-filter-menu" id="menu-Kosmetik" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">
                 Kosmetik
            </button>
        </div>

        <div style="margin-bottom: 25px; display: flex; justify-content: flex-start;">
            <input type="text" id="cariProduk" onkeyup="fungsiCariMenu()" placeholder="🔍 Cari nama produk di katalog..." style="width: 100%; max-width: 400px; padding: 11px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);">
        </div>

        <div id="kontainerProduk" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            <?php 
            $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id ASC");
            while($row = mysqli_fetch_assoc($query)) { 
                $file_gambar = "img/" . $row['nama_barang'] . ".JPEG";
                if (!file_exists($file_gambar)) { $file_gambar = "img/" . $row['nama_barang'] . ".jpeg"; }
                if (!file_exists($file_gambar)) { $file_gambar = "img/" . $row['nama_barang'] . ".jpg"; }
                if (!file_exists($file_gambar)) { $file_gambar = "img/" . $row['nama_barang'] . ".png"; }
                if (!file_exists($file_gambar)) { $file_gambar = "img/no-image.png"; }
            ?>
            <div class="card-produk-item" data-kategori="<?= $row['kategori']; ?>" data-nama="<?= strtolower($row['nama_barang']); ?>" style="background: white; border: 1px solid #e9ecef; border-radius: 10px; padding: 15px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <img src="<?= $file_gambar; ?>" style="width: 100%; height: 130px; object-fit: contain; margin-bottom: 10px; border-radius: 6px;">
                    <h5 style="margin: 5px 0; color: #333; font-weight: bold; font-size: 15px;"><?= $row['nama_barang']; ?></h5>
                    <p style="margin: 0 0 10px 0; color: #28a745; font-weight: bold; font-size: 14px;">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                    <p style="margin: 0 0 15px 0; color: #777; font-size: 12px;">Stok: <?= $row['stok']; ?></p>
                </div>
                
                <div style="display: flex; gap: 5px; align-items: center;">
                    <input type="number" id="qty_<?= $row['id']; ?>" value="1" min="1" max="<?= $row['stok']; ?>" style="width: 50px; padding: 6px; border: 1px solid #ccc; border-radius: 4px; text-align: center; font-size: 13px;">
                    <button type="button" onclick="bukaModal('<?= $row['id']; ?>', '<?= $row['nama_barang']; ?>', '<?= $row['harga']; ?>')" style="flex: 1; padding: 7px; background: #1E104E; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 12px;">
                        Beli Langsung
                    </button>
                </div>
            </div>
            <?php } ?>
        </div>

    </div>
</div>

<div id="modalBayar" class="modal-kasir">
    <div class="modal-content">
        <h3 style="margin-top: 0; color: #1E104E; font-weight: bold;">🛒 Konfirmasi Pembelian</h3>
        <form action="proses_beli.php" method="POST">
            <input type="hidden" id="modal_id" name="id_produk">
            
            <label style="font-size: 12px; font-weight: bold;">Nama Barang:</label>
            <input type="text" id="modal_nama" readonly style="background: #e9ecef;">
            
            <label style="font-size: 12px; font-weight: bold;">Harga Satuan:</label>
            <input type="number" id="modal_harga" readonly style="background: #e9ecef;">
            
            <label style="font-size: 12px; font-weight: bold;">Jumlah Beli (Qty):</label>
            <input type="number" id="modal_qty" name="qty" min="1" oninput=\"hitungTotal()\">
            
            <label style="font-size: 12px; font-weight: bold;">Total Belanja:</label>
            <input type="text" id="modal_total" readonly style="background: #e9ecef; font-weight: bold; color: #007bff;">
            
            <button type="submit" class="btn-block" style="border-radius: 4px; font-weight: bold; margin-top: 10px;">Selesai & Cetak</button>
            <button type="button" class="btn-block" style="background:#dc3545; border-radius: 4px; font-weight: bold; margin-top:10px;" onclick="tutupModal()">Tutup</button>
        </form>
    </div>
</div>

<script>
let katMenuTerpilih = "Semua";

// FILTER BULAT KATEGORI LIVE TANPA RELOAD
function filterKategoriMenu(kategori) {
    katMenuTerpilih = kategori;
    
    const semuaTombol = document.querySelectorAll('.btn-filter-menu');
    semuaTombol.forEach(tombol => {
        tombol.style.background = "#e9ecef";
        tombol.style.color = "#333";
    });
    
    const tombolAktif = document.getElementById('menu-' + kategori);
    if(kategori === 'Semua') { tombolAktif.style.background = "#1E104E"; tombolAktif.style.color = "white"; }
    else if(kategori === 'Makanan') { tombolAktif.style.background = "#28a745"; tombolAktif.style.color = "white"; }
    else if(kategori === 'Minuman') { tombolAktif.style.background = "#007bff"; tombolAktif.style.color = "white"; }
    else if(kategori === 'Kebutuhan Pokok') { tombolAktif.style.background = "#ffc107"; tombolAktif.style.color = "#212529"; }
    else if(kategori === 'Kosmetik') { tombolAktif.style.background = "#e83e8c"; tombolAktif.style.color = "white"; }

    jalankanFilterMenuGabungan();
}

// LIVE SEARCH INPUT
function fungsiCariMenu() {
    jalankanFilterMenuGabungan();
}

function jalankanFilterMenuGabungan() {
    let kataKunci = document.getElementById("cariProduk").value.toLowerCase();
    let cards = document.querySelectorAll(".card-produk-item");

    cards.forEach(card => {
        let kategoriCard = card.getAttribute("data-kategori");
        let namaCard = card.getAttribute("data-nama");

        let cocokKategori = (katMenuTerpilih === "Semua" || kategoriCard === katMenuTerpilih);
        let cocokKataKunci = (namaCard.indexOf(kataKunci) > -1);

        if (cocokKategori && cocokKataKunci) {
            card.style.display = "flex";
        } else {
            card.style.display = "none";
        }
    });
}

function bukaModal(id, nama, harga) {
    let qty = document.getElementById('qty_' + id).value;
    document.getElementById('modal_id').value = id;
    document.getElementById('modal_nama').value = nama;
    document.getElementById('modal_harga').value = harga;
    document.getElementById('modal_qty').value = qty;
    document.getElementById('modalBayar').style.display = 'block';
    hitungTotal();
}

function tutupModal() {
    document.getElementById('modalBayar').style.display = 'none';
}

function hitungTotal() {
    let q = document.getElementById('modal_qty').value;
    let h = document.getElementById('modal_harga').value;
    let total = q * h;
    document.getElementById('modal_total').value = "Rp " + total.toLocaleString('id-ID');
}
</script>