<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
include 'koneksi.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nama Barang');
$sheet->setCellValue('C1', 'Kategori');
$sheet->setCellValue('D1', 'Harga');
$sheet->setCellValue('E1', 'Stok');

$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id ASC");
$row_num = 2; 

while($data = mysqli_fetch_assoc($query)) {
    $sheet->setCellValue('A' . $row_num, $data['id']); 
    $sheet->setCellValue('B' . $row_num, $data['nama_barang']);
    $sheet->setCellValue('C' . $row_num, $data['kategori']);
    $sheet->setCellValue('D' . $row_num, $data['harga']);
    $sheet->setCellValue('E' . $row_num, $data['stok']);
    $row_num++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Data_Produk_Minimarket.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>