<?php
include_once '../../../configurasi/koneksi.php';

if ($_GET['action'] == "table_data") {

    $columns = array(
        0 => 'id_barang',
        1 => 'kd_barang',
        2 => 'nm_barang',
        3 => 'stok_barang',
        4 => 'sat_barang',
        5 => 'stok_retail',
        6 => 'sat_retail',
        7 => 'konversi',
        8 => 'jenisobat',
        9 => 'hrgsat_barang',
        10 => 'hrgjual_barang',
        11 => 'hrgjual_retail',
        12 => 'indikasi',
        13 => 'id_barang'
    );

    $querycount = $db->query("SELECT count(id_barang) as jumlah FROM barang");
    $datacount = $querycount->fetch_array();

    $totalData = $datacount['jumlah'];

    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        $query = $db->query("SELECT *
            FROM barang ORDER BY $order $dir LIMIT $limit OFFSET $start");
    } else {
        $search = $_POST['search']['value'];
        $query = $db->query("SELECT *
            FROM barang WHERE kd_barang LIKE '%$search%' 
                        OR nm_barang LIKE '%$search%'
                        OR stok_barang LIKE '%$search%'
                        OR sat_barang LIKE '%$search%'
                        OR stok_retail LIKE '%$search%'
                        OR sat_retail LIKE '%$search%'
                        OR konversi LIKE '%$search%'
                        OR jenisobat LIKE '%$search%'
                        OR hrgsat_barang LIKE '%$search%'
                        OR hrgjual_barang LIKE '%$search%'
                        OR hrgjual_retail LIKE '%$search%'
                        OR indikasi LIKE '%$search%' 
            ORDER BY $order $dir LIMIT $limit OFFSET $start");

        $querycount = $db->query("SELECT count(id_barang) as jumlah 
            FROM barang WHERE kd_barang LIKE '%$search%' 
                        OR nm_barang LIKE '%$search%'
                        OR stok_barang LIKE '%$search%'
                        OR sat_barang LIKE '%$search%'
                        OR stok_retail LIKE '%$search%'
                        OR sat_retail LIKE '%$search%'
                        OR konversi LIKE '%$search%'
                        OR jenisobat LIKE '%$search%'
                        OR hrgsat_barang LIKE '%$search%'
                        OR hrgjual_barang LIKE '%$search%'
                        OR hrgjual_retail LIKE '%$search%'
                        OR indikasi LIKE '%$search%'");

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
            $nestedData['stok_retail'] = $value['stok_retail'];
            $nestedData['sat_retail'] = $value['sat_retail'];
            $nestedData['konversi'] = $value['konversi'];
            $nestedData['jenisobat'] = $value['jenisobat'];
            $nestedData['hrgsat_barang'] = $value['hrgsat_barang'];
            $nestedData['hrgjual_barang'] = $value['hrgjual_barang'];
            $nestedData['hrgjual_retail'] = $value['hrgjual_retail'];
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
