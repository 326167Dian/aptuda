<?php 
include "../../../configurasi/koneksi.php";

$kd_trbmasuk = $_POST['kd_trbmasuk'];
$id_barang = $_POST['id_barang'];
$kd_barang = $_POST['kd_barang'];
$nmbrg_dtrbmasuk = $_POST['nmbrg_dtrbmasuk'];
$qty_dtrbmasuk = $_POST['qty_dtrbmasuk'];
$sat_dtrbmasuk = $_POST['sat_dtrbmasuk'];
$hnasat_dtrbmasuk = $_POST['hnasat_dtrbmasuk'];
$diskon = $_POST['diskon'];
$faktordiskon = 1- ($diskon/100);
$hrgsat_dtrbmasuk = $hnasat_dtrbmasuk * 1.11 ;
$hrgjadi = $hrgsat_dtrbmasuk * $faktordiskon;

$hrgjual_dtrbmasuk = $_POST['hrgjual_dtrbmasuk'];
$jns_obat = $_POST['jns_obat'];

if($qty_dtrbmasuk == ""){
$qty_dtrbmasuk = "1";
}else{}
if($diskon == ""){
    $diskon = "0";
}else{}

//cek apakah barang sudah ada
$cekdetail=mysqli_query($GLOBALS["___mysqli_ston"],
    "SELECT id_barang, kd_barang, kd_trbmasuk, id_dtrbmasuk, qty_dtrbmasuk,qty_mskretail 
FROM trbmasuk_detail 
WHERE kd_barang='$kd_barang' AND kd_trbmasuk='$kd_trbmasuk'");

$ketemucekdetail=mysqli_num_rows($cekdetail);
$rcek=mysqli_fetch_array($cekdetail);

$konversi1 = mysqli_query($GLOBALS["___mysqli_ston"],"
            select * from barang where kd_barang='$rcek[kd_barang]' ");
$konversi2 = mysqli_fetch_array($konversi1);
$konversi = $konversi2['konversi'];


if ($ketemucekdetail > 0){

$id_dtrbmasuk = $rcek['id_dtrbmasuk'];
$qtylama = $rcek['qty_dtrbmasuk'];
$qtyretaillama = $rcek['qty_mskretail'];
$qtyretailbaru = $qtyretaillama + ($qty_dtrbmasuk*$konversi);
$ttlqty = $qtylama + $qty_dtrbmasuk;
$ttlharga = $ttlqty * $hnasat_dtrbmasuk;

mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trbmasuk_detail SET 
                                        qty_dtrbmasuk = '$ttlqty',
                                        qty_mskretail = '$qtyretailbaru',
										hnasat_dtrbmasuk = '$hnasat_dtrbmasuk',
										diskon = '$diskon',
										hrgsat_dtrbmasuk = '$hrgsat_dtrbmasuk',										
										hrgttl_dtrbmasuk = '$ttlharga'
										WHERE id_dtrbmasuk = '$id_dtrbmasuk'");
										
//update stok
$cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_barang, stok_barang,stok_retail,konversi FROM barang 
WHERE id_barang='$id_barang'");
$rst=mysqli_fetch_array($cekstok);

$stok_barang = $rst['stok_barang'];
$konversi =  $rst['konversi'];
$stok_retail = $rst['stok_retail'];
$stok_retailbaru = $stok_retail + ($qty_dtrbmasuk * $konversi);
$stokakhir = (($stok_barang - $qtylama) + $ttlqty);

mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
                                          stok_barang = '$stokakhir', 
                                          stok_retail = '$stok_retailbaru', 
                                          hna = '$hnasat_dtrbmasuk',
                                          hrgsat_barang = '$hrgjadi',
                                          hrgjual_barang = '$hrgjual_dtrbmasuk',
                                          jenisobat = '$jns_obat'
                                          WHERE id_barang = '$id_barang'");
                                          
		echo json_encode($stokakhir);							
}else{
    $cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_barang, konversi FROM barang 
WHERE id_barang='$id_barang'");
    $rst=mysqli_fetch_array($cekstok);

$faktordiskon = (1-($diskon/100));
$ttlharga = $qty_dtrbmasuk * $hnasat_dtrbmasuk * $faktordiskon ;
$qty_mskretail = $qty_dtrbmasuk * $rst['konversi'];


mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO trbmasuk_detail(kd_trbmasuk,
										id_barang,
										kd_barang,
										nmbrg_dtrbmasuk,
										qty_dtrbmasuk,
										qty_mskretail,
										sat_dtrbmasuk,
										hnasat_dtrbmasuk,
										diskon,
										hrgsat_dtrbmasuk,										
										hrgttl_dtrbmasuk)
								  VALUES('$kd_trbmasuk',
										'$id_barang',
										'$kd_barang',
										'$nmbrg_dtrbmasuk',
										'$qty_dtrbmasuk',
										'$qty_mskretail',
										'$sat_dtrbmasuk',
										'$hnasat_dtrbmasuk',
										'$diskon',
										'$hrgsat_dtrbmasuk',
										'$ttlharga')");
										
//update stok,hna,hrgbrg+ppn
$cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_barang, stok_barang,stok_retail,konversi FROM barang 
WHERE id_barang='$id_barang'");
$rst=mysqli_fetch_array($cekstok);

$stok_barang = $rst['stok_barang'];
    $konversi =  $rst['konversi'];
    $stok_retail = $rst['stok_retail'];
    $stok_retailbaru = $stok_retail + ($qty_dtrbmasuk * $konversi);
$stokakhir = $stok_barang + $qty_dtrbmasuk;

mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
                                                stok_barang = '$stokakhir',
                                                stok_retail = '$stok_retailbaru', 
                                                hna = '$hnasat_dtrbmasuk',
                                                hrgsat_barang = '$hrgjadi',
                                                hrgjual_barang = '$hrgjual_dtrbmasuk',
                                                jenisobat = '$jns_obat'
                                                WHERE id_barang = '$id_barang'");
    echo json_encode($stokakhir);
}
				  
?>
