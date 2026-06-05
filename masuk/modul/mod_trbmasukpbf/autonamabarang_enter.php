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
			'jenisobat'         => $re['jenisobat'],
			'sat_barang'        => $re['sat_barang'],
			'indikasi'          => $re['indikasi'],
			'hrgjual_barang'    => $re['hrgjual_barang'],
			'hrgsat_barang'     => $re['hrgsat_barang'],
			'kd_barang'         => $re['kd_barang'],
			'hna'               => $re['hna'],
			'hrgjual_barang'    => $re['hrgjual_barang'],
			'jns_obat'          => $re['jenisobat']
			);
}
echo json_encode($json);
?>