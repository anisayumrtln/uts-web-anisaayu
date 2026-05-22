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
    /* CSS Modal agar tidak merusak tampilan */
    .modal-kasir { 
        display: none; 
        position: fixed; 
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
    /* Pastikan input tidak merusak modal */
    .modal-content input { width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box; }
    .btn-block { width: 100%; padding: 10px; border: none; background: #28a745; color: white; cursor: pointer; }
</style>

<div class="main-content-area" style="padding-top: 100px;">
    <div class="custom-container" style="width: 90%; margin: auto;">
        <h3>Kelola Transaksi Penjualan Produk</h3>
        <table style="width: 100%; border-collapse: collapse; background: white;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 12px; border: 1px solid #ddd;">Pilih</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Gambar</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Nama Barang</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Harga</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Jumlah Beli</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $produk_query = mysqli_query($conn, "SELECT * FROM produk");
            while($row = mysqli_fetch_assoc($produk_query)) {
                // Pastikan nama file sesuai dengan database Anda
                $path_gambar = "img/" . $row['nama_barang'] . ".jpeg";
            ?>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;"><input type="checkbox" name="pilih[]" value="<?= $row['id']; ?>"></td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <img src="<?= $path_gambar; ?>" style="width: 50px; height: 50px; object-fit: cover;">
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?= $row['nama_barang']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;">Rp <?= number_format($row['harga']); ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <input type="number" id="qty_<?= $row['id']; ?>" value="1" style="width: 60px;">
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <button type="button" onclick="bukaModal('<?= $row['id']; ?>', '<?= addslashes($row['nama_barang']); ?>', <?= $row['harga']; ?>)" 
                            style="padding: 8px 15px; background: #28a745; color: white; border: none; cursor: pointer;">
                        Input Transaksi
                    </button>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalBayar" class="modal-kasir">
    <div class="modal-content">
        <h3>Formulir Pembayaran</h3>
        <form action="struk.php" method="GET">
            <input type="hidden" id="modal_id" name="id">
            <input type="hidden" id="modal_harga">
            <label>Nama Produk:</label><input type="text" id="modal_nama" readonly>
            <label>Jumlah Beli:</label><input type="number" id="modal_qty" name="qty" oninput="hitungTotal()">
            <label>Total Belanja:</label><input type="text" id="modal_total" readonly>
            <button type="submit" class="btn-block">Selesai & Cetak</button>
            <button type="button" class="btn-block" style="background:#dc3545; margin-top:10px;" onclick="tutupModal()">Tutup</button>
        </form>
    </div>
</div>

<script>
function bukaModal(id, nama, harga) {
    let qty = document.getElementById('qty_' + id).value;
    document.getElementById('modal_id').value = id;
    document.getElementById('modal_nama').value = nama;
    document.getElementById('modal_harga').value = harga;
    document.getElementById('modal_qty').value = qty;
    document.getElementById('modalBayar').style.display = 'block';
    hitungTotal();
}
function hitungTotal() {
    let q = document.getElementById('modal_qty').value;
    let h = document.getElementById('modal_harga').value;
    document.getElementById('modal_total').value = 'Rp ' + (q * h).toLocaleString('id-ID');
}
function tutupModal() { document.getElementById('modalBayar').style.display = 'none'; }
</script>

<?php include 'layouts/footer.php'; ob_end_flush(); ?>