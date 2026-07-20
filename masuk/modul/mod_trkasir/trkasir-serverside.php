<?php
include_once '../../../configurasi/koneksi.php';

if ($_GET['action'] == "table_data") {

    $aksi = "modul/mod_trkasir/aksi_trkasir.php";

    $columns = array(
        0 => 'id_trkasir',
        1 => 'kd_trkasir',
        2 => 'tgl_trkasir',
        3 => 'petugas',
        4 => 'nm_pelanggan',
        5 => 'nm_carabayar',
        6 => 'statusbayar',
        7 => 'nilai_transaksi',
        8 => 'ttl_trkasir',
        9 => 'id_trkasir'
    );

    $from = "FROM trkasir LEFT JOIN carabayar ON trkasir.id_carabayar = carabayar.id_carabayar";

    $querycount = $db->query("SELECT COUNT(*) as jumlah $from");
    $datacount = $querycount->fetch_array();
    $totalData = $datacount['jumlah'];
    $totalFiltered = $totalData;

    $limit = intval($_POST['length']);
    $start = intval($_POST['start']);
    $order = $columns[$_POST['order']['0']['column']];
    $dir = (strtolower($_POST['order']['0']['dir']) == 'asc') ? 'asc' : 'desc';

    if ($limit < 0) {
        $limit = $totalData > 0 ? $totalData : 1;
    }

    if (empty($_POST['search']['value'])) {
        $query = $db->query("SELECT trkasir.*, carabayar.nm_carabayar $from
            ORDER BY $order $dir LIMIT $limit OFFSET $start");
    } else {
        $search = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['search']['value']);
        $where = " WHERE trkasir.kd_trkasir LIKE '%$search%'
                    OR trkasir.nm_pelanggan LIKE '%$search%'
                    OR trkasir.petugas LIKE '%$search%'
                    OR trkasir.statusbayar LIKE '%$search%'
                    OR trkasir.tgl_trkasir LIKE '%$search%'
                    OR carabayar.nm_carabayar LIKE '%$search%'";

        $query = $db->query("SELECT trkasir.*, carabayar.nm_carabayar $from $where
            ORDER BY $order $dir LIMIT $limit OFFSET $start");

        $querycount = $db->query("SELECT COUNT(*) as jumlah $from $where");
        $datacount = $querycount->fetch_array();
        $totalFiltered = $datacount['jumlah'];
    }

    $data = array();
    if (!empty($query)) {
        $no = $start + 1;
        while ($value = $query->fetch_array()) {

            $highlight = '';
            $tanggal_1 = new DateTime($value['tgl_trkasir']);
            $tanggal_2 = new DateTime();
            $selisih = $tanggal_1->diff($tanggal_2);

            if ($selisih->m >= 1 && $value['statusbayar'] != 'LUNAS') {
                $highlight = 'red';
            } elseif (in_array($value['id_carabayar'], [2, 3]) && $value['statusbayar'] == 'PROSES') {
                $highlight = 'yellow';
            }

            $aksi_html = "<a href='?module=penjualansebelumnya&act=ubah&id=$value[id_trkasir]' title='EDIT' class='glyphicon glyphicon-pencil'>&nbsp</a> "
                . "<a class='glyphicon glyphicon-print' onclick=\"window.open('modul/mod_laporan/faktur.php?kd_trkasir=$value[kd_trkasir]','nama window','width=500,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no')\">&nbsp</a> "
                . "<a href=\"javascript:confirmdelete('$aksi?module=trkasir&act=hapus&id=$value[id_trkasir]')\" title='HAPUS' class='glyphicon glyphicon-remove'>&nbsp</a>";

            $nestedData['no'] = $no;
            $nestedData['kd_trkasir'] = $value['kd_trkasir'];
            $nestedData['tgl_trkasir'] = $value['tgl_trkasir'];
            $nestedData['petugas'] = $value['petugas'];
            $nestedData['nm_pelanggan'] = $value['nm_pelanggan'];
            $nestedData['nm_carabayar'] = $value['nm_carabayar'];
            $nestedData['statusbayar'] = $value['statusbayar'];
            $nestedData['nilai_transaksi'] = $value['nilai_transaksi'];
            $nestedData['ttl_trkasir'] = $value['ttl_trkasir'];
            $nestedData['highlight'] = $highlight;
            $nestedData['aksi'] = $aksi_html;
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
