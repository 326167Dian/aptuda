<?php
// error_reporting(0);
session_start();
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])) {
    echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
    echo "<a href=../../index.php><b>LOGIN</b></a></center>";
} else {
    include "../../../configurasi/koneksi.php";
    include "../../../configurasi/fungsi_thumb.php";
    include "../../../configurasi/library.php";

    $conn   = $GLOBALS["___mysqli_ston"];
    $module = "trkasir";

    $stt_aksi = $_POST['stt_aksi'];
    if ($stt_aksi == "input_trkasir" || $stt_aksi == "ubah_trkasir") {
        $act = $stt_aksi;
    } else {
        $act = $_GET['act'];
    }

    $id_carabayar = (int)$_POST['id_carabayar'];

    $date_now = date("Y-m-d", time());
    $date_15  = date('Y-m-d', strtotime('-5 days', time()));

    // Input transaksi baru
    if ($module == 'trkasir' AND $act == 'input_trkasir') {

        $nm_pelanggan = mysqli_real_escape_string($conn, $_POST['nm_pelanggan']);

        $caribynama = mysqli_query($conn, "SELECT count(id_trkasir) as jml FROM trkasir
            WHERE nm_pelanggan = '$nm_pelanggan' AND tgl_trkasir <= '$date_15' AND statusbayar = 'PROSES'");
        $temu       = mysqli_fetch_array($caribynama);

        if (($temu['jml'] > 0) AND ($id_carabayar > 1)) {
            $data = array('result' => 'failed');
            echo json_encode($data);
        } else {
            if ($id_carabayar > 1) {
                $totaltransaksi = (float)$_POST['dp_bayar'];
                $nilaitransaksi = (float)$_POST['ttl_trkasir'];
                $status         = 'PROSES';
            } else {
                $totaltransaksi = (float)$_POST['ttl_trkasir'];
                $nilaitransaksi = $totaltransaksi;
                $status         = 'LUNAS';
            }

            $kd_trkasir       = mysqli_real_escape_string($conn, $_POST['kd_trkasir']);
            $petugas          = mysqli_real_escape_string($conn, $_POST['petugas']);
            $tgl_trkasir      = mysqli_real_escape_string($conn, $_POST['tgl_trkasir']);
            $tlp_pelanggan    = mysqli_real_escape_string($conn, $_POST['tlp_pelanggan']);
            $alamat_pelanggan = mysqli_real_escape_string($conn, $_POST['alamat_pelanggan']);
            $diskon           = (float)$_POST['diskon'];
            $diskon2          = (float)$_POST['diskon2'];
            $dp_bayar         = (float)$_POST['dp_bayar'];
            $sisa_bayar       = (float)$_POST['sisa_bayar'];
            $ket_trkasir      = mysqli_real_escape_string($conn, $_POST['ket_trkasir']);
            $id_admin         = (int)$_SESSION['idadmin'];

            mysqli_begin_transaction($conn);

            $success = mysqli_query($conn, "INSERT INTO trkasir(
                kd_trkasir, petugas, tgl_trkasir, nm_pelanggan, tlp_pelanggan,
                alamat_pelanggan, nilai_transaksi, ttl_trkasir, diskon, diskon2,
                dp_bayar, sisa_bayar, ket_trkasir, id_carabayar, statusbayar)
                VALUES('$kd_trkasir', '$petugas', '$tgl_trkasir', '$nm_pelanggan',
                '$tlp_pelanggan', '$alamat_pelanggan', '$nilaitransaksi', '$totaltransaksi',
                '$diskon', '$diskon2', '$dp_bayar', '$sisa_bayar', '$ket_trkasir',
                '$id_carabayar', '$status')");

            if ($success && $id_carabayar == 3) {
                $success = $success && mysqli_query($conn, "INSERT INTO trkasir_tempo(kd_trkasir, angsuran, petugas)
                    VALUES('$kd_trkasir', '$dp_bayar', '$petugas')");
            }

            $success = $success && mysqli_query($conn, "UPDATE kdtk SET stt_kdtk = 'OFF'
                WHERE id_admin = '$id_admin' AND kd_trkasir = '$kd_trkasir'");

            if ($success) {
                mysqli_commit($conn);
                $datas = array('result' => 'success');
                echo json_encode($datas);
            } else {
                mysqli_rollback($conn);
                $datas = array('result' => 'error');
                echo json_encode($datas);
            }
        }

    // Update transaksi
    } elseif ($module == 'trkasir' AND $act == 'ubah_trkasir') {

        $id_trkasir       = (int)$_POST['id_trkasir'];
        $kd_trkasir       = mysqli_real_escape_string($conn, $_POST['kd_trkasir']);
        $tgl_trkasir      = mysqli_real_escape_string($conn, $_POST['tgl_trkasir']);
        $petugas          = mysqli_real_escape_string($conn, $_POST['petugas']);
        $nm_pelanggan     = mysqli_real_escape_string($conn, $_POST['nm_pelanggan']);
        $tlp_pelanggan    = mysqli_real_escape_string($conn, $_POST['tlp_pelanggan']);
        $alamat_pelanggan = mysqli_real_escape_string($conn, $_POST['alamat_pelanggan']);
        $nilaitransaksi   = (float)$_POST['ttl_trkasir'];
        $diskon           = (float)$_POST['diskon'];
        $diskon2          = (float)$_POST['diskon2'];
        $dp_bayar         = (float)$_POST['dp_bayar'];
        $sisa_bayar       = (float)$_POST['sisa_bayar'];
        $ket_trkasir      = mysqli_real_escape_string($conn, $_POST['ket_trkasir']);
        $statusbayar      = mysqli_real_escape_string($conn, $_POST['statusbayar']);

        // tentukan total transaksi sesuai cara bayar
        if ($id_carabayar > 1) {
            $totaltransaksi = $dp_bayar;
        } else {
            $totaltransaksi = $nilaitransaksi;
        }

        mysqli_begin_transaction($conn);

        $success = mysqli_query($conn, "UPDATE trkasir SET
            tgl_trkasir      = '$tgl_trkasir',
            petugas          = '$petugas',
            nm_pelanggan     = '$nm_pelanggan',
            tlp_pelanggan    = '$tlp_pelanggan',
            alamat_pelanggan = '$alamat_pelanggan',
            nilai_transaksi  = '$nilaitransaksi',
            ttl_trkasir      = '$totaltransaksi',
            diskon           = '$diskon',
            diskon2          = '$diskon2',
            dp_bayar         = '$dp_bayar',
            sisa_bayar       = '$sisa_bayar',
            ket_trkasir      = '$ket_trkasir',
            id_carabayar     = '$id_carabayar',
            statusbayar      = '$statusbayar'
            WHERE id_trkasir = '$id_trkasir'");

        if ($success && $id_carabayar == 3) {
            $success = $success && mysqli_query($conn, "INSERT INTO trkasir_tempo(kd_trkasir, angsuran, petugas)
                VALUES('$kd_trkasir', '$dp_bayar', '$petugas')");

            if ($success) {
                $pembayaran  = mysqli_query($conn, "SELECT sum(hrgttl_dtrkasir) as totbayar FROM trkasir_detail
                    WHERE kd_trkasir='$kd_trkasir'");
                $tbayar      = mysqli_fetch_array($pembayaran);
                $tampilbayar = $tbayar['totbayar'];

                $cicil  = mysqli_query($conn, "SELECT sum(angsuran) as totalcicil FROM trkasir_tempo
                    WHERE kd_trkasir='$kd_trkasir'");
                $tc     = mysqli_fetch_array($cicil);
                $tc_new = $tc['totalcicil'];

                $status_bayar = ($tc_new >= $tampilbayar) ? 'LUNAS' : 'PROSES';

                $success = $success && mysqli_query($conn, "UPDATE trkasir SET
                    ttl_trkasir = '$tc_new',
                    statusbayar = '$status_bayar'
                    WHERE kd_trkasir='$kd_trkasir'");
            }
        }

        if ($success) {
            mysqli_commit($conn);
            $data = array('result' => 'success');
            echo json_encode($data);
        } else {
            mysqli_rollback($conn);
            $data = array('result' => 'error');
            echo json_encode($data);
        }

    // Hapus transaksi
    } elseif ($module == 'trkasir' AND $act == 'hapus') {

        $id_hapus = (int)$_GET['id'];

        $ambildatainduk = mysqli_query($conn, "SELECT id_trkasir, kd_trkasir FROM trkasir
            WHERE id_trkasir='$id_hapus'");
        $r1         = mysqli_fetch_array($ambildatainduk);
        $kd_trkasir = mysqli_real_escape_string($conn, $r1['kd_trkasir']);

        $ambildatadetail = mysqli_query($conn, "SELECT id_dtrkasir, kd_trkasir, id_barang, qty_dtrkasir, tipe,
            sum(qty_kasirretail) as qtytr_retail FROM trkasir_detail WHERE kd_trkasir='$kd_trkasir'");

        mysqli_begin_transaction($conn);
        $success = true;

        while ($r = mysqli_fetch_array($ambildatadetail)) {
            $id_dtrkasir  = $r['id_dtrkasir'];
            $id_barang    = $r['id_barang'];
            $qtytr_retail = $r['qtytr_retail'];

            $cekstok     = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang='$id_barang'");
            $rst         = mysqli_fetch_array($cekstok);
            $konversi    = $rst['konversi'];
            $stok_retail = $rst['stok_retail'];

            $stok_retail_akhir = $stok_retail + $qtytr_retail;
            $stok_grosir       = intval($stok_retail_akhir / $konversi);

            $success = $success && mysqli_query($conn, "UPDATE barang SET
                stok_barang = '$stok_grosir',
                stok_retail = '$stok_retail_akhir'
                WHERE id_barang = '$id_barang'");

            $success = $success && mysqli_query($conn, "DELETE FROM trkasir_detail WHERE id_dtrkasir = '$id_dtrkasir'");
            $success = $success && mysqli_query($conn, "DELETE FROM trkasir_tempo WHERE kd_trkasir = '$kd_trkasir'");
        }

        $success = $success && mysqli_query($conn, "DELETE FROM trkasir WHERE id_trkasir = '$id_hapus'");

        if ($success) {
            mysqli_commit($conn);
            echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module={$module}'</script>";
        } else {
            mysqli_rollback($conn);
            echo "<script type='text/javascript'>alert('Gagal menghapus data !');window.location='../../media_admin.php?module={$module}'</script>";
        }

    } elseif ($module == 'trkasir' AND $act == 'tes') {
        $nama       = mysqli_real_escape_string($conn, $_GET['nama']);
        $caribynama = mysqli_query($conn, "SELECT count(id_trkasir) as jml FROM trkasir
            WHERE nm_pelanggan = '$nama' AND tgl_trkasir <= '$date_15' AND statusbayar = 'PROSES'");
        $temu       = mysqli_fetch_array($caribynama);
        echo $temu['jml'];
    }
}
?>
