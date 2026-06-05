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
//update kas debit
        //cek tambah stok
        $kurangkas = mysqli_query($GLOBALS["___mysqli_ston"],"select * from kas where id_kas=1 ");
        $kaslama = mysqli_fetch_array($kurangkas);
        $kasdebit = $kaslama['saldo'] - $_POST['debit'] ;

        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kas SET saldo = '$kasdebit'
                                                where id_kas =1 ");
        // if($angka==$ttlqty) {

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

        //update kas kredit
        //cek tambah stok
        $tambahkas = mysqli_query($GLOBALS["___mysqli_ston"],"select * from kas where id_kas=1 ");
        $kaslama = mysqli_fetch_array($tambahkas);
        $kaskredit = $kaslama['saldo'] + $_POST['kredit'] ;

        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kas SET saldo = '$kaskredit'
                                                where id_kas =1 ");
        // if($angka==$ttlqty) {

        //echo "<script type='text/javascript'>alert('Data berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
        header('location:../../media_admin.php?module='.$module);

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

        //update kas

           $saldo2 = $db->query("select sum(kredit) as kred, sum(debit) as debt from jurnal ");
           $saldo1 = $saldo2->fetch_array();
           $saldo_akhir = $saldo1['kred'] - $saldo1['debt'];



            mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kas SET saldo = '$saldo_akhir'
                                                where id_kas =1 ");

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
        { if($r['debit']>0)
        {
            $tambahkas = mysqli_query($GLOBALS["___mysqli_ston"],"select * from kas where id_kas=1 ");
            $kaslama = mysqli_fetch_array($tambahkas);
            $kasdebit = $kaslama['saldo'] + $r['debit'] ;

            mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kas SET saldo = '$kasdebit'
                                                where id_kas =1 ");
        }
        else
            {
                $kurangkas = mysqli_query($GLOBALS["___mysqli_ston"],"select * from kas where id_kas=1 ");
                $kaslama = mysqli_fetch_array($kurangkas);
                $kasdebit = $kaslama['saldo'] - $r['kredit'] ;

                mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kas SET saldo = '$kasdebit'
                                                where id_kas =1 ");

            }

            mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM jurnal WHERE id_jurnal = '$_GET[id]'");
            //echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
            header('location:../../media_admin.php?module='.$module);
            }
        else{
            echo "<script type='text/javascript'>alert('Jurnal hanya bisa dihapus orang yang sama atau pemilik apotek!');history.go(-1);</script>";
        }
    }

}
?>
