<?php
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

    $module=$_GET['module'];
    $act=$_GET['act'];

// Input admin
    if ($module=='catatan' AND $act=='input_jenistransaksi'){

        $cekganda=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT nm_jurnal FROM jenis_jurnal WHERE nm_jurnal='$_POST[nm_jurnal]'");
        $ada=mysqli_num_rows($cekganda);
        if ($ada > 0){
            echo "<script type='text/javascript'>alert('Jenis Transaksi sudah ada!');history.go(-1);</script>";
        }else{

            mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO jenis_jurnal (
                                        nm_jurnal,
                                        tipe
                                        )
								 VALUES(
								        '$_POST[nm_jurnal]',
								        '$_POST[tipe]'
								        )");


            echo "<script type='text/javascript'>alert('Data berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
            header('location:../../media_admin.php?module='.$module .'&act=jenistransaksi');

        }
    }
    //updata catatan
    elseif ($module=='catatan' AND $act=='update_jenistransaksi'){

        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE jenis_jurnal SET   
                                    nm_jurnal = '$_POST[nm_jurnal]'
									WHERE idjenis = '$_POST[id]'");

        //echo "<script type='text/javascript'>alert('Data berhasil diubah !');window.location='../../media_admin.php?module=".$module."'</script>";
        header('location:../../media_admin.php?module='.$module. '&act=jenistransaksi');

    }
//Hapus Proyek
    elseif ($module=='catatan' AND $act=='hapus'){
        $petugas = $_SESSION['namalengkap'];
        $delete=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jenis_jurnal WHERE idjenis='$_GET[id]'");
        $r=mysqli_fetch_array($delete);

        if ( $petugas !== $r['petugas'] && $_SESSION['level']!=='pemilik')
        { echo "<script type='text/javascript'>alert('catatan harus dihapus orang yang sama atau pemilik apotek!');history.go(-1);</script>";}
        else{
            mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM jenis_jurnal WHERE idjenis = '$_GET[id]'");
            //echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
            header('location:../../media_admin.php?module='.$module.'&act=jenistransaksi');

        }
    }

}
?>
