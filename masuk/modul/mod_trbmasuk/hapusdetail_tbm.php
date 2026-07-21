<?php
include "../../../configurasi/koneksi.php";

$conn = $GLOBALS["___mysqli_ston"];
$id_dtrbmasuk = $_POST['id_dtrbmasuk'];

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    mysqli_begin_transaction($conn);

    // ambil data
    $stmt = mysqli_prepare($conn, "SELECT id_barang, qty_dtrbmasuk, tipe FROM trbmasuk_detail WHERE id_dtrbmasuk = ?");
    mysqli_stmt_bind_param($stmt, "s", $id_dtrbmasuk);
    mysqli_stmt_execute($stmt);
    $r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    $id_barang     = $r['id_barang'];
    $qty_dtrbmasuk = $r['qty_dtrbmasuk'];
    $tipe          = $r['tipe'];

    // update stok
    $stmt = mysqli_prepare($conn, "SELECT stok_barang, stok_retail, konversi FROM barang WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmt, "s", $id_barang);
    mysqli_stmt_execute($stmt);
    $rst = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    $stok_barang = $rst['stok_barang'];
    $stok_retail = $rst['stok_retail'];
    $konversi    = $rst['konversi'];

    if ($tipe == 1) {
        $stok_barang_baru = $stok_barang - $qty_dtrbmasuk;
        $stok_retail_baru = $stok_retail - ($qty_dtrbmasuk * $konversi);
    } elseif ($tipe == 2) {
        $stok_retail_baru = $stok_retail - $qty_dtrbmasuk;
        $stok_barang_baru = intval($stok_retail_baru / $konversi);
    }

    $stmt = mysqli_prepare($conn, "UPDATE barang SET stok_barang = ?, stok_retail = ? WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmt, "sss", $stok_barang_baru, $stok_retail_baru, $id_barang);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "DELETE FROM trbmasuk_detail WHERE id_dtrbmasuk = ?");
    mysqli_stmt_bind_param($stmt, "s", $id_dtrbmasuk);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_commit($conn);

    $data = array(
        'stok_barang_baru' => $stok_barang_baru,
        'stok_retail_baru' => $stok_retail_baru
    );
    echo json_encode($data);
} catch (mysqli_sql_exception $e) {
    mysqli_rollback($conn);
    http_response_code(500);
    echo json_encode(["error" => "Gagal menghapus data, transaksi dibatalkan."]);
}
