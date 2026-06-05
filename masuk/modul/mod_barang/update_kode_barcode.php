<?php
include "../../../configurasi/koneksi.php";
$act = $_GET['act'];

if($act == 'update-row') {
    $kd_barcode = $_POST['kd_barang'];
    $id_barang = $_POST['id_barang'];
    $hrgsat_barang = $_POST['hrgsat_barang'];
    $hrgjual_barang = $_POST['hrgjual_barang'];
    $sat_barang = $_POST['sat_barang'];
    $sat_retail = $_POST['sat_retail'];
    $konversi = $_POST['konversi'];
    // $sesuai = $_POST['sesuai'];
    
    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET kd_barang = '$kd_barcode', cek = 1, 
        hrgsat_barang = '$hrgsat_barang', 
        hrgjual_barang = '$hrgjual_barang', 
        sat_barang = '$sat_barang', 
        sat_retail = '$sat_retail', 
        konversi = '$konversi' 
    WHERE id_barang = $id_barang");
    
    // echo 'ID = '.$id_barang.'<br>Kode Barang = '.$kd_barcode.'<br>Harga Beli = '.$hrgsat_barang.'<br>Harga Jual = '.$hrgjual_barang.'<br>Satuan Barang = '.$sat_barang.'<br>Satuan Retail = '.$sat_retail.'<br>Konversi = '.$konversi;
} else
if ($act == 'update-all'){
    $kd_barcode = $_POST['kd_barcode'];
    $id_barang = $_POST['id_barang'];
    $sesuai = $_POST['sesuai'];
    $sat_barang = $_POST['sat_barang'];
    $sat_retail = $_POST['sat_retail'];
    $konversi = $_POST['konversi'];
    $hrgjual_barang = $_POST['hrgjual_barang'];
    $hrgsat_barang = $_POST['hrgsat_barang'];
    $pilih = $_POST['pilihan'];
    
    $count = count($pilih);
    
    for($i = 0; $i < $count; $i++){
        if($sesuai[$i] > 0){
            mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET kd_barang = '$kd_barcode[$i]', 
                cek = '$sesuai[$i]', 
                sat_barang = '$sat_barang[$i]', 
                sat_retail = '$sat_retail[$i]', 
                hrgjual_barang = '$hrgjual_barang[$i]', 
                hrgsat_barang = '$hrgsat_barang[$i]',
                konversi = '$konversi[$i]' 
            WHERE id_barang = $id_barang[$i]");
            // echo 'Kode Barang = '.$kd_barcode[$i].' <br>Cek = '.$sesuai[$i].'<br>Satuan =  '.$sat_barang[$i].'<br>Satuan Retail = '.$sat_retail[$i].'<br>Konversi = '.$konversi[$i].'<br>ID Barang = '.$id_barang[$i].'<br>Checkbox = '.$pilih[$i].'<br>Harga Beli = '.$hrgsat_barang[$i].'<br>Harga Jual = '.$hrgjual_barang[$i].'<br>==========================<br>';
        }
    }

    header('location:../../media_admin.php?module=barang&act=barcodebarang');
}
?>