<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href=../css/style.css rel=stylesheet type=text/css>";
  echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_trkasir/aksi_trkasir.php";
$aksi_trkasir = "masuk/modul/mod_trkasir/aksi_trkasir.php";
switch($_GET[act]){
  // Tampil Penjualan
  default:
	  ?>


			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">TRANSAKSI PENJUALAN SEBELUMNYA</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body">

                    <a  class ='btn  btn-warning  btn-flat' href='#'></a>
                    <small>* Pembayaran belum lunas</small><br>
                    <a  class ='btn  btn-flat' style="background-color:#E52020" href='#'></a>
                    <small>* Pembayaran belum lunas (>1 month)</small>
                    <div></div>
				<!--	<a  class ='btn  btn-warning  btn-flat' href='?module=trkasir&act=penjualansebelum'>PENJUALAN SEBELUMNYA</a>
					<small>* Pembayaran belum lunas</small> -->
					<br><br>


					<table id="tbl_trkasir" class="table table-bordered table-striped" >
						<thead>
							<tr>
								<th>No</th>
								<th>Kode</th>
								<th>Tanggal</th>
								<th>Petugas</th>
								<th>Pelanggan</th>
								<th>Cara Bayar</th>
								<th>Status Bayar</th>
								<th>Nilai Transaksi</th>
								<th>Dana Masuk</th>

								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>

			<script>
			$(document).ready(function() {
				$('#tbl_trkasir').DataTable({
					processing: true,
					serverSide: true,
					order: [[0, 'desc']],
					ajax: {
						"url": "modul/mod_trkasir/trkasir-serverside.php?action=table_data",
						"dataType": "JSON",
						"type": "POST"
					},
					"rowCallback": function(row, data, index) {
						if (data['highlight'] == 'red') {
							$(row).find('td:eq(0)').css('background-color', '#E52020');
							$(row).find('td:eq(1)').css('background-color', '#E52020');
						} else if (data['highlight'] == 'yellow') {
							$(row).find('td:eq(0)').css('background-color', '#ffbf00');
							$(row).find('td:eq(1)').css('background-color', '#ffbf00');
						}
					},
					columns: [
						{ "data": "no", "className": "text-center" },
						{ "data": "kd_trkasir" },
						{ "data": "tgl_trkasir" },
						{ "data": "petugas" },
						{ "data": "nm_pelanggan" },
						{ "data": "nm_carabayar", "className": "text-center" },
						{ "data": "statusbayar", "className": "text-center" },
						{
							"data": "nilai_transaksi",
							"className": "text-center",
							"render": function(data, type, row) {
								return formatRupiah(data);
							}
						},
						{
							"data": "ttl_trkasir",
							"className": "text-right",
							"render": function(data, type, row) {
								return formatRupiah(data);
							}
						},
						{ "data": "aksi", "orderable": false }
					]
				});
			});
			</script>

<?php

    break;
	
    case "tambah":
            //cek apakah ada kode transaksi ON berdasarkan user

            $cekkd = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM kdtk WHERE id_admin='$_SESSION[idadmin]' AND stt_kdtk='ON'");
            $ketemucekkd = mysqli_num_rows($cekkd);
            $hcekkd = mysqli_fetch_array($cekkd);
            $petugas = $_SESSION['namalengkap'];

            if ($ketemucekkd > 0) {
                $kdtransaksi = $hcekkd['kd_trkasir'];
            } else {
                $kdunik = date('dmyHis');
                $kdtransaksi = "TKP-" . $kdunik;
                mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO kdtk(kd_trkasir,id_admin) VALUES('$kdtransaksi','$_SESSION[idadmin]')");
            }

            $tglharini = date('Y-m-d');

            echo "
		  <div class='box box-primary box-solid table-responsive'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH PENJUALAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form onsubmit='return false;' method=POST action='$aksi?module=trkasir&act=input_trkasir' enctype='multipart/form-data' class='form-horizontal'>
						
						       <input type=hidden name='id_trkasir' id='id_trkasir' value='0'>
							   <input type=hidden name='kd_trkasir' id='kd_trkasir' value='$kdtransaksi'>
							   <input type=hidden name='stt_aksi' id='stt_aksi' value='input_trkasir'>
							   <input type=hidden name='petugas' id='petugas' value='$petugas'>
						 
						<div class='col-lg-6'>
							  
								<div class='form-group'>
							  
									<label class='col-sm-4 control-label'>Tanggal </label>
										<div class='col-sm-6'>
											<div class='input-group date'>
												<div class='input-group-addon'>
													<span class='glyphicon glyphicon-th'></span>
												</div>
													<input type='text' class='datepicker' name='tgl_trkasir' id='tgl_trkasir' required='required' value='$tglharini' autocomplete='off'>
											</div>
										</div>
										
									<label class='col-sm-4 control-label'>Kode Transaksi</label>        		
										<div class='col-sm-6'>
											<input type=text name='kd_hid' id='kd_hid' class='form-control' required='required' value='$kdtransaksi' autocomplete='off' Disabled>
										</div>
																		
									<label class='col-sm-4 control-label'>Pelanggan</label>        		
										<div class='col-sm-6'>
											<input type=text name='nm_pelanggan' id='nm_pelanggan' class='typeahead form-control' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Telepon</label>        		
										<div class='col-sm-6'>
											<input type=text name='tlp_pelanggan' id='tlp_pelanggan' class='form-control' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Alamat</label>        		
										<div class='col-sm-6'>
											<textarea name='alamat_pelanggan' id='alamat_pelanggan' class='form-control' rows='2'></textarea>
										</div>
										
									<label class='col-sm-4 control-label'>Keterangan Pembayaran</label>        		
										<div class='col-sm-6'>
											<textarea name='ket_trkasir' id='ket_trkasir' class='form-control' rows='2'></textarea>
										</div>
									
								</div>
							  
						</div>
						
						<div class='col-lg-6'>
						
						
								<input type=hidden name='id_barang' id='id_barang'>
								<input type=hidden name='stok_barang' >
								
								<div class='form-group'>
								
									
									 
									<label class='col-sm-4 control-label'>Kode Barang</label>        		
									 <div class='col-sm-7'>
									 <div class='input-group'>
										<input type=text name='kd_barang' id='kd_barang' class='form-control' autocomplete='off'>
										<div class='input-group-addon'>
											<button type=button data-toggle='modal' data-target='#ModalItem' href='#'><span class='glyphicon glyphicon-search'></span></button>
										</div>
										</div>
									 </div>
									 
									<label class='col-sm-4 control-label'>Nama Barang</label>        		
											<div class='col-sm-7'>
													<div class='btn-group btn-group-justified' role='group' aria-label='...'>
                                                      <div class='btn-group' role='group'>
                                                        <input type=text name='nmbrg_dtrkasir' id='nmbrg_dtrkasir' class='typeahead form-control' autocomplete='off'>
                                                      </div>
                                                      <div class='btn-group' role='group'>
                                                        <button type='button' class='btn btn-primary' id='nmbrg_dtrkasir_enter'>Enter</button>
                                                      </div>
                                                    </div>
											</div>
											
									<label class='col-sm-4 control-label'>Jenis Transaksi</label>        		
									 <div class='col-sm-7'>									    
										    <select name='jns_transaksi' id='jns_transaksi' class='form-control'>
										        <option value='1'>Grosir</option>
										        <option value='2'>Retail</option>
										    </select>										
									 </div>
											
									<label class='col-sm-4 control-label'>Qty</label>        		
										<div class='col-sm-7'>
											<input type='number' name='qty_dtrkasir' id='qty_dtrkasir' class='form-control' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Satuan</label>        		
										<div class='col-sm-7'>
											<input type=text name='sat_dtrkasir' id='sat_dtrkasir' class='form-control' autocomplete='off' disabled>
										</div>
										
									<label class='col-sm-4 control-label'>Harga</label>        		
										<div class='col-sm-7'>
											<input type=text name='hrgjual_dtrkasir' id='hrgjual_dtrkasir' class='form-control' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Disc</label>        		
										<div class='col-sm-7'>
											<input type=text name='disc' id='disc' class='form-control' autocomplete='off' value='0'>
											</p>
												<div class='buttons'>
													<button type='button' class='btn btn-success right-block' onclick='simpan_detail();'>SIMPAN DETAIL</button>
												</div>
										</div>
										
								</div>
								
						</div>
						</form>
							  
				</div> 
				
				<div id='tabeldata'>
				
			</div>";

            break;

        case "ubah":
            $ubah=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir 
	WHERE trkasir.id_trkasir='$_GET[id]'");
            $re=mysqli_fetch_array($ubah);
            $petugas= $_SESSION['namalengkap'];


            echo "
		  <div class='box box-primary box-solid table-responsive'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH PENJUALAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form onsubmit='return false;' method=POST action='$aksi?module=trkasir&act=ubah_trkasir' enctype='multipart/form-data' class='form-horizontal'>
						
						       <input type=hidden name='id_trkasir' id='id_trkasir' value='$re[id_trkasir]'>
							   <input type=hidden name='kd_trkasir' id='kd_trkasir' value='$re[kd_trkasir]'>
							   <input type=hidden name='stt_aksi' id='stt_aksi' value='ubah_trkasir'>
							   <input type=hidden name='petugas' id='petugas' value='$petugas'>
							 
						<div class='col-lg-6'>
							  
								<div class='form-group'>
							  
									<label class='col-sm-4 control-label'>Tanggal</label>
										<div class='col-sm-6'>
											<div class='input-group date'>
												<div class='input-group-addon'>
													<span class='glyphicon glyphicon-th'></span>
												</div>
													<input type='text' class='datepicker' name='tgl_trkasir' id='tgl_trkasir' value='$re[tgl_trkasir]' required='required' value='$tglharini' autocomplete='off'>
											</div>
										</div>
										
									<label class='col-sm-4 control-label'>Kode Transaksi</label>        		
										<div class='col-sm-6'>
											<input type=text name='kd_hid' id='kd_hid' class='form-control' required='required' value='$re[kd_trkasir]' autocomplete='off' Disabled>
										</div>
									
									<label class='col-sm-4 control-label'>Pelanggan</label>        		
										<div class='col-sm-6'>
											<input type=text name='nm_pelanggan' id='nm_pelanggan' class='typeahead form-control' value='$re[nm_pelanggan]' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Telepon</label>        		
										<div class='col-sm-6'>
											<input type=text name='tlp_pelanggan' id='tlp_pelanggan' class='form-control' value='$re[tlp_pelanggan]' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Alamat</label>        		
										<div class='col-sm-6'>
											<textarea name='alamat_pelanggan' id='alamat_pelanggan' class='form-control' rows='2'>$re[alamat_pelanggan]</textarea>
										</div>
										
									<label class='col-sm-4 control-label'>Keterangan Pembayaran</label>        		
										<div class='col-sm-6'>
											<textarea name='ket_trkasir' id='ket_trkasir' class='form-control' rows='2'>$re[ket_trkasir]</textarea>
										</div>
										
								</div>
							  
						</div>
						
						<div class='col-lg-6'>
						
						
								<input type=hidden name='id_barang' id='id_barang'>
								<input type=hidden name='stok_barang' >
								
								
								
								
									 
								<div class='form-group'>
									<label class='col-sm-4 control-label'>Kode Barang</label>        		
									 <div class='col-sm-7'>
									 <div class='input-group'>
										<input type=text name='kd_barang' id='kd_barang' class='form-control' autocomplete='off'>
										<div class='input-group-addon'>
											<button type=button data-toggle='modal' data-target='#ModalItem' href='#'><span class='glyphicon glyphicon-search'></span></button>
										</div>
										</div>
									 </div>
									 
									<label class='col-sm-4 control-label'>Nama Barang</label>        		
											<div class='col-sm-7'>
													<div class='btn-group btn-group-justified' role='group' aria-label='...'>
                                                      <div class='btn-group' role='group'>
                                                        <input type=text name='nmbrg_dtrkasir' id='nmbrg_dtrkasir' class='typeahead form-control' autocomplete='off'>
                                                      </div>
                                                      <div class='btn-group' role='group'>
                                                        <button type='button' class='btn btn-primary' id='nmbrg_dtrkasir_enter'>Enter</button>
                                                      </div>
                                                    </div>
											</div>
											
									<label class='col-sm-4 control-label'>Jenis Transaksi</label>        		
									 <div class='col-sm-7'>
									    
										    <select name='jns_transaksi' id='jns_transaksi' class='form-control'>
										        <option value='1'>Grosir</option>
										        <option value='2'>Retail</option>
										    </select>										
									 </div>	
											
									<label class='col-sm-4 control-label'>Qty</label>        		
										<div class='col-sm-7'>
											<input type='number' name='qty_dtrkasir' id='qty_dtrkasir' class='form-control' autocomplete='off'>
										</div>
											
									<label class='col-sm-4 control-label'>Satuan</label>        		
										<div class='col-sm-7'>
											<input type=text name='sat_dtrkasir' id='sat_dtrkasir' class='form-control' autocomplete='off' disabled>
										</div>
										
									<label class='col-sm-4 control-label'>Harga</label>        		
										<div class='col-sm-7'>
											<input type=number name='hrgjual_dtrkasir' id='hrgjual_dtrkasir' class='form-control' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Disc</label>        		
										<div class='col-sm-7'>
											<input type=number name='disc' id='disc' class='form-control' autocomplete='off' value='0'>
											</p>
												<div class='buttons'>
													<button type='button' class='btn btn-success right-block' onclick='simpan_detail();'>SIMPAN DETAIL</button>
												</div>
										</div>
								</div>
								
								
						</div>
						</form>
							  
				</div> 
				
				<div id='tabeldata'>
				
			</div>";
            break;
        case "cari";

            $tgl_awal = date('Y-m-d');
            $tgl_kemarin = date('Y-m-d', strtotime('-1 days', strtotime( $tgl_awal)));
            $tgl_akhir = date('Y-m-d', strtotime('-60 days', strtotime( $tgl_awal)));

            $tampil_trkasir = mysqli_query($GLOBALS["___mysqli_ston"],
                "SELECT * FROM trkasir 
        where tgl_trkasir between '$tgl_akhir' and '$tgl_kemarin'ORDER BY id_trkasir desc ") ;

            ?>


            <div class="box box-primary box-solid table-responsive">
                <div class="box-header with-border">
                    <h3 class="box-title">TRANSAKSI PENJUALAN SEBELUMNYA</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div>
                <div class="box-body">

                    <a  class ='btn  btn-warning  btn-flat' href='#'></a>
                    <small>* Pembayaran belum lunas</small>
                    <div></div>
                    <!--	<a  class ='btn  btn-warning  btn-flat' href='?module=trkasir&act=penjualansebelum'>PENJUALAN SEBELUMNYA</a>
                        <small>* Pembayaran belum lunas</small> -->
                    <br><br>


                    <table id="example1" class="table table-bordered table-striped" >
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Status Bayar</th>
                            <th>Total Transaksi</th>
                            <th>Total Dana Masuk</th>
                            <th>Sisa Bayar</th>
                            <th width="70">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no=1;
                        $tgl_awal = date('Y-m-d');
                        while ($r=mysqli_fetch_array($tampil_trkasir)){
                            $ttl_trkasir = $r['ttl_trkasir'];
                            $ttl_trkasir2 = format_rupiah($ttl_trkasir);
                            $ttljual += $ttl_trkasir;
                            $ttljual1 = format_rupiah($ttljual);

                            $query2=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT                                         
                                        SUM(hrgttl_dtrkasir)as ttltransaksi,
                                        SUM(disc) as ttldisc                                                              
                                        FROM trkasir_detail                                      
                                        WHERE kd_trkasir ='$r[kd_trkasir]'");
                            $r2=mysqli_fetch_array($query2);
                            $tts = format_rupiah($r2['ttltransaksi'] - $r2['ttldisc']);
                            $sb = format_rupiah($r2['ttltransaksi'] - $r2['ttldisc'] - $r['ttl_trkasir']);




                            if($r['id_carabayar']>1 ){
                                echo"<td style='background-color:#ffbf00;'>$no</td>
											 <td style='background-color:#ffbf00;'>$r[kd_trkasir]</td>";}
                            else{ echo "<td>$no</td>
                                                <td>$r[kd_trkasir]";
                            }
                            echo"	<td>$r[tgl_trkasir]</td>
											<td>$r[nm_pelanggan]</td>";
                            $cabay = mysqli_query($GLOBALS["___mysqli_ston"],
                                "SELECT * FROM trkasir JOIN carabayar on trkasir.id_carabayar = carabayar.id_carabayar WHERE trkasir.kd_trkasir ='$r[kd_trkasir]'");
                            $cabay1 = mysqli_fetch_array($cabay);

                            echo"
											<td align='center'>$cabay1[statusbayar]</td>";
                            echo"													
											<td align=right>$tts</td>
											<td align=right>$ttl_trkasir2</td>
											<td align=right>$sb</td>
											
											 <td><a href='?module=trkasir&act=ubah&id=$r[id_trkasir]' title='EDIT' class='glyphicon glyphicon-pencil'>&nbsp</a> 
											 ";
                            ?>
                            <a class='glyphicon glyphicon-print' onclick="window.open('modul/mod_laporan/faktur.php?kd_trkasir=<?php echo $r['kd_trkasir']?>','nama window','width=500,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no')">&nbsp</a>
                            <?php
                            echo"
											 <a href=javascript:confirmdelete('$aksi?module=trkasir&act=hapus&id=$r[id_trkasir]') title='HAPUS' class='glyphicon glyphicon-remove'>&nbsp</a>
											 
											</td>
										</tr>";
                            $no++;
                        }
                        echo "</tbody>
                            
                        </table>";
                        ?>
                </div>
            </div>
            <input class='btn btn-danger' type=button value=KEMBALI onclick=self.history.back()>
            <?php

            break;
    }
}
?>

<!-- Modal itemmat -->
<div id="ModalItem" class="modal fade" role="dialog">
    <div class="modal-lg modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PILIH ITEM BARANG</h4>

                <div id="box">
                    <CENTER><strong>MySIFA PROFIT ANALYSIS</strong></CENTER><br>
                    <center><button type="button" class="btn btn-info">PROFIT>200%</button>
                        <button type="button" class="btn btn-success">PROFIT = 100 - 200 % </button>
                        <button type="button" class="btn btn-warning">PROFIT = 30 - 100%"</button>
                        <button type="button" class="btn btn-danger">PROFIT<30%</button></center>
                </div>
            </div>



            <div class="modal-body table-responsive">
                <table id="example1" class="table table-bordered table-striped" >

                    <thead>
                    <tr class="judul-table">
                        <th style="vertical-align: middle; background-color: #008000; text-align: center; ">No</th>
                        <th style="vertical-align: middle; background-color: #008000; text-align: left; ">Kode</th>
                        <th style="vertical-align: middle; background-color: #008000; text-align: left; ">Nama Barang</th>
                        <th style="vertical-align: middle; background-color: #008000; text-align: right; ">Qty Grosir</th>
                        <th style="vertical-align: middle; background-color: #008000; text-align: center; ">Satuan Grosir</th>
                        <th style="vertical-align: middle; background-color: #008000; text-align: right; ">Qty Retail</th>
                        <th style="vertical-align: middle; background-color: #008000; text-align: center; ">Satuan Retail</th>
                        <th style="vertical-align: middle; background-color: #008000; text-align: center; ">Harga Jual Grosir</th>
                        <th style="vertical-align: middle; background-color: #008000; text-align: center; ">Harga Jual Retail</th>
                        <th style="vertical-align: middle; background-color: #008000; text-align: center; ">Indikasi</th>
                        <th style="vertical-align: middle; background-color: #008000; text-align: center; ">Pilih</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=1;
                    $tampil_dproyek=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang ORDER BY id_barang ASC");
                    while ($rd=mysqli_fetch_array($tampil_dproyek)){
                        $Q = intval(($rd['hrgjual_barang']-$rd['hrgsat_barang'])/$rd['hrgsat_barang']);
                        $harga1 = format_rupiah($rd['hrgjual_barang']);
                        $harga2 = format_rupiah($rd['hrgjual_retail']);
                        $disc = 0 ;

                        echo "<tr style='font-size: 13px;'> 
										     <td align=center>$no</td>
											 <td width='20px'>$rd[kd_barang]</td>
											 <td>$rd[nm_barang]</td>
											 <td align=right id='stok_barang'><div id='stok_$rd[id_barang]'>$rd[stok_barang]</div></td>
											 <td align=center>$rd[sat_barang]</td>
											 <td align=right id='stok_retail'><div id='retail_$rd[id_barang]'>$rd[stok_retail]</div></td>
											 <td align=center>$rd[sat_retail]</td>";
                        if( $Q <= 0.3){
                            echo" <td style='background-color:#ff003f;'align='center'><strong>$harga1</strong></td> ";                                                            }
                        elseif ($Q > 0.3 && $Q <= 1){
                            echo"  <td style='background-color:#f39c12;' align='center'><strong>$harga1</strong></td>"; }
                        elseif ($Q > 1 && $Q <= 2){
                            echo"  <td style='background-color:#00ff3f;' align='center'><strong>$harga1</strong></td>"; }
                        elseif ($Q > 2 ){
                            echo"  <td style='background-color:#00bfff;' align='center'><strong>$harga1</strong></td>"; }
                        echo"
											 <td align=center>$harga2</td>		
											 <td align=center>$rd[indikasi]</td>		
											
											 <td align=center>
											 
											 <button class='btn btn-xs btn-info' id='pilihbarang' 
												 data-id_barang='$rd[id_barang]'
												 data-kd_barang='$rd[kd_barang]'
												 data-nm_barang='$rd[nm_barang]'
												 data-stok_barang='$rd[stok_barang]'
												 data-stok_retail='$rd[stok_retail]'
												 data-sat_barang='$rd[sat_barang]'												 
												 data-hrgjual_barang='$rd[hrgjual_barang]'
												 data-hrgjual_retail='$rd[hrgjual_retail]'
												 data-disc='$disc'
												 >
												 <i class='fa fa-check'></i>
												 </button>
												
											</td>
										</tr>";
                        $no++;
                    }
                    echo "</tbody></table>";
                    ?>
            </div>
        </div>
    </div>
</div>
<!-- end modal item -->

<script type="text/javascript">
    $(function(){
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    });
</script>

<script>

    $(document).ready(function () {
        tabel_detail();

    });

    $('select[name="jns_transaksi"]').on('change', function(){
        var jns_transaksi = $('select[name="jns_transaksi"]').val();
        var kd_barang = $('#kd_barang').val();
        let nm_barang = $('#nmbrg_dtrkasir').val();

        if(kd_barang != ''){

            $.ajax({
                url: 'modul/mod_trkasir/autonamabarang_enter.php',
                type: 'post',
                data: {
                    'nm_barang': nm_barang
                },
            }).success(function(response) {
                let data = $.parseJSON(response);
                // let data = JSON.parse(response)
                let qty_default = "1";

                for (let i = 0; i < data.length; i++) {
                    data = data[i];
                    // 1 = Grosir
                    // 2 = Retail
                    if(jns_transaksi == '2'){
                        document.getElementById('id_barang').value = data.id_barang;
                        document.getElementById('kd_barang').value = data.kd_barang;
                        document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                        document.getElementById('stok_barang').value = data.stok_barang;
                        document.getElementById('stok_retail').value = data.stok_retail;

                        document.getElementById('qty_dtrkasir').value = qty_default;
                        document.getElementById('sat_dtrkasir').value = data.sat_retail;
                        document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_retail;
                        document.getElementById('indikasi').value = data.indikasi;

                    } else if(jns_transaksi == '1'){
                        document.getElementById('id_barang').value = data.id_barang;
                        document.getElementById('kd_barang').value = data.kd_barang;
                        document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                        document.getElementById('stok_barang').value = data.stok_barang;
                        document.getElementById('stok_retail').value = data.stok_retail;

                        document.getElementById('qty_dtrkasir').value = qty_default;
                        document.getElementById('sat_dtrkasir').value = data.sat_barang;
                        document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang;
                        document.getElementById('indikasi').value = data.indikasi;

                    }
                }

            });
        }
    });

    // Autocomplete nama obat
    $('#nmbrg_dtrkasir').typeahead({
        source: function(query, process) {
            return $.post('modul/mod_trkasir/autonamabarang.php', {
                query: query
            }, function(data) {

                data = $.parseJSON(data);
                return process(data);

            });
        }
    });

    // event enter nama obat
    $(document).ready(function() {
        $('#nmbrg_dtrkasir').on('keydown', function(e) {
            if (e.which == 13) {
                let nm_barang = $('#nmbrg_dtrkasir').val();
                let jns_transaksi = $('select[name="jns_transaksi"]').val();

                $.ajax({
                    url: 'modul/mod_trkasir/autonamabarang_enter.php',
                    type: 'post',
                    data: {
                        'nm_barang': nm_barang
                    },
                }).success(function(response) {
                    let data = $.parseJSON(response);
                    // let data = JSON.parse(response)
                    let qty_default = "1";
                    console.log(data);
                    for (let i = 0; i < data.length; i++) {
                        data = data[i];
                        // 1 = Grosir
                        // 2 = Retail
                        if(jns_transaksi == '2'){
                            document.getElementById('id_barang').value = data.id_barang;
                            document.getElementById('kd_barang').value = data.kd_barang;
                            document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                            document.getElementById('stok_barang').value = data.stok_barang;
                            document.getElementById('stok_retail').value = data.stok_retail;
                            document.getElementById('qty_dtrkasir').value = qty_default;
                            document.getElementById('sat_dtrkasir').value = data.sat_retail;
                            document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_retail;
                            document.getElementById('indikasi').value = data.indikasi;

                        } else {
                            document.getElementById('id_barang').value = data.id_barang;
                            document.getElementById('kd_barang').value = data.kd_barang;
                            document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                            document.getElementById('stok_barang').value = data.stok_barang;
                            document.getElementById('stok_retail').value = data.stok_retail;

                            document.getElementById('qty_dtrkasir').value = qty_default;
                            document.getElementById('sat_dtrkasir').value = data.sat_barang;
                            document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang;
                            document.getElementById('indikasi').value = data.indikasi;

                        }
                    }

                });
            }
        })
    });

    $('#nmbrg_dtrkasir_enter').on('click', function(){
        let nm_barang = $('#nmbrg_dtrkasir').val();
        let jns_transaksi = $('select[name="jns_transaksi"]').val();

        $.ajax({
            url: 'modul/mod_trkasir/autonamabarang_enter.php',
            type: 'post',
            data: {
                'nm_barang': nm_barang
            },
        }).success(function(response) {
            let data = $.parseJSON(response);
            // let data = JSON.parse(response)
            let qty_default = "1";

            for (let i = 0; i < data.length; i++) {
                data = data[i];

                if(jns_transaksi == 'retail'){
                    document.getElementById('id_barang').value = data.id_barang;
                    document.getElementById('kd_barang').value = data.kd_barang;
                    document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                    document.getElementById('stok_barang').value = data.stok_barang;
                    document.getElementById('stok_retail').value = data.stok_retail;

                    document.getElementById('qty_dtrkasir').value = qty_default;
                    document.getElementById('sat_dtrkasir').value = data.sat_retail;
                    document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_retail;
                    document.getElementById('indikasi').value = data.indikasi;
                    document.getElementById('disc').value = data.disc;

                } else {
                    document.getElementById('id_barang').value = data.id_barang;
                    document.getElementById('kd_barang').value = data.kd_barang;
                    document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                    document.getElementById('stok_barang').value = data.stok_barang;
                    document.getElementById('stok_retail').value = data.stok_retail;

                    document.getElementById('qty_dtrkasir').value = qty_default;
                    document.getElementById('sat_dtrkasir').value = data.sat_barang;
                    document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang;
                    document.getElementById('indikasi').value = data.indikasi;
                    document.getElementById('disc').value = data.disc;

                }

            }

        });
    })

    $(document).on('click', '#hapusdetail2', function(){

        var id_tempo = $(this).data('id_tempo');
        var kd_trkasir = $(this).data('kd_trkasir');

        $.ajax({
            type: 'post',
            url: "modul/mod_trkasir/hapusdetail_trkasir_tempo.php",
            data: {
                id_tempo: id_tempo
            },

            success: function (data) {
                //setelah simpan data, tabel_detail data terbaru
                //alert('Hapus data detail berhasil');
                tabel_detail();
                $('#stok_'+id_tempo).html(data);
                //hilangkan modal
                $(".close").click();
            }
        });

    });

    $(document).on('click', '#pilihbarang', function(){

        var id_barang = $(this).data('id_barang');
        var kd_barang = $(this).data('kd_barang');
        var nm_barang = $(this).data('nm_barang');
        var stok_barang = $(this).data('stok_barang');
        var stok_retail = $(this).data('stok_retail');
        var sat_barang = $(this).data('sat_barang');
        var hrgjual_barang = $(this).data('hrgjual_barang');
        var hrgjual_retail = $(this).data('hrgjual_retail');
        var disc = $(this).data('disc');
        var qty_default = "1";
        let jns_transaksi = $('select[name="jns_transaksi"]').val();

        $.ajax({
            url: 'modul/mod_trkasir/gettabel_barang.php',
            type: 'post',
            data: {'id_barang': id_barang},
            dataType: 'JSON',
            success: function(response) {

                if(jns_transaksi == '2'){
                    document.getElementById('id_barang').value= response[0].id_barang ;
                    document.getElementById('kd_barang').value= response[0].kd_barang ;
                    document.getElementById('nmbrg_dtrkasir').value= response[0].nm_barang ;
                    document.getElementById('stok_barang').value= response[0].stok_barang ;
                    document.getElementById('stok_retail').value= response[0].stok_retail ;
                    document.getElementById('qty_dtrkasir').value= qty_default ;
                    document.getElementById('sat_dtrkasir').value= response[0].sat_retail ;
                    document.getElementById('hrgjual_dtrkasir').value= response[0].hrgjual_retail ;
                    document.getElementById('disc').value= response[0].disc ;
                } else if(jns_transaksi == '1'){
                    document.getElementById('id_barang').value= response[0].id_barang ;
                    document.getElementById('kd_barang').value= response[0].kd_barang ;
                    document.getElementById('nmbrg_dtrkasir').value= response[0].nm_barang ;
                    document.getElementById('stok_barang').value= response[0].stok_barang ;
                    document.getElementById('stok_retail').value= response[0].stok_retail ;
                    document.getElementById('qty_dtrkasir').value= qty_default ;
                    document.getElementById('sat_dtrkasir').value= response[0].sat_barang ;
                    document.getElementById('hrgjual_dtrkasir').value= response[0].hrgjual_barang ;
                    document.getElementById('disc').value= response[0].disc ;

                }

            }

        });

        //hilangkan modal
        $(".close").click();

    });


    function simpan_detail(){

        let kd_trkasir = document.getElementById('kd_trkasir').value;
        let id_barang = document.getElementById('id_barang').value;
        let kd_barang = document.getElementById('kd_barang').value;
        let nmbrg_dtrkasir = document.getElementById('nmbrg_dtrkasir').value;
        let stok_barang = document.getElementById('stok_barang').value;
        let stok_retail = document.getElementById('stok_retail').value;
        let qty_dtrkasir = document.getElementById('qty_dtrkasir').value;
        let sat_dtrkasir = document.getElementById('sat_dtrkasir').value;
        let hrgjual_dtrkasir = document.getElementById('hrgjual_dtrkasir').value;
        let disc = document.getElementById('disc').value;

        var jns_transaksi = $('select[name="jns_transaksi"]').val();

        if(nmbrg_dtrkasir == ""){
            alert('Belum ada Item terpilih');
        }else if(qty_dtrkasir == ""){
            alert('Qty tidak boleh kosong');
// 	}else if(parseInt(stok_barang) < parseInt(qty_dtrkasir)){
// 	alert('Stok barang tidak mencukupi');
        }else{


            $.ajax({

                type: 'post',
                url: "modul/mod_trkasir/simpandetail_trkasir.php",
                data: {
                    'kd_trkasir': kd_trkasir,
                    'id_barang': id_barang,
                    'kd_barang': kd_barang,
                    'nmbrg_dtrkasir': nmbrg_dtrkasir,
                    'qty_dtrkasir': qty_dtrkasir,
                    'sat_dtrkasir': sat_dtrkasir,
                    'hrgjual_dtrkasir': hrgjual_dtrkasir,
                    'disc': disc,
                    'tipe': jns_transaksi,
                },
                success: function(response) {
                    //alert('Tambah data detail berhasil');
                    document.getElementById("id_barang").value = "";
                    document.getElementById("kd_barang").value = "";
                    document.getElementById("nmbrg_dtrkasir").value = "";
                    document.getElementById("qty_dtrkasir").value = "";
                    document.getElementById("sat_dtrkasir").value = "";
                    document.getElementById("hrgjual_dtrkasir").value = "";
                    document.getElementById("disc").value = "";
                    tabel_detail();

                    let data = $.parseJSON(response);
                    $('#stok_'+id_barang).html(data.stok_barang_baru);
                    $('#retail_'+id_barang).html(data.stok_retail_baru);
                }
            });
        }
    }



    $(document).on('click', '#hapusdetail', function(){

        var id_dtrkasir = $(this).data('id_dtrkasir');
        var id_barang = $(this).data('id_barang');

        $.ajax({
            type: 'post',
            url: "modul/mod_trkasir/hapusdetail_trkasir.php",
            data: {
                id_dtrkasir: id_dtrkasir
            },

            success: function (response) {
                //setelah simpan data, tabel_detail data terbaru
                //alert('Hapus data detail berhasil');
                tabel_detail();
                // 	$('#stok_'+id_barang).html(data);

                let data = $.parseJSON(response);
                $('#stok_'+id_barang).html(data.stok_barang_baru);
                $('#retail_'+id_barang).html(data.stok_retail_baru);

                //hilangkan modal
                $(".close").click();
            }
        });

    });



    //fungsi tabel detail
    function tabel_detail() {

        var kd_trkasir = document.getElementById('kd_trkasir').value;
        var stt_aksi = document.getElementById('stt_aksi').value;

        $.ajax({
            url: 'modul/mod_trkasir/tbl_detail.php',
            type: 'post',
            data: {'kd_trkasir': kd_trkasir, 'stt_aksi': stt_aksi},
            success: function(data) {
                $('#tabeldata').html(data);
            }

        });
    }

    //auto pelanggan
    $('#nm_pelanggan').typeahead({
        source:  function (query, process) {
            return $.get('modul/mod_trkasir/autopelanggan.php', { query: query }, function (data) {

                console.log(data);
                data = $.parseJSON(data);
                return process(data);

            });
        }
    });


    //enter pelanggan
    $('#nm_pelanggan').keydown(function(e) {
        if (e.which == 13) { // e.which == 13 merupakan kode yang mendeteksi ketika anda   // menekan tombol enter di keyboard
            //letakan fungsi anda disini

            var nm_pelanggan = $("#nm_pelanggan").val();
            $.ajax({
                url: 'modul/mod_trkasir/autopelanggan_enter.php',
                type: 'post',
                data: {'nm_pelanggan': nm_pelanggan },
            }).success(function (data) {

                var json = data;
                //replace array [] menjadi ''
                var res1 = json.replace("[", "");
                var res2 = res1.replace("]", "");
                //INI CONTOH ARRAY JASON const json = '{"result":true, "count":42}';
                datab = JSON.parse(res2);
                document.getElementById('nm_pelanggan').value= datab.nm_pelanggan ;
                document.getElementById('tlp_pelanggan').value= datab.tlp_pelanggan ;
                document.getElementById('alamat_pelanggan').value= datab.alamat_pelanggan ;
            });

        }
    });

    $('#kd_barang').keydown(function(e) {
        if (e.which == 13) { // e.which == 13 merupakan kode yang mendeteksi ketika anda   // menekan tombol enter di keyboard
            //letakan fungsi anda disini

            var kd_brg = $("#kd_barang").val();
            $.ajax({
                url: 'modul/mod_trkasir/autobarang.php',
                type: 'post',
                data: {'kd_brg': kd_brg },
            }).success(function (data) {

                var json = data;
                //replace array [] menjadi ''
                var res1 = json.replace("[", "");
                var res2 = res1.replace("]", "");
                //INI CONTOH ARRAY JASON const json = '{"result":true, "count":42}';
                datab = JSON.parse(res2);
                document.getElementById('id_barang').value= datab.id_barang ;
                document.getElementById('nmbrg_dtrkasir').value= datab.nm_barang ;
                document.getElementById('stok_barang').value= datab.stok_barang ;
                document.getElementById('qty_dtrkasir').value= "1" ;
                document.getElementById('sat_dtrkasir').value= datab.sat_barang ;
                document.getElementById('hrgjual_dtrkasir').value= datab.hrgjual_barang ;
                document.getElementById('disc').value= datab.disc ;

            });

        }
    });


    function simpan_transaksi(){

        var id_trkasir = document.getElementById('id_trkasir').value;
        var kd_trkasir = document.getElementById('kd_trkasir').value;
        var petugas = document.getElementById('petugas').value;
        var tgl_trkasir = document.getElementById('tgl_trkasir').value;
        var nm_pelanggan = document.getElementById('nm_pelanggan').value;
        var tlp_pelanggan = document.getElementById('tlp_pelanggan').value;
        var alamat_pelanggan = document.getElementById('alamat_pelanggan').value;
        var ttl_trkasir = document.getElementById('ttl_trkasir').value;
        var diskon = document.getElementById('diskon').value;
        var diskon2 = document.getElementById('diskon2').value;
        var dp_bayar = document.getElementById('dp_bayar').value;
        var sisa_bayar = document.getElementById('sisa_bayar').value;
        var ket_trkasir = document.getElementById('ket_trkasir').value;
        var stt_aksi = document.getElementById('stt_aksi').value;
        var id_carabayar = document.getElementById('id_carabayar').value;


        var ttl_trkasir1 = ttl_trkasir.replace(".", "");
        var dp_bayar1 = dp_bayar.replace(".", "");
        var sisa_bayar1 = sisa_bayar.replace(".", "");

        var ttl_trkasir1x = ttl_trkasir1.replace(".", "");
        var dp_bayar1x = dp_bayar1.replace(".", "");
        var sisa_bayar1x = sisa_bayar1.replace(".", "");

        var diskon1x = diskon.replace(/\,/g, "");

        var diskon2x = diskon2.replace(/\./g, "");

        if(stt_aksi == 'ubah_trkasir'){
            var status = document.getElementById('statusbayar').value;
        } else {
            if(id_carabayar == '1'){
                var status = 'LUNAS';
            } else {
                var status = 'PROSES';
            }
        }

        if(id_carabayar == 1) {
            if (parseInt(dp_bayar1x) < parseInt(ttl_trkasir1x)) {
                alert('Input Nominal Bayar Lebih besar dari harga');
            } else {

                $.ajax({

                    type: 'post',
                    url: "modul/mod_trkasir/aksi_trkasir.php",

                    data: {
                        'id_trkasir': id_trkasir,
                        'kd_trkasir': kd_trkasir,
                        'tgl_trkasir': tgl_trkasir,
                        'petugas': petugas,
                        'nm_pelanggan': nm_pelanggan,
                        'tlp_pelanggan': tlp_pelanggan,
                        'alamat_pelanggan': alamat_pelanggan,
                        'ttl_trkasir': ttl_trkasir1x,
                        'diskon': diskon1x,
                        'diskon2': diskon2x,
                        'ttl_trkasir': ttl_trkasir1x,
                        'dp_bayar': dp_bayar1x,
                        'sisa_bayar': sisa_bayar1x,
                        'ket_trkasir': ket_trkasir,
                        'stt_aksi': stt_aksi,
                        'id_carabayar': id_carabayar,
                        'statusbayar': status
                    },
                    success: function (data) {
                        // window.open('modul/mod_laporan/struk.php?kd_trkasir='+kd_trkasir, 'nama window','width=400,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no');
                        alert('Proses berhasil !');
                        window.location = 'media_admin.php?module=penjualansebelumnya';

                    }

                    //	success: function(data) {

                    //	window.open('modul/mod_laporan/struk.php?kd_trkasir='+kd_trkasir, 'nama window','
                    //  	width=400,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,
                    //  	scrollbars=no,resizable=yes,copyhistory=no');
                    //	alert('Proses berhasil !');window.location='media_admin.php?module=trkasir';

                    //	}
                });
            }
        }
        else
        {
            $.ajax({

                type: 'post',
                url: "modul/mod_trkasir/aksi_trkasir2.php",

                data: {
                    'id_trkasir': id_trkasir,
                    'kd_trkasir': kd_trkasir,
                    'tgl_trkasir': tgl_trkasir,
                    'petugas': petugas,
                    'nm_pelanggan': nm_pelanggan,
                    'tlp_pelanggan': tlp_pelanggan,
                    'alamat_pelanggan': alamat_pelanggan,
                    'ttl_trkasir': ttl_trkasir1x,
                    'diskon': diskon1x,
                    'diskon2': diskon2x,
                    'ttl_trkasir': ttl_trkasir1x,
                    'dp_bayar': dp_bayar1x,
                    'sisa_bayar': sisa_bayar1x,
                    'ket_trkasir': ket_trkasir,
                    'stt_aksi': stt_aksi,
                    'id_carabayar': id_carabayar,
                    'statusbayar': status
                },
                success: function (data) {
                    console.log(data);
                    // window.open('modul/mod_laporan/struk.php?kd_trkasir='+kd_trkasir, 'nama window','width=400,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no');
                    alert('Proses berhasil !');
                    window.location = 'media_admin.php?module=penjualansebelumnya';

                }

                //	success: function(data) {

                //	window.open('modul/mod_laporan/struk.php?kd_trkasir='+kd_trkasir, 'nama window','
                //  	width=400,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,
                //  	scrollbars=no,resizable=yes,copyhistory=no');
                //	alert('Proses berhasil !');window.location='media_admin.php?module=trkasir';

                //	}
            });
        }
    }


    function cetakstruk(){

        var kd_trkasir = document.getElementById('kd_trkasir').value;

        //window.open("modul/mod_laporan/struk.php?kd_trkasir="+kd_trkasir,"_blank");
        window.open('modul/mod_laporan/struk.php?kd_trkasir='+kd_trkasir, 'nama window','width=400,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no');

    }
    function cetakstrukresep(){

        var kd_trkasir = document.getElementById('kd_trkasir').value;

        //window.open("modul/mod_laporan/struk.php?kd_trkasir="+kd_trkasir,"_blank");
        window.open('modul/mod_laporan/strukresep.php?kd_trkasir='+kd_trkasir, 'nama window','width=400,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no');

    }


</script>