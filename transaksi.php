<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Jakarta');
include 'koneksi.php';
include 'layouts/header.php'; 
include 'layouts/navbar.php'; 

$query = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0 ORDER BY nama_barang ASC");
?>

<div style="padding-top: 30px; padding-bottom: 40px; background-color: #f4f7f6; min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-top: 0px;">
    <div style="width: 95%; max-width: 1100px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        <h2 style="font-weight: bold; color: #1E104E; margin-top: 0px; margin-bottom: 5px; text-align: center;">Kelola Transaksi Penjualan Produk</h2>
        <p style="color: #666; margin: 0 0 25px 0; text-align: center; font-size: 14px;">Pilih daftar produk di bawah ini untuk memulai transaksi baru dan mencetak struk belanja pelanggan.</p>

        <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; justify-content: flex-start;">
            <button type="button" onclick="filterKategoriTransaksi('Semua')" class="btn-filter-kat" id="kat-Semua" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #1E104E; color: white;">
                 Semua
            </button>
            <button type="button" onclick="filterKategoriTransaksi('Makanan')" class="btn-filter-kat" id="kat-Makanan" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">
                 Makanan
            </button>
            <button type="button" onclick="filterKategoriTransaksi('Minuman')" class="btn-filter-kat" id="kat-Minuman" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">
                 Minuman
            </button>
            <button type="button" onclick="filterKategoriTransaksi('Kebutuhan Pokok')" class="btn-filter-kat" id="kat-Kebutuhan Pokok" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">
                 Kebutuhan Pokok
            </button>
            <button type="button" onclick="filterKategoriTransaksi('Kosmetik')" class="btn-filter-kat" id="kat-Kosmetik" style="padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 13px; border: none; cursor: pointer; transition: 0.2s; background: #e9ecef; color: #333;">
                 Kosmetik
            </button>
        </div>

        <div style="margin-bottom: 25px; display: flex; justify-content: flex-start;">
            <input type="text" id="inputCariKasir" onkeyup="filterCariKasir()" placeholder="🔍 Masukkan nama barang yang dicari..." style="width: 100%; max-width: 400px; padding: 11px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);">
        </div>

        <form action="proses_pembayaran.php" method="POST" id="formPenjualan">
            
            <div style="overflow-x: auto; margin-bottom: 30px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; text-align: left;">
                            <th style="padding: 12px; width: 60px; text-align: center;">Pilih</th>
                            <th style="padding: 12px; width: 80px; text-align: center;">Gambar</th>
                            <th style="padding: 12px;">Nama Produk</th>
                            <th style="padding: 12px; width: 150px;">Harga Satuan</th>
                            <th style="padding: 12px; width: 120px;">Jumlah Beli</th>
                            <th style="padding: 12px; width: 150px;">Subtotal</th>
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
                        <tr class="baris-produk" data-kategori="<?= $row['kategori']; ?>" style="border-bottom: 1px solid #e9ecef; background: white;">
                            
                            <td style="padding: 12px; text-align: center;">
                                <input type="checkbox" name="id_barang[]" value="<?= $row['id']; ?>" class="cb-produk" onchange="aktifkanBaris(this, '<?= $row['id']; ?>')" style="width: 18px; height: 18px; cursor: pointer;">
                            </td>
                            
                            <td style="padding: 8px; text-align: center;">
                                <img src="<?= $file_gambar; ?>" style="width: 45px; height: 45px; object-fit: contain; border-radius: 4px; border: 1px solid #eee;">
                            </td>
                            
                            <td class="nama-produk-td" style="padding: 12px; font-weight: 500; color: #333;">
                                <?= $row['nama_barang']; ?>
                            </td>
                            
                            <td style="padding: 12px; color: #555;">
                                Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                                <input type="hidden" id="harga_<?= $row['id']; ?>" value="<?= $row['harga']; ?>">
                            </td>
                            
                            <td style="padding: 12px;">
                                <input type="number" id="qty_<?= $row['id']; ?>" name="jumlah[<?= $row['id']; ?>]" value="1" min="1" max="<?= $row['stok']; ?>" disabled oninput="hitungSubtotalBaris('<?= $row['id']; ?>')" style="width: 80px; padding: 6px; border: 1px solid #ccc; border-radius: 4px; text-align: center;">
                                <div style="font-size: 11px; color: #888; margin-top: 3px;">Stok: <?= $row['stok']; ?></div>
                            </td>
                            
                            <td style="padding: 12px; font-weight: bold; color: #333;">
                                <span id="teks_subtotal_<?= $row['id']; ?>">Rp 0</span>
                                <input type="hidden" class="subtotal-item-angka" id="subtotal_angka_<?= $row['id']; ?>" value="0">
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 20px; background: #f8f9fa; padding: 25px; border-radius: 8px; border: 1px solid #e9ecef;">
                
                <div style="flex: 1; min-width: 280px;">
                    <div style="margin-bottom: 15px;">
                        <label style="display:block; font-weight:bold; font-size:14px; margin-bottom: 6px; color: #333;">Total Belanja Keseluruhan:</label>
                        <input type="text" id="grand_total_teks" value="Rp 0" readonly style="width: 100%; padding: 12px; background: #e9ecef; border: 1px solid #ccc; border-radius: 6px; font-weight: bold; font-size: 22px; color: #1E104E; box-sizing: border-box;">
                        <input type="hidden" id="grand_total_angka" value="0">
                    </div>

                    <div>
                        <label style="display:block; font-weight:bold; font-size:14px; margin-bottom: 6px; color: #333;">Metode Pembayaran:</label>
                        <select name="metode_bayar" id="trans_metode_bayar" onchange="pilihMetodePembayaran()" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; background: white; font-size: 14px; box-sizing: border-box; font-weight: 500;">
                            <option value="Tunai">Tunai (Cash)</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Transfer">Transfer Bank</option>
                        </select>
                    </div>
                </div>

                <div style="flex: 1; min-width: 280px; border-left: 2px dashed #ddd; padding-left: 20px;" id="section_kalkulator_tunai">
                    <div style="margin-bottom: 15px;">
                        <label style="display:block; font-weight:bold; font-size:14px; margin-bottom: 6px; color: #333;">Uang yang Diterima Kasir (Rp):</label>
                        <input type="text" id="trans_uang_bayar" name="uang_dibayar" placeholder="Masukkan nominal uang cash..." style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-size: 16px; font-weight: bold; color: #333;">
                    </div>
                    
                    <div>
                        <label style="display:block; font-weight:bold; font-size:14px; color: #333;">Uang Kembalian:</label>
                        <h2 id="trans_teks_kembalian" style="margin: 8px 0 0 0; color: #28a745; font-weight: bold; font-size: 26px;">Rp 0</h2>
                    </div>
                </div>

            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" style="flex: 2; padding: 14px; background: #28a745; color: white; border: none; border-radius: 6px; font-weight: bold; font-size: 16px; cursor: pointer;">
                    💾 Selesai & Cetak Struk Sekarang
                </button>
                <a href="dashboard.php" style="flex: 1; display: block; text-align: center; text-decoration: none; padding: 14px; background: #6c757d; color: white; border-radius: 6px; font-weight: bold; font-size: 16px; box-sizing: border-box;">
                    Kembali ke Dashboard
                </a>
            </div>

        </form>
    </div>
</div>

<script>
const inputUangBayar = document.getElementById('trans_uang_bayar');
let kategoriTerpilih = "Semua";

function filterKategoriTransaksi(kategori) {
    kategoriTerpilih = kategori;
    
    const semuaTombol = document.querySelectorAll('.btn-filter-kat');
    semuaTombol.forEach(tombol => {
        tombol.style.background = "#e9ecef";
        tombol.style.color = "#333";
    });
    
    const tombolAktif = document.getElementById('kat-' + kategori);
    if(kategori === 'Semua') { tombolAktif.style.background = "#1E104E"; tombolAktif.style.color = "white"; }
    else if(kategori === 'Makanan') { tombolAktif.style.background = "#28a745"; tombolAktif.style.color = "white"; }
    else if(kategori === 'Minuman') { tombolAktif.style.background = "#007bff"; tombolAktif.style.color = "white"; }
    else if(kategori === 'Kebutuhan Pokok') { tombolAktif.style.background = "#ffc107"; tombolAktif.style.color = "#212529"; }
    else if(kategori === 'Kosmetik') { tombolAktif.style.background = "#e83e8c"; tombolAktif.style.color = "white"; }

    jalankanFilterGabungan();
}

function filterCariKasir() {
    jalankanFilterGabungan();
}

function jalankanFilterGabungan() {
    let kataKunci = document.getElementById("inputCariKasir").value.toUpperCase();
    let barisBarang = document.querySelectorAll(".baris-produk");

    barisBarang.forEach(baris => {
        let kategoriBaris = baris.getAttribute("data-kategori");
        let tdNama = baris.querySelector(".nama-produk-td");
        let namaProduk = tdNama ? (tdNama.textContent || tdNama.innerText).toUpperCase() : "";

        let cocokKategori = (kategoriTerpilih === "Semua" || kategoriBaris === kategoriTerpilih);
        let cocokKataKunci = (namaProduk.indexOf(kataKunci) > -1);

        if (cocokKategori && cocokKataKunci) {
            baris.style.display = "";
        } else {
            baris.style.display = "none";
        }
    });
}

function aktifkanBaris(checkbox, id) {
    const inputQty = document.getElementById('qty_' + id);
    if (checkbox.checked) {
        inputQty.disabled = false;
        hitungSubtotalBaris(id);
    } else {
        inputQty.disabled = true;
        inputQty.value = 1;
        document.getElementById('subtotal_angka_' + id).value = 0;
        document.getElementById('teks_subtotal_' + id).innerText = "Rp 0";
        hitungGrandTotalTransaksi();
    }
}

function hitungSubtotalBaris(id) {
    const harga = parseInt(document.getElementById('harga_' + id).value) || 0;
    const qty = parseInt(document.getElementById('qty_' + id).value) || 1;
    const subtotal = harga * qty;
    
    document.getElementById('subtotal_angka_' + id).value = subtotal;
    document.getElementById('teks_subtotal_' + id).innerText = "Rp " + subtotal.toLocaleString('id-ID');
    
    hitungGrandTotalTransaksi();
}

function hitungGrandTotalTransaksi() {
    const semuaSubtotalHidden = document.querySelectorAll('.subtotal-item-angka');
    let grandTotal = 0;
    
    semuaSubtotalHidden.forEach(input => {
        grandTotal += parseInt(input.value) || 0;
    });
    
    document.getElementById('grand_total_angka').value = grandTotal;
    document.getElementById('grand_total_teks').value = "Rp " + grandTotal.toLocaleString('id-ID');
    
    hitungKembalianSecaraLive();
}

if (inputUangBayar) {
    inputUangBayar.addEventListener('keyup', function() {
        let nomor = this.value.replace(/[^,\d]/g, '').toString();
        let split = nomor.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        this.value = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        hitungKembalianSecaraLive();
    });
}

function hitungKembalianSecaraLive() {
    const total = parseInt(document.getElementById('grand_total_angka').value) || 0;
    const bayarRaw = inputUangBayar.value.replace(/\./g, '');
    const bayar = parseInt(bayarRaw) || 0;

    const kembali = bayar - total;
    const teks = document.getElementById('trans_teks_kembalian');

    if (bayar === 0) {
        teks.innerText = "Rp 0";
        teks.style.color = "#333";
    } else if (kembali >= 0) {
        teks.innerText = "Rp " + kembali.toLocaleString('id-ID');
        teks.style.color = "#28a745"; 
    } else {
        teks.innerText = "Rp 0 (Uang Kurang!)";
        teks.style.color = "#dc3545"; 
    }
}

function pilihMetodePembayaran() {
    let metode = document.getElementById('trans_metode_bayar').value;
    let sectionTunai = document.getElementById('section_kalkulator_tunai');
    
    if (metode === 'Tunai') {
        sectionTunai.style.opacity = '1';
        inputUangBayar.disabled = false;
    } else {
        sectionTunai.style.opacity = '0.3';
        inputUangBayar.disabled = true;
        inputUangBayar.value = '';
        document.getElementById('trans_teks_kembalian').innerText = 'Rp 0';
    }
}

document.getElementById('formPenjualan').addEventListener('submit', function(e) {
    const checkboxes = document.querySelectorAll('.cb-produk:checked');
    if (checkboxes.length === 0) {
        e.preventDefault();
        alert('Pilih/Centang minimal satu barang terlebih dahulu sebelum memproses struk!');
    }
});
</script>
