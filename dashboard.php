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
    .preloader { display: none !important; opacity: 0 !important; visibility: hidden !important; }
    body { background-color: #f4f7f6 !important; }
    .main-content-area { display: block !important; width: 100% !important; padding-top: 50px !important; padding-bottom: 20px !important; }
    .custom-container { width: 90% !important; max-width: 1200px !important; margin: 0 auto !important; }
    .welcome-box { background: #ffffff; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; margin-bottom: 30px; }
</style>

<div class="main-content-area">
    <div class="custom-container">
        
        <div class="welcome-box">
            <img src="img/logo_toko.jpeg" alt="Logo Toko" style="width: 120px; height: 120px; object-fit: contain; margin-bottom: 20px; border-radius: 50%; border: 3px solid #007bff; padding: 5px;">
            <h1 style="font-weight: 700; color: #222; margin-bottom: 10px;">Selamat Datang di Sistem Kasir</h1>
            <p style="font-size: 18px; color: #666; margin-bottom: 25px;">Halo <strong><?= $_SESSION['username'] ?? 'Anisa'; ?></strong>, Anda login sebagai Kasir hari ini.</p>
            <span id="jam_besar" style="font-size: 20px; font-weight: bold; background: #e8f4fd; color: #007bff; padding: 10px 25px; border-radius: 30px; display: inline-block;"></span>
        </div>

        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
            <div style="flex: 1; min-width: 220px; background: #007bff; color: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <span style="font-size: 15px; opacity: 0.9;">Total Stok Barang</span>
                <h3 style="margin: 5px 0 0 0; font-size: 32px; font-weight: bold;">
                    <?php 
                        $total_stok = mysqli_query($conn, "SELECT SUM(stok) as total FROM produk");
                        $data_stok = mysqli_fetch_assoc($total_stok);
                        echo number_format($data_stok['total'] ?? 0, 0, ',', '.');
                    ?>
                </h3>
            </div>
            <div style="flex: 1; min-width: 220px; background: #28a745; color: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <span style="font-size: 15px; opacity: 0.9;">Total Jenis Produk</span>
                <h3 style="margin: 5px 0 0 0; font-size: 32px; font-weight: bold;">
                    <?php 
                        $total_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
                        $data_prod = mysqli_fetch_assoc($total_produk);
                        echo $data_prod['total'] ?? 0;
                    ?>
                </h3>
            </div>
            <div style="flex: 1; min-width: 220px; background: #ffc107; color: #212529; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <span style="font-size: 15px; font-weight: 600; opacity: 0.9;">Status Printer Kasir</span>
                <h3 style="margin: 12px 0 0 0; font-size: 26px; font-weight: bold;">Ready ✅</h3>
            </div>
            <div style="flex: 1; min-width: 220px; background: #dc3545; color: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <span style="font-size: 15px; opacity: 0.9;">Shift Kerja Aktif</span>
                <?php 
                    $jam = (int)date('H');
                    if ($jam >= 6 && $jam < 14) { $shift = "Pagi <span style='font-size:12px;'>(06:00-14:00)</span>"; }
                    elseif ($jam >= 14 && $jam < 22) { $shift = "Siang <span style='font-size:12px;'>(14:00-22:00)</span>"; }
                    else { $shift = "Malam <span style='font-size:12px;'>(22:00-06:00)</span>"; }
                ?>
                <h3 style="margin: 8px 0 0 0; font-size: 22px; font-weight: bold;"><?= $shift; ?></h3>
            </div>
        </div>

    </div>
</div>

<script>
function updateJamBesar() {
    const now = new Date();
    document.getElementById('jam_besar').innerText = "Waktu Digital: " + now.toLocaleTimeString('id-ID') + " WIB";
}
setInterval(updateJamBesar, 1000);
updateJamBesar();
</script>

<?php 
include 'layouts/footer.php'; 
ob_end_flush();
?>