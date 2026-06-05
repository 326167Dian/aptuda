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

$tampil_barang = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang ORDER BY barang.id_barang ");
$no=1;

while ($r=mysqli_fetch_array($tampil_barang)) {

    $beli = "SELECT trbmasuk.tgl_trbmasuk,                                           
                                       SUM(trbmasuk_detail.qty_mskretail) AS totalbeli                                            
                                       FROM trbmasuk_detail join trbmasuk 
                                       on (trbmasuk_detail.kd_trbmasuk=trbmasuk.kd_trbmasuk)
                                       WHERE id_barang =$r[id_barang]";
    $buy = mysqli_query($GLOBALS["___mysqli_ston"], $beli);
    $buy2 = mysqli_fetch_array($buy);
    $jual = "SELECT trkasir.tgl_trkasir,                                
                                        sum(trkasir_detail.qty_kasirretail) AS totaljual
                                        FROM trkasir_detail join trkasir 
                                        on (trkasir_detail.kd_trkasir=trkasir.kd_trkasir)
                                        WHERE id_barang =$r[id_barang]";
    $jokul = mysqli_query($GLOBALS["___mysqli_ston"], $jual);
    $sell = mysqli_fetch_array($jokul);
    $selisih = $buy2['totalbeli'] - $sell['totaljual'];
    $stokretail = $r['stok_retail'];
    $konversi = $r['konversi'];
    $stokbarang = intval ($selisih/$konversi);


    if($stokretail != $selisih)
    {
        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
                                                stok_retail = '$selisih',
                                                stok_barang = '$stokbarang'
                                                 where id_barang =$r[id_barang] and ($selisih>=0)");
    }
    else{}

}
        header('location:../../media_admin.php?module=trkasir');
    
}
?>
