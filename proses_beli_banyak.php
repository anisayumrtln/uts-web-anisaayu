<?php
include 'koneksi.php';

if (isset($_POST['id_barang'])) {
    $ids = $_POST['id_barang'];
    $jumlah_beli = $_POST['jumlah']; 
    
    $data_pembelian = [];

    foreach ($ids as $id) {
    $qty = $jumlah_beli[$id];
    
    // Ambil data stok saat ini dulu
    $cek_stok = mysqli_query($conn, "SELECT stok FROM produk WHERE id = '$id'");
    $data = mysqli_fetch_assoc($cek_stok);
    
    // Hanya kurangi jika stok mencukupi
    if ($data['stok'] >= $qty) {
        mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id = '$id'");
        $data_pembelian[] = $id . ":" . $qty;
    }
}

    $kirim_data = implode(',', $data_pembelian);

    header("location:struk_banyak.php?data=$kirim_data");
}
?>