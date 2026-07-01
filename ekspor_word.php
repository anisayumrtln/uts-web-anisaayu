<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
include 'koneksi.php'; 

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

$phpWord = new PhpWord();
$section = $phpWord->addSection();
$section->addText("DATA PRODUK MINIMARKET", array('name' => 'Arial', 'size' => 16, 'bold' => true), array('alignment' => 'center'));
$section->addTextBreak(1); 

$styleTable = array('borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80);
$styleHeader = array('bold' => true, 'bgColor' => 'F2F2F2');
$phpWord->addTableStyle('ProdukTable', $styleTable);

$table = $section->addTable('ProdukTable');
$table->addRow();
$table->addCell(1000, $styleHeader)->addText('ID', array('bold' => true));
$table->addCell(4000, $styleHeader)->addText('Nama Barang', array('bold' => true));
$table->addCell(2500, $styleHeader)->addText('Kategori', array('bold' => true));
$table->addCell(2000, $styleHeader)->addText('Harga', array('bold' => true));
$table->addCell(1500, $styleHeader)->addText('Stok', array('bold' => true));

$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id ASC");

while ($data = mysqli_fetch_assoc($query)) {
    $table->addRow();
    $table->addCell(1000)->addText($data['id']);
    $table->addCell(4000)->addText($data['nama_barang']);
    $table->addCell(2500)->addText($data['kategori']);
    $table->addCell(2000)->addText("Rp " . number_format($data['harga'], 0, ',', '.'));
    $table->addCell(1500)->addText($data['stok']);
}

$filename = "Data_Produk_Minimarket.docx";
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Cache-Control: max-age=0');

$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$xmlWriter->save('php://output');
exit;
?>