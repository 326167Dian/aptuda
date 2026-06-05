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
    $tgl_awal = date('Y-m-d');
    $curtime = date('ymdHis');
    $nama = $_SESSION['namalengkap'];
// Input admin
    if ($module=='catatan' AND $act=='input_jurnal'){

       mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO jurnal (
                                        tanggal,
                                        ket,
                                        petugas,
                                        idjenis,
                                        carabayar,
                                        debit,
                                        current
                                        )
								 VALUES(
								        '$tgl_awal',
								        '$_POST[ket]',
								        '$nama',
								        '$_POST[idjenis]',
								        '$_POST[carabayar]',
								        '$_POST[debit]',
								        '$curtime'
								        )");


            //echo "<script type='text/javascript'>alert('Data berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
            header('location:../../media_admin.php?module='.$module);


    }
    elseif ($module=='catatan' AND $act=='input_jurnal2'){

        mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO jurnal (
                                        tanggal,
                                        ket,
                                        petugas,
                                        idjenis,
                                        carabayar,
                                        kredit,
                                        current
                                        )
								 VALUES(
								        '$tgl_awal',
								        '$_POST[ket]',
								        '$nama',								        
								        '$_POST[idjenis]',
								        '$_POST[carabayar]',
								        '$_POST[kredit]',
								        '$curtime'
								        )");


        //echo "<script type='text/javascript'>alert('Data berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
        header('location:../../media_admin.php?module='.$module);


    }
    //updata catatan
    elseif ($module=='catatan' AND $act=='update_jurnal'){
        $petugas = $_SESSION['namalengkap'];
        $sesi = $_SESSION['level'];

        $edit=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jurnal WHERE id_jurnal='$_POST[id]'");
        $r=mysqli_fetch_array($edit);

        if ( ($petugas == $r['petugas']) or $sesi =='pemilik' )
        {  mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE jurnal SET                                       
                                    ket = '$_POST[ket]',
                                    idjenis = '$_POST[idjenis]',                                 
                                    carabayar = '$_POST[carabayar]',                                 
                                    debit = '$_POST[debit]',
                                    kredit = '$_POST[kredit]',
                                    current = '$curtime'
									WHERE id_jurnal = '$_POST[id]'");

            //echo "<script type='text/javascript'>alert('Data berhasil diubah !');window.location='../../media_admin.php?module=".$module."'</script>";
            header('location:../../media_admin.php?module=' . $module);
            
         }
        else {

              echo "<script type='text/javascript'>alert('Jurnal hanya bisa diedit orang yang sama atau pemilik apotek!');history.go(-1);</script>";
        }
    }
//Hapus Proyek
    elseif ($module=='catatan' AND $act=='hapus'){
        $petugas = $_SESSION['namalengkap'];
        $sesi = $_SESSION['level'];
        $edit=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jurnal WHERE id_jurnal='$_GET[id]'");
        $r=mysqli_fetch_array($edit);

        if ( $petugas == $r['petugas'] or $sesi =='pemilik')
        { mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM jurnal WHERE id_jurnal = '$_GET[id]'");
            //echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
            header('location:../../media_admin.php?module='.$module);
            }
        else{
            echo "<script type='text/javascript'>alert('Jurnal hanya bisa dihapus orang yang sama atau pemilik apotek!');history.go(-1);</script>";
        }
    }

}
?>
