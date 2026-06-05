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
if ($module=='supplier' AND $act=='input_supplier'){

$cekganda=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM supplier WHERE nm_supplier='$_POST[nm_supplier]'AND tlp_supplier='$_POST[tlp_supplier]'");
$ada=mysqli_num_rows($cekganda);
if ($ada > 0){
echo "<script type='text/javascript'>alert('Nama Supplier dengan nomor telepon ini sudah ada!');history.go(-1);</script>";
}else{

    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO supplier(nm_supplier, tlp_supplier, alamat_supplier, ket_supplier)
								 VALUES('$_POST[nm_supplier]','$_POST[tlp_supplier]','$_POST[alamat_supplier]','$_POST[ket_supplier]')");
										
										
	//echo "<script type='text/javascript'>alert('Data berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
	header('location:../../media_admin.php?module='.$module);

}
}
 //updata supplier
 elseif ($module=='supplier' AND $act=='update_supplier'){
 
     mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE supplier SET nm_supplier = '$_POST[nm_supplier]',
									tlp_supplier = '$_POST[tlp_supplier]',
									alamat_supplier = '$_POST[alamat_supplier]',
									ket_supplier = '$_POST[ket_supplier]'
									WHERE id_supplier = '$_POST[id]'");
									
	//echo "<script type='text/javascript'>alert('Data berhasil diubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	header('location:../../media_admin.php?module='.$module);
	
}
//Hapus Proyek
elseif ($module=='supplier' AND $act=='hapus'){

  mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM supplier WHERE id_supplier = '$_GET[id]'");
  //echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
  header('location:../../media_admin.php?module='.$module);
}

}
?>
