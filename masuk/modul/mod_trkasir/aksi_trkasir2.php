<?php
error_reporting(0);
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

// Input admin
if ($module=='trkasir' AND $act=='input_trkasir'){

	if ($id_carabayar > 1){
		$totaltransaksi = $_POST['dp_bayar'];
		$nilaitransaksi = $_POST['ttl_trkasir'];
	}

	else { $totaltransaksi = $_POST['ttl_trkasir'];
		$nilaitransaksi = $totaltransaksi;
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
										'$_POST[statusbayar]'
										
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
	//echo "<script type='text/javascript'>alert('Transkasi berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
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
									statusbayar = '$_POST[statusbayar]'
									WHERE id_trkasir = '$_POST[id_trkasir]'");
	 if($id_carabayar=3)
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
		     $angsuran='LUNAS';
		 }else{
		 	// $angsuran=3;
		 	$angsuran='PROSES';
		 }


		//update total pembayaran
		 mysqli_query($GLOBALS["___mysqli_ston"],"update 
					trkasir set 
					ttl_trkasir='$tc_new',
					statusbayar ='$angsuran'
					where kd_trkasir='$_POST[kd_trkasir]'");
// 		 mysqli_query($GLOBALS["___mysqli_ston"],"update 
// 					trkasir set 
// 					ttl_trkasir='$tc_new',
// 					id_carabayar ='$angsuran'
// 					where kd_trkasir='$_POST[kd_trkasir]'");
	 }
										
										
	echo "<script type='text/javascript'>alert('Transkasi berhasil Ubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	
 
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

}
?>
