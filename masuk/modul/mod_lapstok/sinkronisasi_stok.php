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

    $tampil_barang = mysqli_query($GLOBALS["___mysqli_ston"], "
    SELECT tgl_trkasir, sum(nilai_transaksi) as tunai from trkasir 
    where id_carabayar=1 and statusbayar='LUNAS' and tgl_trkasir between '2025-02-03' and '2025-03-28' 
    GROUP by tgl_trkasir");

     while ($r1=mysqli_fetch_array($tampil_barang))
     { $transfer = $db->query("SELECT tgl_trkasir, sum(nilai_transaksi) as trans from trkasir 
        where id_carabayar=2 and statusbayar='LUNAS' and tgl_trkasir='$r1[tgl_trkasir]' ");
        $tx=$transfer->fetch_array();

        mysqli_query($GLOBALS["___mysqli_ston"],"insert into jurnal (
            tanggal,
            ket,
            petugas,
            idjenis,
            debit,
            kredit,
            carabayar,
            current
            )
        values( '$r1[tgl_trkasir]',
            'Hasil Penjualan Tunai $r1[tgl_trkasir]',
            'Team IT',
            '6',
            '0',
            '$r1[tunai]',
            'TUNAI',
            '2025-04-04 00:00:01'
            )
            ");
        
        mysqli_query($GLOBALS["___mysqli_ston"],"insert into jurnal (
            tanggal,
            ket,
            petugas,
            idjenis,
            debit,
            kredit,
            carabayar,
            current
            )
        values( '$r1[tgl_trkasir]',
            'Hasil Penjualan Transfer $r1[tgl_trkasir]',
            'Team IT',
            '6',
            '0',
            '$tx[trans]',
            'TRANSFER',
            '2025-04-04 00:00:01'
            )
            ");

     }

    
  
    }
     

        
    header('location:../../media_admin.php?module=trkasir');

    
?>
    