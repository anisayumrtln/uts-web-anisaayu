<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama  = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $query = mysqli_query($conn, "INSERT INTO produk VALUES('', '$nama', '$harga', '$stok')");
    
    if($query){
        header("location:dashboard.php");
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    </head>
<body>
    </body>
</html>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border: 2px solid black;
            box-shadow: 10px 10px 0px #1E104E; 
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #1E104E;
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #ccc;
            box-sizing: border-box;
        }
        button {
            background-color: #1E104E;
            color: white;
            width: 100%;
            padding: 12px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #331a7a;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #666;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>TAMBAH PRODUK</h2>
    <form action="" method="POST">
        <label>Nama Produk</label>
        <input type="text" name="nama_barang" required>
        
        <label>Harga</label>
        <input type="number" name="harga" required>
        
        <label>Stok</label>
        <input type="number" name="stok" required>
        
        <button type="submit" name="simpan">SIMPAN DATA</button>
        <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>
    </form>
</div>

</body>
</html>