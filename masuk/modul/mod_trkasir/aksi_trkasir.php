<?php
// error_reporting(0);
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

$module= "trkasir";
$stt_aksi=$_POST['stt_aksi'];
if($stt_aksi == "input_trkasir" || $stt_aksi == "ubah_trkasir"){
$act=$stt_aksi;
}else{
$act=$_GET['act'];
}
$id_carabayar = $_POST['id_carabayar'];

$tgl_awal = date('Y-m-d');
    $jth_tempo=date('Y-m-d', strtotime('-30 days', strtotime( $tgl_awal)));
    
    // $tempo =  mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(id_trkasir) as kena WHERE nm_pelanggan = '$_POST[nm_pelanggan]' AND tgl_trkasir < $jth_tempo and statusbayar='PROSES' ");
    // $ttt = mysqli_fetch_array($tempo);
    
    // if ($ttt['kena']>0)
    // {echo "<script type='text/javascript'>alert('Mohon Selesaikan Pembayaran yang Tertunda !');window.location='../../media_admin.php?module=".$module."'</script>";}
    // else{

$date_now = date("Y-m-d", time());
// $date_15 = date('Y-m-d', strtotime('-15 days', time()));
$date_15 = date('Y-m-d', strtotime('-5 days', time()));

// Input admin
if ($module=='trkasir' AND $act=='input_trkasir'){

    // $caribynama = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir WHERE nm_pelanggan = '$_POST[nm_pelanggan]' AND id_carabayar > 1
    //     ORDER BY id_trkasir ASC LIMIT 1");
    $caribynama = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(id_trkasir) as jml FROM trkasir 
                    WHERE nm_pelanggan = '$_POST[nm_pelanggan]' AND tgl_trkasir <= '$date_15' AND statusbayar = 'PROSES'");
    
    $row = mysqli_num_rows($caribynama);
    $temu = mysqli_fetch_array($caribynama);
     
    if(($temu['jml'] > 0) AND ($_POST['id_carabayar'] > 1)){
        // $date_trx = $temu['tgl_trkasir'];
        // // $status_bayar = $temu['statusbayar'];
        // $status_bayar = $_POST['status'];
        // $date_before = date('Y-m-d', strtotime('-30 days', time()));
        
        // $tgl    = $temu['tgl_trkasir'];
        // $awal   = date_create($tgl);
        // $akhir  = date_create($date_now); // waktu sekarang
        // $diff   = date_diff( $awal, $akhir );
        
        // if(($date_now - $r['tgl_trkasir'])
        // echo "Nama = ".$r['nm_pelanggan']." - Tanggal = ".$r['tgl_trkasir']." - Selisih Now = ".$diff->days."<br>";
        
        // if(($date_before >= $date_trx) && ($status_bayar != 'LUNAS') && ($_POST['id_carabayar']!='1')){
        // if(($_POST['id_carabayar'] != 1)){
            $data = array('result'=>'failed');
            echo json_encode($data);
            // echo "<script type='text/javascript'>alert('Mohon Selesaikan Pembayaran yang Tertunda !');window.location='../../media_admin.php?module=".$module."'</script>";
        // } else {
        //     // $data = array('result'=>'success');
        //     // echo json_encode($data);
        
        // 	if ($id_carabayar > 1){
        // 		$totaltransaksi = $_POST['dp_bayar'];
        // 		$nilaitransaksi = $_POST['ttl_trkasir'];
        // 		$status = 'PROSES';
        // 	}
        
        // 	else { $totaltransaksi = $_POST['ttl_trkasir'];
        // 		$nilaitransaksi = $totaltransaksi;
        // 		$status = 'LUNAS';
        // 	}
        
        //     mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO trkasir(
        // 										kd_trkasir,	
        // 										petugas,																									
        // 										tgl_trkasir,																			
        // 										nm_pelanggan,										
        // 										tlp_pelanggan,
        // 										alamat_pelanggan,
        // 										nilai_transaksi,										
        // 										ttl_trkasir,
        // 										diskon,
        // 										diskon2,
        // 										dp_bayar,
        // 										sisa_bayar,
        // 										ket_trkasir,
        // 										id_carabayar,
        // 										statusbayar
        // 										)
        // 								 VALUES('$_POST[kd_trkasir]',
        // 								 		'$_POST[petugas]',								 	
        // 										'$_POST[tgl_trkasir]',										
        // 										'$_POST[nm_pelanggan]',
        // 										'$_POST[tlp_pelanggan]',
        // 										'$_POST[alamat_pelanggan]',
        // 										'$nilaitransaksi',
        // 										'$totaltransaksi',
        // 										'$_POST[diskon]',
        // 										'$_POST[diskon2]',
        // 										'$_POST[dp_bayar]',
        // 										'$_POST[sisa_bayar]',
        // 										'$_POST[ket_trkasir]',
        // 										'$_POST[id_carabayar]',
        // 										'$status'
        										
        // 										)");
        // 	if($id_carabayar==3)
        // 	{ mysqli_query($GLOBALS["___mysqli_ston"],"INSERT INTO trkasir_tempo(
        // 										kd_trkasir,
        // 										angsuran,
        // 										petugas
        // 										)
        // 									values('$_POST[kd_trkasir]',
        // 											'$_POST[dp_bayar]',
        // 											'$_POST[petugas]'
        // 											)
        // 											");
        // 	}
        
        
        // 	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kdtk SET stt_kdtk = 'OFF' WHERE id_admin = '$_SESSION[idadmin]'AND kd_trkasir = '$_POST[kd_trkasir]'");
        // // 	echo "<script type='text/javascript'>alert('Transkasi berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
        	
        // 	$data = array('result'=>'success');
        //     echo json_encode($data);
        // }
    } else {
        // $data = array('result'=>'success');
        // echo json_encode($data);
        
        	if ($id_carabayar > 1){
        		$totaltransaksi = $_POST['dp_bayar'];
        		$nilaitransaksi = $_POST['ttl_trkasir'];
        		$status = 'PROSES';
        	}
        
        	else { $totaltransaksi = $_POST['ttl_trkasir'];
        		$nilaitransaksi = $totaltransaksi;
        		$status = 'LUNAS';
        	}
        
            mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO trkasir(
        										kd_trkasir,	
        										petugas,																									
        										tgl_trkasir,																			
        										nm_pelanggan,										
        										tlp_pelanggan,
        										alamat_pelanggan,
        										nilai_transaksi,										
        										ttl_trkasir,
        										diskon,
        										diskon2,
        										dp_bayar,
        										sisa_bayar,
        										ket_trkasir,
        										id_carabayar,
        										statusbayar
        										)
        								 VALUES('$_POST[kd_trkasir]',
        								 		'$_POST[petugas]',								 	
        										'$_POST[tgl_trkasir]',										
        										'$_POST[nm_pelanggan]',
        										'$_POST[tlp_pelanggan]',
        										'$_POST[alamat_pelanggan]',
        										'$nilaitransaksi',
        										'$totaltransaksi',
        										'$_POST[diskon]',
        										'$_POST[diskon2]',
        										'$_POST[dp_bayar]',
        										'$_POST[sisa_bayar]',
        										'$_POST[ket_trkasir]',
        										'$_POST[id_carabayar]',
        										'$status'
        										
        										)");
        	if($id_carabayar==3)
        	{ mysqli_query($GLOBALS["___mysqli_ston"],"INSERT INTO trkasir_tempo(
        										kd_trkasir,
        										angsuran,
        										petugas
        										)
        									values('$_POST[kd_trkasir]',
        											'$_POST[dp_bayar]',
        											'$_POST[petugas]'
        											)
        											");
        	}
        
        
        	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kdtk SET stt_kdtk = 'OFF' WHERE id_admin = '$_SESSION[idadmin]'AND kd_trkasir = '$_POST[kd_trkasir]'");
        // 	echo "<script type='text/javascript'>alert('Transkasi berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
        $datas = array('result'=>'success');
        echo json_encode($datas);
    }
}

 //updata trkasir
 elseif ($module=='trkasir' AND $act=='ubah_trkasir'){
        // $data = array(
        //     'carabayar'=>$_POST['id_carabayar'],
        //     'statusbayar'=>$_POST['statusbayar']
        // );
        // echo json_encode($data);
        // die();
     $nilaitransaksi = $_POST['ttl_trkasir'];

    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trkasir SET tgl_trkasir = '$_POST[tgl_trkasir]',
									petugas = '$_POST[petugas]',
									nm_pelanggan = '$_POST[nm_pelanggan]',									
									tlp_pelanggan = '$_POST[tlp_pelanggan]',
									alamat_pelanggan = '$_POST[alamat_pelanggan]',
									nilai_transaksi =  '$nilaitransaksi',
									ttl_trkasir = '$totaltransaksi',
									diskon = '$_POST[diskon]',
									diskon2 = '$_POST[diskon2]',
									dp_bayar = '$_POST[dp_bayar]',
									sisa_bayar = '$_POST[sisa_bayar]',
									ket_trkasir = '$_POST[ket_trkasir]',
									id_carabayar = '$_POST[id_carabayar]',
									statusbayar = '$_POST[statusbayar]'
									WHERE id_trkasir = '$_POST[id_trkasir]'");
// 	 if($id_carabayar=3)
	 if($_POST['id_carabayar']=='3')
	 {

	 	mysqli_query($GLOBALS["___mysqli_ston"],"INSERT INTO trkasir_tempo(
										kd_trkasir,
										angsuran,
										petugas
										)
									values('$_POST[kd_trkasir]',
											'$_POST[dp_bayar]',
											'$_POST[petugas]')											
											");
		 //tarik data cicilan sebelumnya
	 	$cicilnew=$_POST['dp_bayar'];
	 	$pembayaran = mysqli_query($GLOBALS["___mysqli_ston"],"select sum(hrgttl_dtrkasir) as totbayar from trkasir_detail where kd_trkasir='$_POST[kd_trkasir]'");
	 	$tbayar = mysqli_fetch_array($pembayaran);
	 	$tampilbayar = $tbayar['totbayar'];

		 $cicil= mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT sum(angsuran) as totalcicil FROM trkasir_tempo WHERE kd_trkasir='$_POST[kd_trkasir]'");
		 $tc= mysqli_fetch_array($cicil);
		 $tc_new = $tc['totalcicil'];
		 if ($tc_new >= $tampilbayar)
		 {
		  //   $angsuran=1;
		     $status_bayar='LUNAS';
		 }else{
		 	// $angsuran=3;
		 	$status_bayar='PROSES';
		 }


		//update total pembayaran
// 		 mysqli_query($GLOBALS["___mysqli_ston"],"update 
// 					trkasir set 
// 					ttl_trkasir='$tc_new',
                    
				// 	id_carabayar ='$angsuran',
// 					statusbayar ='$angsuran'
// 					where kd_trkasir='$_POST[kd_trkasir]'");
		 mysqli_query($GLOBALS["___mysqli_ston"],"update 
					trkasir set 
					ttl_trkasir='$tc_new',
					statusbayar = '$status_bayar'
					where kd_trkasir='$_POST[kd_trkasir]'");
	 }
										
										
	//echo "<script type='text/javascript'>alert('Transkasi berhasil Ubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	$data = array('result'=>'success');
            echo json_encode($data);
 
}
//Hapus Proyek
elseif ($module=='trkasir' AND $act=='hapus'){

  //update bagian stok dulu
  //ambil data induk
	$ambildatainduk=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_trkasir, kd_trkasir FROM trkasir 
	WHERE id_trkasir='$_GET[id]'");
	$r1=mysqli_fetch_array($ambildatainduk);
	$kd_trkasir = $r1['kd_trkasir'];
	
	//loop data detail
	$ambildatadetail=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_dtrkasir, kd_trkasir, id_barang, qty_dtrkasir, tipe, sum(qty_kasirretail) as qtytr_retail FROM trkasir_detail WHERE kd_trkasir='$kd_trkasir'");
	while ($r=mysqli_fetch_array($ambildatadetail)){
	
	$id_dtrkasir = $r['id_dtrkasir'];
	$id_barang = $r['id_barang'];
	$qty_dtrkasir = $r['qty_dtrkasir'];
	$qtytr_retail = $r['qtytr_retail'];

	//update stok
	
		$cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang 
		WHERE id_barang='$id_barang'");
		$rst=mysqli_fetch_array($cekstok);
		$konversi = $rst['konversi'];
		$stok_retail = $rst['stok_retail'];

		$stok_retail_akhir = $stok_retail + $qtytr_retail;
		$stok_grosir = intval ($stok_retail_akhir/$konversi);
		mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
							stok_barang = '$stok_grosir',
							stok_retail = '$stok_retail_akhir' 
							WHERE id_barang = '$id_barang'");
	
	
	mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM trkasir_detail WHERE id_dtrkasir = '$id_dtrkasir'");
	mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM trkasir_tempo WHERE kd_trkasir = '$r[kd_trkasir]'");
	
	}

  mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM trkasir WHERE id_trkasir = '$_GET[id]'");
  
echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
}
elseif ($module=='trkasir' AND $act=='tes'){
    $nama = $_GET['nama'];
    // $cek = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir WHERE nm_pelanggan = '$nama'
    //                     AND id_carabayar > 1
    //                     ORDER BY id_trkasir DESC;");
    
    $caribynama = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(id_trkasir) as jml FROM trkasir WHERE nm_pelanggan = '$nama' AND tgl_trkasir <= '$date_15' AND statusbayar = 'PROSES'");
    
    $row = mysqli_num_rows($caribynama);
    $temu = mysqli_fetch_array($caribynama);
    
    $date_now = date("Y-m-d", time());
    echo $temu['jml'];
    // while($r = mysqli_fetch_array($temu)){
    //     $tgl = $r['tgl_trkasir'];
    //     $awal  = date_create($tgl);
    //     $akhir = date_create($date_now); // waktu sekarang
    //     $diff  = date_diff( $awal, $akhir );
        
    //     // if(($date_now - $r['tgl_trkasir'])
    //     echo "Nama = ".$r['nm_pelanggan']." - Tanggal = ".$r['tgl_trkasir']." - Selisih Now = ".$diff->days."<br>";
    // }                    
}
    // }
}
?>
