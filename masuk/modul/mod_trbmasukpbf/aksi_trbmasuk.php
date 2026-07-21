<?php
error_reporting(0);
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../../configurasi/koneksi.php";
include "../../../configurasi/fungsi_thumb.php";
include "../../../configurasi/library.php";

$conn = $GLOBALS["___mysqli_ston"];
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$module= "trbmasukpbf";
$stt_aksi=$_POST['stt_aksi'];
if($stt_aksi == "input_trbmasuk" || $stt_aksi == "ubah_trbmasuk"){
$act=$stt_aksi;
}else{
$act=$_GET['act'];
}


// Input admin
if ($module=='trbmasukpbf' AND $act=='input_trbmasuk'){

    try {
        mysqli_begin_transaction($conn);

        $stmt = mysqli_prepare($conn, "INSERT INTO
                trbmasuk(id_resto,
                        kd_trbmasuk,
                        tgl_trbmasuk,
                        id_supplier,
                        petugas,
                        nm_supplier,
                        tlp_supplier,
                        alamat_trbmasuk,
                        ttl_trbmasuk,
                        dp_bayar,
                        sisa_bayar,
                        ket_trbmasuk,
                        jatuhtempo,
                        carabayar,
                        pbf)
                VALUES ('pusat', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pbf')");
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssss",
            $_POST['kd_trbmasuk'],
            $_POST['tgl_trbmasuk'],
            $_POST['id_supplier'],
            $_POST['petugas'],
            $_POST['nm_supplier'],
            $_POST['tlp_supplier'],
            $_POST['alamat_trbmasuk'],
            $_POST['ttl_trkasir'],
            $_POST['dp_bayar'],
            $_POST['sisa_bayar'],
            $_POST['ket_trbmasuk'],
            $_POST['jatuhtempo'],
            $_POST['carabayar']
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($conn, "UPDATE kdbm SET stt_kdbm = 'OFF'
                WHERE id_admin = ? AND id_resto = 'pusat' AND kd_trbmasuk = ?");
        mysqli_stmt_bind_param($stmt, "ss", $_SESSION['idadmin'], $_POST['kd_trbmasuk']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);

        //echo "<script type='text/javascript'>alert('Transkasi berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conn);
        echo "<script type='text/javascript'>alert('Transaksi gagal disimpan, silakan coba lagi !');window.location='../../media_admin.php?module=".$module."'</script>";
    }

}
 //updata trbmasukpbf
 elseif ($module=='trbmasukpbf' AND $act=='ubah_trbmasuk'){

    try {
        mysqli_begin_transaction($conn);

        $stmt = mysqli_prepare($conn, "UPDATE trbmasuk SET
                tgl_trbmasuk = ?,
                id_supplier = ?,
                nm_supplier = ?,
                tlp_supplier = ?,
                alamat_trbmasuk = ?,
                ttl_trbmasuk = ?,
                dp_bayar = ?,
                sisa_bayar = ?,
                ket_trbmasuk = ?,
                jatuhtempo = ?,
                carabayar = ?
                WHERE id_trbmasuk = ?");
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssss",
            $_POST['tgl_trbmasuk'],
            $_POST['id_supplier'],
            $_POST['nm_supplier'],
            $_POST['tlp_supplier'],
            $_POST['alamat_trbmasuk'],
            $_POST['ttl_trkasir'],
            $_POST['dp_bayar'],
            $_POST['sisa_bayar'],
            $_POST['ket_trbmasuk'],
            $_POST['jatuhtempo'],
            $_POST['carabayar'],
            $_POST['id_trbmasuk']
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);

        //echo "<script type='text/javascript'>alert('Transkasi berhasil Ubah !');window.location='../../media_admin.php?module=".$module."'</script>";
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conn);
        echo "<script type='text/javascript'>alert('Transaksi gagal diubah, silakan coba lagi !');window.location='../../media_admin.php?module=".$module."'</script>";
    }

}
//Hapus Proyek
elseif ($module=='trbmasukpbf' AND $act=='hapus'){

  try {
    mysqli_begin_transaction($conn);

    //ambil data induk
    $stmt = mysqli_prepare($conn, "SELECT kd_trbmasuk FROM trbmasuk WHERE id_trbmasuk = ?");
    mysqli_stmt_bind_param($stmt, "s", $_GET['id']);
    mysqli_stmt_execute($stmt);
    $r1 = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    $kd_trbmasuk = $r1['kd_trbmasuk'];

    //ambil data detail
    $stmt = mysqli_prepare($conn, "SELECT * FROM trbmasuk_detail WHERE kd_trbmasuk = ?");
    mysqli_stmt_bind_param($stmt, "s", $kd_trbmasuk);
    mysqli_stmt_execute($stmt);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    //loop data detail
    foreach ($rows as $r) {

        $id_dtrbmasuk  = $r['id_dtrbmasuk'];
        $id_barang     = $r['id_barang'];
        $qty_dtrbmasuk = $r['qty_dtrbmasuk'];
        $qtytrb_retail = $r['qtytrb_retail'];

        //update stok
        $stmt2 = mysqli_prepare($conn, "SELECT stok_barang, stok_retail, konversi FROM barang WHERE id_barang = ?");
        mysqli_stmt_bind_param($stmt2, "s", $id_barang);
        mysqli_stmt_execute($stmt2);
        $rst = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));
        mysqli_stmt_close($stmt2);

        $stok_barang = $rst['stok_barang'];
        $stokakhir   = $stok_barang - $qty_dtrbmasuk;
        $stok_retail = $rst['stok_retail'];

        $stok_retail_akhir = $stok_retail - $qtytrb_retail;

        $stmt2 = mysqli_prepare($conn, "UPDATE barang SET
                stok_barang = ?,
                stok_retail = ?
                WHERE id_barang = ?");
        mysqli_stmt_bind_param($stmt2, "sss", $stokakhir, $stok_retail_akhir, $id_barang);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        $stmt2 = mysqli_prepare($conn, "DELETE FROM trbmasuk_detail WHERE id_dtrbmasuk = ?");
        mysqli_stmt_bind_param($stmt2, "s", $id_dtrbmasuk);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

    }

    $stmt = mysqli_prepare($conn, "DELETE FROM trbmasuk WHERE id_trbmasuk = ?");
    mysqli_stmt_bind_param($stmt, "s", $_GET['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_commit($conn);

    echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
  } catch (mysqli_sql_exception $e) {
    mysqli_rollback($conn);
    echo "<script type='text/javascript'>alert('Data gagal dihapus, silakan coba lagi !');window.location='../../media_admin.php?module=".$module."'</script>";
  }
}

}
?>
