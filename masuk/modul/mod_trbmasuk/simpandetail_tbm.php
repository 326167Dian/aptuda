<?php 
include "../../../configurasi/koneksi.php";

$kd_trbmasuk = $_POST['kd_trbmasuk'];
$id_barang = $_POST['id_barang'];
$kd_barang = $_POST['kd_barang'];
$nmbrg_dtrbmasuk = $_POST['nmbrg_dtrbmasuk'];
$qty_dtrbmasuk = $_POST['qty_dtrbmasuk'];
$diskon = $_POST['diskon'];
$sat_dtrbmasuk = $_POST['sat_dtrbmasuk'];
$hrgsat_dtrbmasuk = $_POST['hrgsat_dtrbmasuk'];
$hrgjual_dtrbmasuk = $_POST['hrgjual_dtrbmasuk'];
$jns_obat   = $_POST['jns_obat'];

$tipe = $_POST['tipe'];
$faktordiskon = (1-($diskon/100));
$hargajadi = $hrgsat_dtrbmasuk * $faktordiskon;


if($qty_dtrbmasuk == ""){
$qty_dtrbmasuk = "1";
}else{}
if($diskon == ""){
    $diskon = "0";
}else{}

$cekstok = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_barang, stok_barang, stok_retail, konversi FROM barang 
            WHERE id_barang='$id_barang'");
$rst = mysqli_fetch_array($cekstok);
$konversi = $rst['konversi'];
if($tipe == 1) {
    $qty_masukretail = $qty_dtrbmasuk * $konversi;
}
else {
    $qty_masukretail = $qty_dtrbmasuk;
}
// else{

// }

//cek apakah barang sudah ada
$cekdetail=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trbmasuk_detail 
WHERE kd_barang='$kd_barang' AND kd_trbmasuk='$kd_trbmasuk' AND tipe='$tipe'");

$ketemucekdetail=mysqli_num_rows($cekdetail);
$rcek=mysqli_fetch_array($cekdetail);
if ($ketemucekdetail > 0){
    
    //update stok
    $cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang 
    WHERE id_barang='$id_barang'");
    $rst=mysqli_fetch_array($cekstok);
    
    $stok_barang = $rst['stok_barang'];
    $stok_retail = $rst['stok_retail'];
    $konversi = $rst['konversi'];

    if ($tipe==1){
        $id_dtrbmasuk = $rcek['id_dtrbmasuk'];
        $qtylama = $rcek['qty_dtrbmasuk'];
        $qtyretaillama = $rcek['qty_mskretail'];
        $qtyretailbaru = $qtyretaillama + ($qty_dtrbmasuk * $konversi);
        $ttlqty = $qtylama + $qty_dtrbmasuk;
        $ttlharga = $ttlqty * $hrgsat_dtrbmasuk * $faktordiskon;

        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trbmasuk_detail SET 
                                               qty_dtrbmasuk = '$ttlqty',
                                               qty_mskretail = '$qtyretailbaru',
                                                      diskon = '$diskon',
                                            hrgsat_dtrbmasuk = '$hrgsat_dtrbmasuk',
                                            hrgttl_dtrbmasuk = '$ttlharga'
                                            WHERE id_dtrbmasuk = '$id_dtrbmasuk'");
                                            
        $stok_retail_lama = $stok_retail;
        $stok_retail_baru = ($stok_retail_lama) + ($qty_dtrbmasuk * $konversi);
                    
        $stok_barang_lama = $stok_barang;
        // $stok_barang_baru = (($stok_barang_lama + $qtylama) - $ttlqty);
        $stok_barang_baru = $stok_barang_lama + $qty_dtrbmasuk;
                    
        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
                                                        stok_barang = '$stok_barang_baru',
                                                        hrgsat_barang = '$hargajadi',
                                                        stok_retail = '$stok_retail_baru',
                                                        hrgjual_barang = '$hrgjual_dtrbmasuk',
                                                        jenisobat = '$jns_obat'
                                                        WHERE id_barang = '$id_barang'");
                
        // echo $stok_barang_baru;
        $data = array(
                    'stok_barang_baru'  => $stok_barang_baru,
                    'stok_retail_baru'  => $stok_retail_baru
                );                                                
        echo json_encode($data);
                                                            
    }
    else{
        $id_dtrbmasuk = $rcek['id_dtrbmasuk'];
        $qtylama = $rcek['qty_dtrbmasuk'];
        $qtyretaillama = $rcek['qty_mskretail'];
        $qtyretailbaru = $qtyretaillama + $qty_dtrbmasuk ;
        $ttlqty = $qtylama + $qty_dtrbmasuk;
        $ttlharga = $qtyretailbaru * $hrgsat_dtrbmasuk * $faktordiskon;
        $hargajadi = $hrgsat_dtrbmasuk * $faktordiskon;

        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trbmasuk_detail SET 
                                               qty_dtrbmasuk = '$ttlqty',
                                               qty_mskretail = '$qtyretailbaru',
                                            hrgsat_dtrbmasuk = '$hrgsat_dtrbmasuk',
                                            hrgttl_dtrbmasuk = '$ttlharga'
                                            WHERE id_dtrbmasuk = '$id_dtrbmasuk'");
                                            
        $stok_retail_lama = $stok_retail;
        $stok_retail_baru = $stok_retail_lama + $qty_dtrbmasuk;
                    
        $stok_barang_lama = $stok_barang;
        $w = intval($stok_retail_baru/$konversi);
                    
        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
                                                        stok_barang = '$w',
                                                        stok_retail = '$stok_retail_baru,
                                                        hrgsat_retail = '$hargajadi,
                                                        hrgjual_retail = '$hrgjual_dtrbmasuk',
                                                        jenisobat = '$jns_obat'
                                                        WHERE id_barang = '$id_barang'");
            
        // echo $w;
        $data = array(
                    'stok_barang_baru'  => $w,
                    'stok_retail_baru'  => $stok_retail_baru
                );                                                
        echo json_encode($data);
                                                    
    }
    
// //update stok
// $cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang 
// WHERE id_barang='$id_barang'");
// $rst=mysqli_fetch_array($cekstok);

// $stok_barang = $rst['stok_barang'];
// $stok_retail = $rst['stok_retail'];
// $konversi = $rst['konversi'];
            
            // if($tipe == 1){
                // $stok_retail_lama = $stok_retail;
                // $stok_retail_baru = ($stok_retail_lama) + ($qty_dtrbmasuk * $konversi);
                    
                // $stok_barang_lama = $stok_barang;
                // // $stok_barang_baru = (($stok_barang_lama + $qtylama) - $ttlqty);
                // $stok_barang_baru = $stok_barang_lama + $qty_dtrbmasuk;
                    
                // mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
                //                                                 stok_barang = '$stok_barang_baru',
                //                                                 hrgsat_barang = '$hargajadi',
                //                                                 stok_retail = '$stok_retail_baru'
                //                                                 WHERE id_barang = '$id_barang'");
                
                // // echo $stok_barang_baru;
                // $data = array(
                //         'stok_barang_baru'  => $stok_barang_baru,
                //         'stok_retail_baru'  => $stok_retail_baru
                //     );                                                
                // echo json_encode($data);
                
            // } elseif($tipe == 2){
                // $stok_retail_lama = $stok_retail;
                // $stok_retail_baru = $stok_retail_lama + $qty_dtrbmasuk;
                    
                // $stok_barang_lama = $stok_barang;
                // $w = intval($stok_retail_baru/$konversi);
                    
                // mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
                //                                                 stok_barang = '$w',
                //                                                 stok_retail = '$stok_retail_baru,
                //                                                 hrgsat_retail = '$hargajadi
                //                                                 WHERE id_barang = '$id_barang'");
            
                // // echo $w;
                // $data = array(
                //         'stok_barang_baru'  => $w,
                //         'stok_retail_baru'  => $stok_retail_baru
                //     );                                                
                // echo json_encode($data);
                
            // }
            
// mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET stok_barang = '$stokakhir' WHERE id_barang = '$id_barang'");
									
}else{

    $faktordiskon = (1-($diskon/100));
$ttlharga = $qty_dtrbmasuk * $hrgsat_dtrbmasuk * $faktordiskon;



mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO trbmasuk_detail(kd_trbmasuk,
										id_barang,
										kd_barang,
										nmbrg_dtrbmasuk,
										qty_dtrbmasuk,
										qty_mskretail,
										diskon,
										sat_dtrbmasuk,
										hrgsat_dtrbmasuk,
										hrgttl_dtrbmasuk,
										tipe)
								  VALUES('$kd_trbmasuk',
										'$id_barang',
										'$kd_barang',
										'$nmbrg_dtrbmasuk',
										'$qty_dtrbmasuk',
										'$qty_masukretail',
										'$diskon',
										'$sat_dtrbmasuk',
										'$hrgsat_dtrbmasuk',
										'$ttlharga',
										'$tipe')");
										
//update stok
$cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang WHERE id_barang = '$id_barang'");
$rst = mysqli_fetch_array($cekstok);

// $stok_barang = $rst['stok_barang'];
// $stokakhir = $stok_barang + $qty_dtrbmasuk;

// mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET stok_barang = '$stokakhir' WHERE id_barang = '$id_barang'");

$stok_barang = $rst['stok_barang'];
$stok_retail = $rst['stok_retail'];
$konversi = $rst['konversi'];
            
            if($tipe == 1){
                $stok_retail_lama = $stok_retail;
                $stok_retail_baru = ($stok_retail_lama) + ($qty_dtrbmasuk * $konversi);
                    
                $stok_barang_lama = $stok_barang;
                // $stok_barang_baru = (($stok_barang_lama + $qtylama) - $ttlqty);
                $stok_barang_baru = $stok_barang_lama + $qty_dtrbmasuk;
                    
                mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
                                                                stok_barang = '$stok_barang_baru',
                                                                stok_retail = '$stok_retail_baru',
                                                                hrgsat_barang = '$hargajadi',
                                                                hrgjual_barang = '$hrgjual_dtrbmasuk',
                                                                jenisobat = '$jns_obat'
                                                                WHERE id_barang = '$id_barang'");
                
                // echo $stok_barang_baru;
                $data = array(
                        'stok_barang_baru'  => $stok_barang_baru,
                        'stok_retail_baru'  => $stok_retail_baru
                    );                                                
                echo json_encode($data);
                
            } elseif($tipe == 2){
                $stok_retail_lama = $stok_retail;
                $stok_retail_baru = $stok_retail_lama + $qty_dtrbmasuk;
                    
                $stok_barang_lama = $stok_barang;
                $w = intval($stok_retail_baru/$konversi);
                    
                mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
                                                                stok_barang = '$w',
                                                                stok_retail = '$stok_retail_baru',
                                                                hrgsat_retail = '$hargajadi',
                                                                hrgjual_retail = '$hrgjual_dtrbmasuk',
                                                                jenisobat = '$jns_obat'
                                                                WHERE id_barang = '$id_barang'");
            
                // echo $w;
                $data = array(
                        'stok_barang_baru'  => $w,
                        'stok_retail_baru'  => $stok_retail_baru
                    );                                                
                echo json_encode($data);
                
            }

}
				  
?>
