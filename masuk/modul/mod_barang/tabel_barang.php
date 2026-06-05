<?php
include "../../../configurasi/koneksi.php";

?>
<form method="post" action="modul/mod_barang/update_kode_barcode.php?act=update-all">
    <table id="example10" class="table table-bordered table-striped table-responsive">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center" width="20%">Nama Obat</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Satuan Retail</th>
                <th class="text-center">Konversi</th>
                <th class="text-center">Kode Barcode</th>
                <th class="text-center" width="10%">Harga Beli</th>
                <th class="text-center" width="10%">Harga Jual</th>
                <th class="text-center">Submit</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
            // $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT nm_barang, sat_barang, sat_retail, konversi, cek, id_barang, kd_barang  FROM barang a WHERE a.cek='0'");
    
            // $no = 1;
            // while ($lihat = mysqli_fetch_array($query)) :
    
                
            ?>
    
                    <!--<tr>-->
                    <!--    <td class="text-center"><?= $no; ?></td>-->
                    <!--    <td class="text-left"><?= $lihat['nm_barang']; ?></td>-->
                    <!--    <td class="text-center">-->
                    <!--        <input type="text" name="sat_barang_<?=$no?>" id="sat_barang_<?=$no?>" value="<?= $lihat['sat_barang']; ?>" style="width: 100px">-->
                    <!--    </td>-->
                    <!--    <td class="text-center">-->
                    <!--        <input type="text" name="sat_retail_<?=$no?>" id="sat_retail_<?=$no?>" value="<?= $lihat['sat_retail']; ?>" style="width: 100px">-->
                    <!--    </td>-->
                    <!--    <td class="text-center">-->
                    <!--        <input type="number" min="0" name="konversi_<?=$no?>" id="konversi_<?=$no?>" value="<?= $lihat['konversi']; ?>" style="width: 50px; text-align:center">-->
                    <!--    </td>-->
                    <!--    <td class="text-center">-->
                    <!--        <input type="text" name="kd_barcode_<?=$no?>" id="kd_barcode_<?=$no?>" value="<?= $lihat['kd_barang']; ?>">-->
                    <!--        <input type="hidden" name="id_barang_<?=$no?>" id="id_barang_<?=$no?>" value="<?= $lihat['id_barang']; ?>">-->
                            
                    <!--    </td>-->
                    <!--    <td class="text-center">-->
                    <!--        <input type="number" min="0" name="sesuai_<?=$no?>" id="sesuai_<?=$no?>" value="<?= $lihat['cek']; ?>" style="width: 50px; text-align:center">-->
                    <!--    </td>-->
                    <!--    <td class="text-center">-->
                            <!--<button type="submit" class="btn btn-primary btn-sm">-->
                            <!--    <i class="fa fa-fw fa-check"></i>-->
                            <!--    SIMPAN</button>-->
                    <!--        <button type="button" id="pilih_<?= $no ?>" class="btn btn-primary btn-sm" onclick="javascript:update_kode_barcode('<?= $no ?>')" >-->
                    <!--            <i class="fa fa-fw fa-check"></i>-->
                    <!--            SIMPAN</button>-->
                    <!--    </td>-->
                    <!--</tr>-->
    
            <?php
            // $no++;
            // endwhile; 
            ?>
        </tbody>
    </table>
    
    <button type="submit" class="btn btn-primary btn-sm">
        <i class="fa fa-fw fa-check"></i>
        SIMPAN ALL DATA
    </button>
</form>

<script>
    $(document).ready(function() {
        // $('#example10').dataTable({
        //     "aLengthMenu": [
        //         [5, 25, 50, 75, -1],
        //         [5, 25, 50, 75, "All"]
        //     ],
        //     "iDisplayLength": 5
        // });

        $('#example10').DataTable({
			processing: true,
			serverSide: true,
			"aLengthMenu": [
                [5, 25, 50, 75, -1],
                [5, 25, 50, 75, "All"]
            ],
			ajax: {
				"url": "modul/mod_barang/tabel_barang_serverside.php?action=table_data",
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
				},
				{
					"data": "hrgjual_barang",
					"className": 'text-right',
				},
				{
					"data": "aksi",
					"className": 'text-center'
				},
			]
		});
    })
</script>