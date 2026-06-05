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

    $querycount = $db->query("SELECT count(id_barang) as jumlah FROM barang WHERE cek=0");
    $datacount = $querycount->fetch_array();

    $totalData = $datacount['jumlah'];

    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        $query = $db->query("SELECT *
            FROM barang WHERE cek = 0 
            ORDER BY $order $dir LIMIT $limit OFFSET $start");
    } else {
        $search = $_POST['search']['value'];
        $query = $db->query("SELECT *
            FROM barang WHERE cek = 0 AND (kd_barang LIKE '%$search%' 
                        OR nm_barang LIKE '%$search%'
                        OR stok_barang LIKE '%$search%'
                        OR sat_barang LIKE '%$search%'
                        OR sat_retail LIKE '%$search%'
                        OR hrgsat_barang LIKE '%$search%'
                        OR hrgjual_barang LIKE '%$search%'
                        OR konversi LIKE '%$search%')
            ORDER BY $order $dir LIMIT $limit OFFSET $start");

        $querycount = $db->query("SELECT count(id_barang) as jumlah 
            FROM barang WHERE cek = 0 AND (kd_barang LIKE '%$search%' 
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
            $nestedData['no'] = '<input type="checkbox" name="pilihan[]" id="pilihan_'.$no.'" value="' . $value['id_barang'] . '" onchange="javascript:isChecked('.$no.')"> '.$no;
            $nestedData['kd_barang'] = '<input type="text" name="kd_barcode[]" id="kd_barcode_'.$no.'" value="'.$value['kd_barang'].'"><input type="hidden" name="id_barang[]" id="id_barang_'.$no.'" value="'.$value['id_barang'].'">';
            $nestedData['nm_barang'] = $value['nm_barang'];
            $nestedData['sat_barang'] = '<input type="text" name="sat_barang[]" id="sat_barang_'.$no.'" value="'.$value['sat_barang'].'" style="width: 100px">';
            $nestedData['sat_retail'] = '<input type="text" name="sat_retail[]" id="sat_retail_'.$no.'" value="'.$value['sat_retail'].'" style="width: 100px">';
            $nestedData['konversi'] = '<input type="number" min="0" name="konversi[]" id="konversi_'.$no.'" value="'. $value['konversi'].'" style="width: 50px; text-align:center"><input type="hidden" min="0" name="sesuai[]" id="sesuai_'.$no.'" value="'. $value['cek'].'">';
            $nestedData['hrgsat_barang'] = '<input type="number" min="0" name="hrgsat_barang[]" id="hrgsat_barang_'.$no.'" value="'. $value['hrgsat_barang'].'" style="width: 100px; text-align:right">';
            $nestedData['hrgjual_barang'] = '<input type="number" min="0" name="hrgjual_barang[]" id="hrgjual_barang_'.$no.'" value="'. $value['hrgjual_barang'].'" style="width: 100px; text-align:right">';
            $nestedData['aksi'] = '<button type="button" id="pilih_'.$no.'" class="btn btn-primary btn-sm" onclick="javascript:update_kode_barcode('. $no.')">SIMPAN PERBARIS</button>';
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
