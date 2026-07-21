<?php
include "../../../configurasi/koneksi.php";

$conn        = $GLOBALS["___mysqli_ston"];
$id_dtrkasir = mysqli_real_escape_string($conn, $_POST['id_dtrkasir']);

$ambildata    = mysqli_query($conn, "SELECT id_dtrkasir, id_barang, qty_dtrkasir, tipe FROM trkasir_detail
    WHERE id_dtrkasir='$id_dtrkasir'");
$r            = mysqli_fetch_array($ambildata);
$id_barang    = $r['id_barang'];
$qty_dtrkasir = $r['qty_dtrkasir'];
$tipe         = $r['tipe'];

$cekstok     = mysqli_query($conn, "SELECT id_barang, stok_barang, stok_retail, konversi FROM barang
    WHERE id_barang='$id_barang'");
$rst         = mysqli_fetch_array($cekstok);
$stok_barang = $rst['stok_barang'];
$stok_retail = $rst['stok_retail'];
$konversi    = $rst['konversi'];

if ($tipe == 1) {
    $stok_barang_baru = $stok_barang + $qty_dtrkasir;
    $stok_retail_baru = $stok_retail + ($qty_dtrkasir * $konversi);
} elseif ($tipe == 2) {
    $stok_retail_baru = $stok_retail + $qty_dtrkasir;
    $stok_barang_baru = intval($stok_retail_baru / $konversi);
}

mysqli_begin_transaction($conn);

$success  = mysqli_query($conn, "UPDATE barang SET
    stok_barang = '$stok_barang_baru',
    stok_retail = '$stok_retail_baru'
    WHERE id_barang = '$id_barang'");
$success  = $success && mysqli_query($conn, "DELETE FROM trkasir_detail WHERE id_dtrkasir = '$id_dtrkasir'");

if ($success) {
    mysqli_commit($conn);
    $data = ['stok_barang_baru' => $stok_barang_baru, 'stok_retail_baru' => $stok_retail_baru];
    echo json_encode($data);
} else {
    mysqli_rollback($conn);
    echo json_encode(['error' => 'Hapus gagal, perubahan dibatalkan']);
}
?>
