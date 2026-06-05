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

// Input admin
if ($module=='shiftkerja' AND $act=='input_shiftkerja'){

    $tglharini = date('Y-m-d');

$cekganda=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM waktukerja WHERE tanggal ='$tglharini' 
and status='ON'");

$ada=mysqli_num_rows($cekganda);
if ($ada > 0){
echo "<script type='text/javascript'>alert('Kasir sudah dibuka!');history.go(-1);</script>";
}
else{

    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO waktukerja (
                                   
                                    petugasbuka,
                                    tanggal,
                                    waktubuka,
                                    shift,
                                    saldoawal,
                                    status)
                            VALUES(
                                    '$_POST[petugasbuka]',
                                    '$_POST[tanggal]',
                                    '$_POST[waktubuka]',
                                    '$_POST[shift]',
                                    '$_POST[saldoawal]',                                                                        
                                    '$_POST[status]'                                                                        
                                    )");
										
										
	//echo "<script type='text/javascript'>alert('Data berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
	header('location:../../media_admin.php?module='.$module);

}
}

//updata waktu kerja
 elseif ($module=='shiftkerja' AND $act=='update_waktukerja'){

     mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE waktukerja 
                                SET petugastutup = '$_POST[petugastutup]', 
                                    waktututup = '$_POST[waktututup]',
                                    status = '$_POST[status]',
                                    saldoakhir = '$_POST[saldoakhir]'
								WHERE shift = '$_POST[shift]' and tanggal='$_POST[tanggal]' ");

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
values( '$_POST[tanggal]',
    'Tutup Kasir ',
    '$_POST[petugastutup]',
    '6',
    '0',
    '$_POST[saldoakhir]',
    'TUNAI',
    '$waktu'
    )
    ");

$transfer = $db->query("select sum(nilai_transaksi) as tf from trkasir where tgl_trkasir='2025-04-04' and statusbayar ='LUNAS' and id_carabayar=2; ");   
$tfr = $transfer->fetch_array();
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
values( '$_POST[tanggal]',
    'Tutup Kasir Shift $_POST[shift]',
    '$_POST[petugastutup]',
    '6',
    '0',
    '$tfr[tf]',
    'TRANSFER',
    '$waktu'
    )
    ");
	//echo "<script type='text/javascript'>alert('Data berhasil diubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	
    header('location:../../media_admin.php?module='.$module);
 }
 //updata waktu kerja
 elseif ($module=='shiftkerja' AND $act=='update_waktukerjakoreksi'){

     mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE waktukerja 
                                SET petugasbuka = '$_POST[petugasbuka]', 
                                    petugastutup = '$_POST[petugastutup]', 
                                    shift  = '$_POST[shift]',
                                    tanggal = '$_POST[tanggal]',
                                    waktubuka = '$_POST[waktubuka]',
                                    waktututup = '$_POST[waktututup]',
                                    saldoawal = '$_POST[saldoawal]',
                                    saldoakhir = '$_POST[saldoakhir]',
                                    status = '$_POST[status]'                                   
								WHERE id_shift = '$_POST[id]'");

	//echo "<script type='text/javascript'>alert('Data berhasil diubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	header('location:../../media_admin.php?module='.$module);
	
}
//Hapus Proyek
elseif ($module=='shiftkerja' AND $act=='hapus'){

  mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM waktukerja WHERE id_shift = '$_GET[id]'");
  //echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
  header('location:../../media_admin.php?module='.$module);
}

}
?>
