<?php
include 'koneksi.php';

if (isset($_POST['id_barang'])) {
    $ids = $_POST['id_barang'];
    $jumlah_beli = $_POST['jumlah']; 
    
    $data_pembelian = [];

    foreach ($ids as $id) {
        $qty = $jumlah_beli[$id]; 
        mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id = $id");
        
        $data_pembelian[] = $id . ":" . $qty;
    }

    $kirim_data = implode(',', $data_pembelian);

    header("location:struk_banyak.php?data=$kirim_data");
}
?>