<?php
include "../../../configurasi/koneksi.php";

$conn = $GLOBALS["___mysqli_ston"];

$kd_trbmasuk       = $_POST['kd_trbmasuk'];
$id_barang         = $_POST['id_barang'];
$kd_barang         = $_POST['kd_barang'];
$nmbrg_dtrbmasuk   = $_POST['nmbrg_dtrbmasuk'];
$qty_dtrbmasuk     = $_POST['qty_dtrbmasuk'];
$sat_dtrbmasuk     = $_POST['sat_dtrbmasuk'];
$hnasat_dtrbmasuk  = $_POST['hnasat_dtrbmasuk'];
$diskon            = $_POST['diskon'];
$hrgjual_dtrbmasuk = $_POST['hrgjual_dtrbmasuk'];
$jns_obat          = $_POST['jns_obat'];

if ($qty_dtrbmasuk == "") {
    $qty_dtrbmasuk = "1";
}
if ($diskon == "") {
    $diskon = "0";
}

$faktordiskon     = 1 - ($diskon / 100);
$hrgsat_dtrbmasuk = $hnasat_dtrbmasuk * 1.11;
$hrgjadi          = $hrgsat_dtrbmasuk * $faktordiskon;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    mysqli_begin_transaction($conn);

    // cek apakah barang sudah ada di detail
    $stmt = mysqli_prepare($conn, "SELECT id_dtrbmasuk, qty_dtrbmasuk, qty_mskretail
        FROM trbmasuk_detail WHERE kd_barang = ? AND kd_trbmasuk = ?");
    mysqli_stmt_bind_param($stmt, "ss", $kd_barang, $kd_trbmasuk);
    mysqli_stmt_execute($stmt);
    $rcek = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "SELECT konversi FROM barang WHERE kd_barang = ?");
    mysqli_stmt_bind_param($stmt, "s", $kd_barang);
    mysqli_stmt_execute($stmt);
    $konversi2 = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    $konversi = $konversi2['konversi'];

    if ($rcek) {
        $id_dtrbmasuk  = $rcek['id_dtrbmasuk'];
        $qtylama       = $rcek['qty_dtrbmasuk'];
        $qtyretaillama = $rcek['qty_mskretail'];
        $qtyretailbaru = $qtyretaillama + ($qty_dtrbmasuk * $konversi);
        $ttlqty        = $qtylama + $qty_dtrbmasuk;
        $ttlharga      = $ttlqty * $hnasat_dtrbmasuk;

        $stmt = mysqli_prepare($conn, "UPDATE trbmasuk_detail SET
                qty_dtrbmasuk = ?,
                qty_mskretail = ?,
                hnasat_dtrbmasuk = ?,
                diskon = ?,
                hrgsat_dtrbmasuk = ?,
                hrgttl_dtrbmasuk = ?
                WHERE id_dtrbmasuk = ?");
        mysqli_stmt_bind_param($stmt, "sssssss", $ttlqty, $qtyretailbaru, $hnasat_dtrbmasuk, $diskon, $hrgsat_dtrbmasuk, $ttlharga, $id_dtrbmasuk);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // update stok
        $stmt = mysqli_prepare($conn, "SELECT stok_barang, stok_retail, konversi FROM barang WHERE id_barang = ?");
        mysqli_stmt_bind_param($stmt, "s", $id_barang);
        mysqli_stmt_execute($stmt);
        $rst = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        $stok_barang     = $rst['stok_barang'];
        $konversi        = $rst['konversi'];
        $stok_retail     = $rst['stok_retail'];
        $stok_retailbaru = $stok_retail + ($qty_dtrbmasuk * $konversi);
        $stokakhir       = ($stok_barang - $qtylama) + $ttlqty;

        $stmt = mysqli_prepare($conn, "UPDATE barang SET
                stok_barang = ?,
                stok_retail = ?,
                hna = ?,
                hrgsat_barang = ?,
                hrgjual_barang = ?,
                jenisobat = ?
                WHERE id_barang = ?");
        mysqli_stmt_bind_param($stmt, "sssssss", $stokakhir, $stok_retailbaru, $hnasat_dtrbmasuk, $hrgjadi, $hrgjual_dtrbmasuk, $jns_obat, $id_barang);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);
        echo json_encode($stokakhir);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT konversi FROM barang WHERE id_barang = ?");
        mysqli_stmt_bind_param($stmt, "s", $id_barang);
        mysqli_stmt_execute($stmt);
        $rst = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        $faktordiskon  = 1 - ($diskon / 100);
        $ttlharga      = $qty_dtrbmasuk * $hnasat_dtrbmasuk * $faktordiskon;
        $qty_mskretail = $qty_dtrbmasuk * $rst['konversi'];

        $stmt = mysqli_prepare($conn, "INSERT INTO trbmasuk_detail
                (kd_trbmasuk, id_barang, kd_barang, nmbrg_dtrbmasuk, qty_dtrbmasuk, qty_mskretail,
                 sat_dtrbmasuk, hnasat_dtrbmasuk, diskon, hrgsat_dtrbmasuk, hrgttl_dtrbmasuk)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssss",
            $kd_trbmasuk,
            $id_barang,
            $kd_barang,
            $nmbrg_dtrbmasuk,
            $qty_dtrbmasuk,
            $qty_mskretail,
            $sat_dtrbmasuk,
            $hnasat_dtrbmasuk,
            $diskon,
            $hrgsat_dtrbmasuk,
            $ttlharga
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // update stok, hna, hrgbrg+ppn
        $stmt = mysqli_prepare($conn, "SELECT stok_barang, stok_retail, konversi FROM barang WHERE id_barang = ?");
        mysqli_stmt_bind_param($stmt, "s", $id_barang);
        mysqli_stmt_execute($stmt);
        $rst = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        $stok_barang     = $rst['stok_barang'];
        $konversi        = $rst['konversi'];
        $stok_retail     = $rst['stok_retail'];
        $stok_retailbaru = $stok_retail + ($qty_dtrbmasuk * $konversi);
        $stokakhir       = $stok_barang + $qty_dtrbmasuk;

        $stmt = mysqli_prepare($conn, "UPDATE barang SET
                stok_barang = ?,
                stok_retail = ?,
                hna = ?,
                hrgsat_barang = ?,
                hrgjual_barang = ?,
                jenisobat = ?
                WHERE id_barang = ?");
        mysqli_stmt_bind_param($stmt, "sssssss", $stokakhir, $stok_retailbaru, $hnasat_dtrbmasuk, $hrgjadi, $hrgjual_dtrbmasuk, $jns_obat, $id_barang);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);
        echo json_encode($stokakhir);
    }
} catch (mysqli_sql_exception $e) {
    mysqli_rollback($conn);
    http_response_code(500);
    echo json_encode(["error" => "Gagal menyimpan data, transaksi dibatalkan."]);
}
