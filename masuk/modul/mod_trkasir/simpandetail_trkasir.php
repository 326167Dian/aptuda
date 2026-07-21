<?php
include "../../../configurasi/koneksi.php";

$conn = $GLOBALS["___mysqli_ston"];

$kd_trkasir       = mysqli_real_escape_string($conn, $_POST['kd_trkasir']);
$id_dtrkasir      = mysqli_real_escape_string($conn, $_POST['id_dtrkasir']);
$id_barang        = mysqli_real_escape_string($conn, $_POST['id_barang']);
$kd_barang        = mysqli_real_escape_string($conn, $_POST['kd_barang']);
$nmbrg_dtrkasir   = mysqli_real_escape_string($conn, $_POST['nmbrg_dtrkasir']);
$qty_dtrkasir     = (int)$_POST['qty_dtrkasir'];
$sat_dtrkasir     = mysqli_real_escape_string($conn, $_POST['sat_dtrkasir']);
$hrgjual_dtrkasir = (float)$_POST['hrgjual_dtrkasir'];
$disc             = (float)$_POST['disc'];
$tipe             = (int)$_POST['tipe'];

$hrgdisc = $hrgjual_dtrkasir - $disc;

if ($qty_dtrkasir == 0) {
    $qty_dtrkasir = 1;
}

mysqli_begin_transaction($conn);

$success = true;
$data    = [];

// kondisi 1: tambah (id_dtrkasir kosong)
if ($id_dtrkasir == "" || $id_dtrkasir == null) {

    $cekdetail        = mysqli_query($conn, "SELECT id_barang, kd_barang, kd_trkasir, id_dtrkasir, qty_dtrkasir
        FROM trkasir_detail
        WHERE kd_barang='$kd_barang' AND kd_trkasir='$kd_trkasir' AND tipe=$tipe");
    $ketemucekdetail  = mysqli_num_rows($cekdetail);
    $rcek             = mysqli_fetch_array($cekdetail);

    // kondisi 1.1: barang sudah ada di detail - update qty
    if ($ketemucekdetail > 0) {

        $id_dtrkasir = $rcek['id_dtrkasir'];
        $qtylama     = $rcek['qty_dtrkasir'];
        $ttlqty      = $qtylama + $qty_dtrkasir;
        $ttlharga    = $ttlqty * $hrgdisc;

        $success = $success && mysqli_query($conn, "UPDATE trkasir_detail SET
            qty_dtrkasir    = '$ttlqty',
            hrgjual_dtrkasir = '$hrgdisc',
            disc            = '$disc',
            hrgttl_dtrkasir = '$ttlharga'
            WHERE id_dtrkasir = '$id_dtrkasir' AND kd_barang='$kd_barang'");

        $cekstok     = mysqli_query($conn, "SELECT id_barang, stok_barang, stok_retail, konversi FROM barang
            WHERE id_barang='$id_barang'");
        $rst         = mysqli_fetch_array($cekstok);
        $stok_barang = $rst['stok_barang'];
        $stok_retail = $rst['stok_retail'];
        $konversi    = $rst['konversi'];

        if ($tipe == 1) {
            $stok_retail_baru = $stok_retail - ($qty_dtrkasir * $konversi);
            $stok_barang_baru = $stok_barang - $qty_dtrkasir;

            $success = $success && mysqli_query($conn, "UPDATE barang SET
                stok_barang    = '$stok_barang_baru',
                stok_retail    = '$stok_retail_baru',
                sat_barang     = '$sat_dtrkasir',
                hrgjual_barang = '$hrgjual_dtrkasir'
                WHERE id_barang = '$id_barang'");

            $data = ['stok_barang_baru' => $stok_barang_baru, 'stok_retail_baru' => $stok_retail_baru];

        } elseif ($tipe == 2) {
            $stok_retail_baru = $stok_retail - $qty_dtrkasir;
            $w                = intval($stok_retail_baru / $konversi);

            $success = $success && mysqli_query($conn, "UPDATE barang SET
                stok_barang     = '$w',
                stok_retail     = '$stok_retail_baru',
                sat_retail      = '$sat_dtrkasir',
                hrgjual_retail  = '$hrgjual_dtrkasir'
                WHERE id_barang = '$id_barang'");

            $data = ['stok_barang_baru' => $w, 'stok_retail_baru' => $stok_retail_baru];
        }

    // kondisi 1.2: barang belum ada di detail - insert baru
    } else {

        $ttlharga = $qty_dtrkasir * $hrgdisc;

        $cekstok  = mysqli_query($conn, "SELECT id_barang, stok_barang, stok_retail, konversi,
            hrgsat_barang, hrgsat_retail FROM barang WHERE id_barang='$id_barang'");
        $rst      = mysqli_fetch_array($cekstok);
        $konversi = $rst['konversi'];

        if ($tipe == 1) {
            $qty_kasirretail = $qty_dtrkasir * $konversi;
            $modal           = $rst['hrgsat_barang'];
        } else {
            $qty_kasirretail = $qty_dtrkasir;
            $modal           = $rst['hrgsat_retail'];
        }
        $finish = $qty_dtrkasir * ($hrgdisc - $modal);

        $success = $success && mysqli_query($conn, "INSERT INTO trkasir_detail(
            kd_trkasir, id_barang, kd_barang, nmbrg_dtrkasir, qty_dtrkasir,
            qty_kasirretail, sat_dtrkasir, hrgjual_dtrkasir, disc, hrgbeli, finish, hrgttl_dtrkasir, tipe)
            VALUES('$kd_trkasir', '$id_barang', '$kd_barang', '$nmbrg_dtrkasir', '$qty_dtrkasir',
            '$qty_kasirretail', '$sat_dtrkasir', '$hrgdisc', '$disc', '$modal', '$finish', '$ttlharga', '$tipe')");

        if ($success) {
            $cekstok2    = mysqli_query($conn, "SELECT id_barang, stok_barang, stok_retail, konversi FROM barang
                WHERE id_barang='$id_barang'");
            $rst2        = mysqli_fetch_array($cekstok2);
            $stok_barang = $rst2['stok_barang'];
            $stok_retail = $rst2['stok_retail'];
            $konversi    = $rst2['konversi'];

            if ($tipe == 1) {
                $stok_retail_baru = $stok_retail - ($qty_dtrkasir * $konversi);
                $stok_barang_baru = $stok_barang - $qty_dtrkasir;

                $success = $success && mysqli_query($conn, "UPDATE barang SET
                    stok_barang    = '$stok_barang_baru',
                    stok_retail    = '$stok_retail_baru',
                    sat_barang     = '$sat_dtrkasir',
                    hrgjual_barang = '$hrgjual_dtrkasir'
                    WHERE id_barang = '$id_barang'");

                $data = ['stok_barang_baru' => $stok_barang_baru, 'stok_retail_baru' => $stok_retail_baru];

            } elseif ($tipe == 2) {
                $stok_retail_baru = $stok_retail - $qty_dtrkasir;
                $w                = intval($stok_retail_baru / $konversi);

                $success = $success && mysqli_query($conn, "UPDATE barang SET
                    stok_barang    = '$w',
                    stok_retail    = '$stok_retail_baru',
                    sat_retail     = '$sat_dtrkasir',
                    hrgjual_retail = '$hrgjual_dtrkasir'
                    WHERE id_barang = '$id_barang'");

                $data = ['stok_barang_baru' => $w, 'stok_retail_baru' => $stok_retail_baru];
            }
        }
    }

// kondisi 2: edit (id_dtrkasir ada)
} else {

    $cekdetail = mysqli_query($conn, "SELECT * FROM trkasir_detail
        WHERE id_dtrkasir='$id_dtrkasir'");
    $rcek      = mysqli_fetch_array($cekdetail);
    $qtylama   = $rcek['qty_dtrkasir'];
    $qtybaru   = $qtylama + $qty_dtrkasir;
    $ttlharga  = $qtybaru * $hrgdisc;

    $success = $success && mysqli_query($conn, "UPDATE trkasir_detail SET
        qty_dtrkasir    = '$qtybaru',
        hrgjual_dtrkasir = '$hrgdisc',
        disc            = '$disc',
        hrgttl_dtrkasir = '$ttlharga'
        WHERE id_dtrkasir = '$id_dtrkasir'");

    $cekstok     = mysqli_query($conn, "SELECT id_barang, stok_barang, stok_retail, konversi FROM barang
        WHERE id_barang='$id_barang'");
    $rst         = mysqli_fetch_array($cekstok);
    $stok_barang = $rst['stok_barang'];
    $stok_retail = $rst['stok_retail'];
    $konversi    = $rst['konversi'];

    if ($tipe == 1) {
        $stok_retail_baru = $stok_retail - ($qty_dtrkasir * $konversi);
        $stok_barang_baru = $stok_barang - $qty_dtrkasir;

        $success = $success && mysqli_query($conn, "UPDATE barang SET
            stok_barang    = '$stok_barang_baru',
            stok_retail    = '$stok_retail_baru',
            sat_barang     = '$sat_dtrkasir',
            hrgjual_barang = '$hrgjual_dtrkasir'
            WHERE id_barang = '$id_barang'");

        $data = ['stok_barang_baru' => $stok_barang_baru, 'stok_retail_baru' => $stok_retail_baru];

    } elseif ($tipe == 2) {
        $stok_retail_baru = $stok_retail - $qty_dtrkasir;
        $w                = intval($stok_retail_baru / $konversi);

        $success = $success && mysqli_query($conn, "UPDATE barang SET
            stok_barang    = '$w',
            stok_retail    = '$stok_retail_baru',
            sat_retail     = '$sat_dtrkasir',
            hrgjual_retail = '$hrgjual_dtrkasir'
            WHERE id_barang = '$id_barang'");

        $data = ['stok_barang_baru' => $w, 'stok_retail_baru' => $stok_retail_baru];
    }
}

if ($success) {
    mysqli_commit($conn);
    echo json_encode($data);
} else {
    mysqli_rollback($conn);
    echo json_encode(['error' => 'Transaksi gagal, semua perubahan dibatalkan']);
}
?>
