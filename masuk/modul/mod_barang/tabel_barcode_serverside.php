<?php
include_once '../../../configurasi/koneksi.php';

if ($_GET['action'] == "table_data") {

    $columns = array(
        0 => 'id_barang',
        1 => 'nm_barang',
        2 => 'sat_barang',
        3 => 'sat_retail',
        4 => 'konversi',
        5 => 'kd_barang',
        6 => 'hrgsat_barang',
        7 => 'hrgjual_barang',
        8 => 'id_barang'
    );

    $querycount = $db->query("SELECT count(id_barang) as jumlah FROM barang WHERE cek>0");
    $datacount = $querycount->fetch_array();

    $totalData = $datacount['jumlah'];

    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        $query = $db->query("SELECT *
            FROM barang WHERE cek > 0 
            ORDER BY $order $dir LIMIT $limit OFFSET $start");
    } else {
        $search = $_POST['search']['value'];
        $query = $db->query("SELECT *
            FROM barang WHERE cek > 0 AND (kd_barang LIKE '%$search%' 
                        OR nm_barang LIKE '%$search%'
                        OR stok_barang LIKE '%$search%'
                        OR sat_barang LIKE '%$search%'
                        OR sat_retail LIKE '%$search%'
                        OR hrgsat_barang LIKE '%$search%'
                        OR hrgjual_barang LIKE '%$search%'
                        OR konversi LIKE '%$search%')
            ORDER BY $order $dir LIMIT $limit OFFSET $start");

        $querycount = $db->query("SELECT count(id_barang) as jumlah 
            FROM barang WHERE cek > 0 AND (kd_barang LIKE '%$search%' 
                        OR nm_barang LIKE '%$search%'
                        OR stok_barang LIKE '%$search%'
                        OR sat_barang LIKE '%$search%'
                        OR sat_retail LIKE '%$search%'
                        OR hrgsat_barang LIKE '%$search%'
                        OR hrgjual_barang LIKE '%$search%'
                        OR konversi LIKE '%$search%')");

        $datacount = $querycount->fetch_array();
        $totalFiltered = $datacount['jumlah'];
    }

    $data = array();
    if (!empty($query)) {
        $no = $start + 1;
        while ($value = $query->fetch_array()) {
            // $nestedData['no'] = '<input type="checkbox" name="check[]" value="' . $value['kd_barang'] . '"> '.$no;
            $nestedData['no'] = $no;
            $nestedData['kd_barang'] = $value['kd_barang'];
            $nestedData['nm_barang'] = $value['nm_barang'];
            $nestedData['stok_barang'] = $value['stok_barang'];
            $nestedData['sat_barang'] = $value['sat_barang'];
            $nestedData['sat_retail'] = $value['sat_retail'];
            $nestedData['konversi'] = $value['konversi'];
            $nestedData['jenisobat'] = $value['jenisobat'];
            $nestedData['hrgsat_barang'] = $value['hrgsat_barang'];
            $nestedData['hrgjual_barang'] = $value['hrgjual_barang'];
            $nestedData['cek'] = $value['cek'];
            $nestedData['indikasi'] = $value['indikasi'];
            $nestedData['aksi'] = $value['id_barang'];;
            $data[] = $nestedData;
            $no++;
        }
    }

    $json_data = [
        "draw"            => intval($_POST['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $data
    ];

    echo json_encode($json_data);
}
