<?php 
include "../../../configurasi/koneksi.php";

$id_dtrbmasuk  = $_POST['id_dtrbmasuk'];

//ambil data
$ambildata=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_dtrbmasuk, id_barang, qty_dtrbmasuk, tipe FROM trbmasuk_detail 
WHERE id_dtrbmasuk='$id_dtrbmasuk'");
$r=mysqli_fetch_array($ambildata);

$id_barang = $r['id_barang'];
$qty_dtrbmasuk = $r['qty_dtrbmasuk'];
$tipe = $r['tipe'];


//update stok
$cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_barang, stok_barang, stok_retail, konversi FROM barang 
WHERE id_barang='$id_barang'");
$rst=mysqli_fetch_array($cekstok);

$stok_barang = $rst['stok_barang'];
$stok_retail = $rst['stok_retail'];
$stokakhir = $stok_barang - $qty_dtrbmasuk;
$konversi = $rst['konversi'];

if($tipe == 1){
    $stok_barang_baru = $stok_barang - $qty_dtrbmasuk;
    $stok_retail_baru = $stok_retail - ($qty_dtrbmasuk * $konversi);
} elseif($tipe == 2){
    $stok_retail_baru = $stok_retail - $qty_dtrbmasuk;
    $stok_barang_baru = intval($stok_retail_baru/$konversi);
}


mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET stok_barang = '$stok_barang_baru', stok_retail = '$stok_retail_baru' WHERE id_barang = '$id_barang'");

mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM trbmasuk_detail WHERE id_dtrbmasuk = '$id_dtrbmasuk'");

$data = array(
    'stok_barang_baru'  => $stok_barang_baru,
    'stok_retail_baru'  => $stok_retail_baru
);
echo json_encode($data);
?>
