<?php
include "../../../configurasi/koneksi.php";

$conn = $GLOBALS["___mysqli_ston"];

$kd_trbmasuk       = $_POST['kd_trbmasuk'];
$id_barang         = $_POST['id_barang'];
$kd_barang         = $_POST['kd_barang'];
$nmbrg_dtrbmasuk   = $_POST['nmbrg_dtrbmasuk'];
$qty_dtrbmasuk     = $_POST['qty_dtrbmasuk'];
$diskon            = $_POST['diskon'];
$sat_dtrbmasuk     = $_POST['sat_dtrbmasuk'];
$hrgsat_dtrbmasuk  = $_POST['hrgsat_dtrbmasuk'];
$hrgjual_dtrbmasuk = $_POST['hrgjual_dtrbmasuk'];
$jns_obat          = $_POST['jns_obat'];
$tipe              = $_POST['tipe'];

$faktordiskon = 1 - ($diskon / 100);
$hargajadi    = $hrgsat_dtrbmasuk * $faktordiskon;

if ($qty_dtrbmasuk == "") {
    $qty_dtrbmasuk = "1";
}
if ($diskon == "") {
    $diskon = "0";
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    mysqli_begin_transaction($conn);

    $stmt = mysqli_prepare($conn, "SELECT konversi FROM barang WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmt, "s", $id_barang);
    mysqli_stmt_execute($stmt);
    $rst = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    $konversi = $rst['konversi'];

    if ($tipe == 1) {
        $qty_masukretail = $qty_dtrbmasuk * $konversi;
    } else {
        $qty_masukretail = $qty_dtrbmasuk;
    }

    // cek apakah barang sudah ada
    $stmt = mysqli_prepare($conn, "SELECT * FROM trbmasuk_detail WHERE kd_barang = ? AND kd_trbmasuk = ? AND tipe = ?");
    mysqli_stmt_bind_param($stmt, "sss", $kd_barang, $kd_trbmasuk, $tipe);
    mysqli_stmt_execute($stmt);
    $rcek = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if ($rcek) {

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
            $id_dtrbmasuk  = $rcek['id_dtrbmasuk'];
            $qtylama       = $rcek['qty_dtrbmasuk'];
            $qtyretaillama = $rcek['qty_mskretail'];
            $qtyretailbaru = $qtyretaillama + ($qty_dtrbmasuk * $konversi);
            $ttlqty        = $qtylama + $qty_dtrbmasuk;
            $ttlharga      = $ttlqty * $hrgsat_dtrbmasuk * $faktordiskon;

            $stmt = mysqli_prepare($conn, "UPDATE trbmasuk_detail SET
                    qty_dtrbmasuk = ?,
                    qty_mskretail = ?,
                    diskon = ?,
                    hrgsat_dtrbmasuk = ?,
                    hrgttl_dtrbmasuk = ?
                    WHERE id_dtrbmasuk = ?");
            mysqli_stmt_bind_param($stmt, "ssssss", $ttlqty, $qtyretailbaru, $diskon, $hrgsat_dtrbmasuk, $ttlharga, $id_dtrbmasuk);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $stok_retail_baru = $stok_retail + ($qty_dtrbmasuk * $konversi);
            $stok_barang_baru = $stok_barang + $qty_dtrbmasuk;

            $stmt = mysqli_prepare($conn, "UPDATE barang SET
                    stok_barang = ?,
                    hrgsat_barang = ?,
                    stok_retail = ?,
                    hrgjual_barang = ?,
                    jenisobat = ?
                    WHERE id_barang = ?");
            mysqli_stmt_bind_param($stmt, "ssssss", $stok_barang_baru, $hargajadi, $stok_retail_baru, $hrgjual_dtrbmasuk, $jns_obat, $id_barang);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $data = array(
                'stok_barang_baru' => $stok_barang_baru,
                'stok_retail_baru' => $stok_retail_baru
            );
        } else {
            $id_dtrbmasuk  = $rcek['id_dtrbmasuk'];
            $qtylama       = $rcek['qty_dtrbmasuk'];
            $qtyretaillama = $rcek['qty_mskretail'];
            $qtyretailbaru = $qtyretaillama + $qty_dtrbmasuk;
            $ttlqty        = $qtylama + $qty_dtrbmasuk;
            $ttlharga      = $qtyretailbaru * $hrgsat_dtrbmasuk * $faktordiskon;

            $stmt = mysqli_prepare($conn, "UPDATE trbmasuk_detail SET
                    qty_dtrbmasuk = ?,
                    qty_mskretail = ?,
                    hrgsat_dtrbmasuk = ?,
                    hrgttl_dtrbmasuk = ?
                    WHERE id_dtrbmasuk = ?");
            mysqli_stmt_bind_param($stmt, "sssss", $ttlqty, $qtyretailbaru, $hrgsat_dtrbmasuk, $ttlharga, $id_dtrbmasuk);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $stok_retail_baru = $stok_retail + $qty_dtrbmasuk;
            $w = intval($stok_retail_baru / $konversi);

            $stmt = mysqli_prepare($conn, "UPDATE barang SET
                    stok_barang = ?,
                    stok_retail = ?,
                    hrgsat_retail = ?,
                    hrgjual_retail = ?,
                    jenisobat = ?
                    WHERE id_barang = ?");
            mysqli_stmt_bind_param($stmt, "ssssss", $w, $stok_retail_baru, $hargajadi, $hrgjual_dtrbmasuk, $jns_obat, $id_barang);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $data = array(
                'stok_barang_baru' => $w,
                'stok_retail_baru' => $stok_retail_baru
            );
        }
    } else {
        $faktordiskon = 1 - ($diskon / 100);
        $ttlharga = $qty_dtrbmasuk * $hrgsat_dtrbmasuk * $faktordiskon;

        $stmt = mysqli_prepare($conn, "INSERT INTO trbmasuk_detail
                (kd_trbmasuk, id_barang, kd_barang, nmbrg_dtrbmasuk, qty_dtrbmasuk, qty_mskretail,
                 diskon, sat_dtrbmasuk, hrgsat_dtrbmasuk, hrgttl_dtrbmasuk, tipe)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssss",
            $kd_trbmasuk,
            $id_barang,
            $kd_barang,
            $nmbrg_dtrbmasuk,
            $qty_dtrbmasuk,
            $qty_masukretail,
            $diskon,
            $sat_dtrbmasuk,
            $hrgsat_dtrbmasuk,
            $ttlharga,
            $tipe
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

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
            $stok_retail_baru = $stok_retail + ($qty_dtrbmasuk * $konversi);
            $stok_barang_baru = $stok_barang + $qty_dtrbmasuk;

            $stmt = mysqli_prepare($conn, "UPDATE barang SET
                    stok_barang = ?,
                    stok_retail = ?,
                    hrgsat_barang = ?,
                    hrgjual_barang = ?,
                    jenisobat = ?
                    WHERE id_barang = ?");
            mysqli_stmt_bind_param($stmt, "ssssss", $stok_barang_baru, $stok_retail_baru, $hargajadi, $hrgjual_dtrbmasuk, $jns_obat, $id_barang);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $data = array(
                'stok_barang_baru' => $stok_barang_baru,
                'stok_retail_baru' => $stok_retail_baru
            );
        } elseif ($tipe == 2) {
            $stok_retail_baru = $stok_retail + $qty_dtrbmasuk;
            $w = intval($stok_retail_baru / $konversi);

            $stmt = mysqli_prepare($conn, "UPDATE barang SET
                    stok_barang = ?,
                    stok_retail = ?,
                    hrgsat_retail = ?,
                    hrgjual_retail = ?,
                    jenisobat = ?
                    WHERE id_barang = ?");
            mysqli_stmt_bind_param($stmt, "ssssss", $w, $stok_retail_baru, $hargajadi, $hrgjual_dtrbmasuk, $jns_obat, $id_barang);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $data = array(
                'stok_barang_baru' => $w,
                'stok_retail_baru' => $stok_retail_baru
            );
        }
    }

    mysqli_commit($conn);
    echo json_encode($data);
} catch (mysqli_sql_exception $e) {
    mysqli_rollback($conn);
    http_response_code(500);
    echo json_encode(["error" => "Gagal menyimpan data, transaksi dibatalkan."]);
}
