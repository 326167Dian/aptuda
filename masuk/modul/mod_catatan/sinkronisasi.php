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


       $saldo2 = $db->query("select sum(kredit) as kred, sum(debit) as debt from jurnal ");
       $saldo1 = $saldo2->fetch_array();
       $saldo_akhir = $saldo1['kred'] - $saldo1['debt'];
//       $saldo = format_rupiah($saldo_akhir);


            mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kas SET saldo = '$saldo_akhir'
                                                where id_kas =1 ");



    header('location:../../media_admin.php?module=catatan');

}
?>
