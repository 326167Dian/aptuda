<?php
include "../../../configurasi/koneksi.php";

$key = $_POST['nm_barang'];

$ubah = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang WHERE nm_barang = '$key'");


$json = [];
while($re=mysqli_fetch_array($ubah)){
$json[] = array(
            'id_barang'         => $re['id_barang'],
			'nm_barang'         => $re['nm_barang'],
			'stok_barang'       => $re['stok_barang'],
			'stok_retail'       => $re['stok_retail'],
			'sat_barang'        => $re['sat_barang'],
			'sat_retail'        => $re['sat_retail'],
			'indikasi'          => $re['indikasi'],
			'hrgjual_barang'    => $re['hrgjual_barang'],
			'hrgjual_retail'    => $re['hrgjual_retail'],
			'kd_barang'         => $re['kd_barang']
			);
}
echo json_encode($json);
?>