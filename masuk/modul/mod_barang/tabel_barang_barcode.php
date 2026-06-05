<?php
include "../../../configurasi/koneksi.php";

?>

    <table id="example11" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Nama Obat</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Satuan Retail</th>
                <th class="text-center">Konversi</th>
                <th class="text-center">Kode Barcode</th>
                <th class="text-center">Harga Beli</th>
                <th class="text-center">Harga Jual</th>
                <th class="text-center" width="10%">Sesuai</th>
                <!--<th class="text-center">Submit</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            
            // $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT nm_barang, sat_barang, sat_retail, konversi, cek, id_barang, kd_barang  FROM barang a WHERE a.cek!='0'");
            // // $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT nm_barang, sat_barang, kd_barang FROM barang a WHERE a.kd_barang != 0 and a.kd_barang !=''");
    
            // $no = 1;
            // while ($lihat = mysqli_fetch_array($query)) :
    
                
            ?>
    
                    <!--<tr>-->
                    <!--    <td class="text-center"><?= $no++; ?></td>-->
                    <!--    <td class="text-left"><?= $lihat['nm_barang']; ?></td>-->
                    <!--    <td class="text-center"><?= $lihat['sat_barang']; ?></td>-->
                    <!--    <td class="text-center"><?= $lihat['sat_retail']; ?></td>-->
                    <!--    <td class="text-center"><?= $lihat['konversi']; ?></td>-->
                    <!--    <td class="text-center">-->
                    <!--        <?= $lihat['kd_barang']; ?>-->
                    <!--    </td>-->
                    <!--    <td class="text-center"><?= $lihat['cek']; ?></td>-->
                        <!--<td class="text-center">-->
                        <!--    <button type="submit" class="btn btn-primary btn-sm">-->
                        <!--        <i class="fa fa-fw fa-check"></i>-->
                        <!--        SIMPAN</button>-->
                            <!--<button type="button" id="pilih_<?= $no ?>" class="btn btn-primary btn-sm" onclick="javascript:update_kode_barcode('<?= $no ?>')" data-id_barang="<?= $lihat['id_barang']; ?>">-->
                            <!--    <i class="fa fa-fw fa-check"></i>-->
                            <!--    SIMPAN</button>-->
                        <!--</td>-->
                    <!--</tr>-->
    
            <?php
            // endwhile; 
            ?>
        </tbody>
    </table>

<script>
    $(document).ready(function() {
        // $('#example11').dataTable({
        //     "aLengthMenu": [
        //         [5, 25, 50, 75, -1],
        //         [5, 25, 50, 75, "All"]
        //     ],
        //     "iDisplayLength": 5
        // });

        $('#example11').DataTable({
			processing: true,
			serverSide: true,
			"aLengthMenu": [
                [5, 25, 50, 75, -1],
                [5, 25, 50, 75, "All"]
            ],
			ajax: {
				"url": "modul/mod_barang/tabel_barcode_serverside.php?action=table_data",
				"dataType": "JSON",
				"type": "POST"
			},
			columns: [{
					"data": "no",
					"className": 'text-center'
				},
				{
					"data": "nm_barang"
				},
				{
					"data": "sat_barang"
				},
				{
					"data": "sat_retail",
					"className": 'text-center'
				},
				{
					"data": "konversi",
					"className": 'text-center'
				},
				{
					"data": "kd_barang",
					"className": 'text-center'
				},
				{
					"data": "hrgsat_barang",
					"className": 'text-right',
					"render": function(data, type, row) {
						return formatRupiah(data);
					}
				},
				{
					"data": "hrgjual_barang",
					"className": 'text-right',
					"render": function(data, type, row) {
						return formatRupiah(data);
					}
				},
				{
					"data": "cek",
					"className": 'text-center'
				},
				
			]
		});
    })
</script>