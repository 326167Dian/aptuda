<?php
include "../../../configurasi/koneksi.php";

$key = $_POST['id_barang'];

$ubah = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang WHERE id_barang = '$key'");


$json = [];
while($re=mysqli_fetch_array($ubah)){
    $json[] = array(
            'id_barang'     => $re['id_barang'],
			'nm_barang'     => $re['nm_barang'],
			'stok_barang'   => $re['stok_barang'],
			'stok_retail'   => $re['stok_retail'],
			'sat_barang'    => $re['sat_barang'],
			'sat_retail'    => $re['sat_retail'],
			'hrgsat_barang' => $re['hrgsat_barang'],
			'hrgsat_retail' => $re['hrgsat_retail'],
			'kd_barang'     => $re['kd_barang'],
			'jns_obat'      => $re['jenisobat'],
			'hrgjual_barang'=> $re['hrgjual_barang'],
			'hrgjual_retail'=> $re['hrgjual_retail'],
            'hna_barang'    => $re['hna'],
	);
}
echo json_encode($json);
?>