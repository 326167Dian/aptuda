<style>
.table-condensed {
font-size: 13px;
}

.table-akum {
font-size: 11px;
}

.judul-table{

text-align: center; 
font-weight: bold; 
font-size: 13px;
background-color: #008000;
color: white;

}

</style>
			<div class="box-body table-responsive">
					<table id="example9" class="table table-condensed table-bordered table-striped table-hover" >
						<thead>
							<tr class="judul-table">
								<th style="vertical-align: middle; background-color: #008000; text-align: center; ">No</th>
								<th style="vertical-align: middle; background-color: #008000; text-align: center; ">Jenis</th>
								<th style="vertical-align: middle; background-color: #008000; text-align: left; ">Kode Barang</th>
								<th style="vertical-align: middle; background-color: #008000; text-align: left; ">Nama Barang</th>
								<th style="vertical-align: middle; background-color: #008000; text-align: right; ">Qty</th>
								<th style="vertical-align: middle; background-color: #008000; text-align: center; ">Satuan</th>
								<th style="vertical-align: middle; background-color: #008000; text-align: right; ">Harga</th>
								<th style="vertical-align: middle; background-color: #008000; text-align: right; ">Total</th>
								<th style="vertical-align: middle; background-color: #008000; text-align: center; ">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						include "../../../configurasi/koneksi.php";
						include "../../../configurasi/fungsi_rupiah.php";
						
							$stt_aksi = $_POST['stt_aksi'];
						    $kd_trkasir = $_POST['kd_trkasir'];

							//AMBIL DATA UNTUK FOOTER
							$dfoot=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir 
							join carabayar on trkasir.id_carabayar = carabayar.id_carabayar
							WHERE trkasir.kd_trkasir='$kd_trkasir'");
							$rf=mysqli_fetch_array($dfoot);
		/**					$id_carabayar = $rf['id_carabayar'];
							$nm_carabayar = $rf['nm_carabayar'];
							$dp_bayar = format_rupiah($rf['dp_bayar']); **/
							$diskon = 0 ;
							$carabayar = $rf['id_carabayar'];

							   
							$sumprice=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT kd_trkasir, SUM(hrgttl_dtrkasir) as grandnya FROM trkasir_detail 
							WHERE kd_trkasir='$kd_trkasir'");
							$ttlprice=mysqli_fetch_array($sumprice);
				// 			$grandnya = format_rupiah($ttlprice['grandnya']);
							$grandnya = format_rupiah($ttlprice['grandnya'] - $rf['diskon2']);
				            
						
						       $noreq=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir_detail
							   WHERE trkasir_detail.kd_trkasir='$kd_trkasir'
							   ORDER BY trkasir_detail.id_dtrkasir ASC");
								$no=1;
								while ($r=mysqli_fetch_array($noreq)){
								$hrgjual_dtrkasir = format_rupiah($r['hrgjual_dtrkasir']);
								$hrgttl_dtrkasir = format_rupiah($r['hrgttl_dtrkasir']);
								$tipe = ($r['tipe']==1)?'<span class="btn btn-xs btn-success">Grosir</span>':'<span class="btn btn-xs btn-warning">Retail</span>';
								
								echo "<tr style='font-size: 13px;'>
											<td align=center>$no</td>           
											<td align=center>$tipe</td>           
											<td align=left>$r[kd_barang]</td>
											<td>$r[nmbrg_dtrkasir]</td>
											<td align=right>$r[qty_dtrkasir]</td>
											<td align=center>$r[sat_dtrkasir]</td>
											<td align=right>$hrgjual_dtrkasir</td>
											<td align=right>$hrgttl_dtrkasir</td>
											<td align=center>
											
											<button class='btn btn-xs btn-danger' id='hapusdetail' 
												data-id_dtrkasir='$r[id_dtrkasir]' data-id_barang='$r[id_barang]'>
												<i class='glyphicon glyphicon-remove'></i>
											</button>
												
											</td>
										</tr>";
									
								$no++;
								}
						echo "</tbody></table>
						
						<p>
						<legend class='scheduler-border'></legend>
							<div class='col-md-6'>	
								
								
										
										</p>
											<div class='buttons'>
												<button type='button' class='btn btn-primary right-block' onclick='simpan_transaksi();'>SIMPAN TRANSAKSI</button>
												&nbsp
												<input class='btn btn-danger' type='button' value=BATAL onclick=self.history.back()>
											</div>";

											if($stt_aksi == "input_trkasir"){
											}else{

											?>
                                                <div class="box box-primary box-solid table-responsive">
                                                    <table id="example10" class="table table-condensed table-bordered table-striped table-hover">
                                                        <thead>
                                                            <th>No.</th>
                                                            <th>Angsuran</th>
                                                            <th>Waktu</th>
                                                            <th>Petugas</th>
                                                            <th>Aksi</th>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                            $ttempo = mysqli_query($GLOBALS["___mysqli_ston"],"select * from trkasir_tempo where kd_trkasir='$kd_trkasir';");
                                                            $no=1;
                                                            while($ttt=mysqli_fetch_array($ttempo))
                                                            { $angsuran= format_rupiah($ttt['angsuran']);
                                                                echo"
                                                                <tr>
                                                                    <td>$no</td>
                                                                    <td style='text-align: right'>Rp. $angsuran</td>
                                                                    <td>$ttt[waktu]</td>
                                                                    <td>$ttt[petugas]</td>
                                                                    <td align=center>											
                                                                        <button class='btn btn-xs btn-danger' id='hapusdetail2' 
                                                                            data-id_tempo='$ttt[id_tempo]' data-kd_trkasir='$ttt[kd_trkasir]'>
                                                                            <i class='glyphicon glyphicon-remove'></i>
                                                                        </button>
                                                                    </td>  
                                                                </tr>
                                                                ";
                                                                $no++;
                                                                $jmlh[] = $ttt['angsuran'];
                                                            }
                                                            $jmlmsk = array_sum($jmlh);
                                                            $msk = format_rupiah($jmlmsk);
                                                            $sisa1=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT  sum(hrgttl_dtrkasir) as totaltempo FROM trkasir_detail
							                                       WHERE kd_trkasir='$kd_trkasir'");
                                                            $sisa2 = mysqli_fetch_array($sisa1);
                                                            $sisa3 = $sisa2['totaltempo']-$rf['diskon2'];
                                                            $sisa = $sisa3 - $jmlmsk;
                                                            $sisarp = format_rupiah($sisa);
                                                            $diskon = format_rupiah($rf['diskon2']);
                                                            echo"
                                                            <tr>
                                                                <td colspan='2'><strong>Total Masuk Rp. $msk</strong> </td>
                                                                <td colspan='2'><strong> Sisa Rp. $sisarp </strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='2'><strong>Diskon Rp. $diskon</strong> </td>
                                                            </tr>
                                                            ";
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php

											}
											echo"
								
								
							</div>
							
							
							<div class='col-lg-6'>	
								
								<div class='text-right'>
									<label class='col-sm-6 control-label'>SUB TOTAL</label>        		
									 <div class='col-sm-6'>
										<input type='text' name='ttl_trkasir' id='ttl_trkasir' value='$grandnya' class='form-control input-validation-error' style='font-size: 18px; color: #fff; font-weight: bold; text-align: right; background: #000000;' autocomplete='off'>
									 </div>
								</div>
								
								<div class='text-right'>
									<label class='col-sm-6 control-label'>DISKON (% & Nominal)</label>        		
									 <div class='col-sm-6'>
    									 <div class='btn-group btn-group-justified' role='group' aria-label='...'>
                                            <div class='btn-group' role='group'>
                                                <input type='text' name='diskon' id='diskon' value='' class='form-control'  style='font-size: 18px; color: #000000; font-weight: bold; text-align: right;' autocomplete='off'>
                                            </div>
                                            <div class='btn-group' role='group'>
                                                <input type='text' name='diskon2' id='diskon2' value='' class='form-control'  style='font-size: 18px; color: #000000; font-weight: bold; text-align: right;' autocomplete='off'>
                                            </div>
                                            <div class='btn-group' role='group'>
                                                <button type='button' class='btn btn-primary' id='diskon_enter'>Enter</button>
                                              </div>
                                        </div>
                                    </div>
								</div>			
													
								<div class='text-right'>
									<label class='col-sm-6 control-label'>JUMLAH BAYAR</label>        		
									 <div class='col-sm-6'>
										<div class='btn-group btn-group-justified' role='group' aria-label='...'>
                                          <div class='btn-group' role='group'>
                                            <input type='text' name='dp_bayar' id='dp_bayar' value='' class='form-control'  style='font-size: 18px; color: #000000; font-weight: bold; text-align: right;' autocomplete='off'>
									    
                                          </div>
                                          <div class='btn-group' role='group'>
                                            <button type='button' class='btn btn-primary' id='dp_bayar_enter'>Enter</button>
                                          </div>
                                        </div>
									 </div>	 
								</div>
								
								
								<div class='text-right'>
									<label class='col-sm-6 control-label'>JENIS BAYAR</label>        		
									 <div class='col-sm-6'>
										<select name='id_carabayar' id='id_carabayar' class='form-control' style='font-size: 13px; color: #000000; font-weight: bold;'>";
											 if($stt_aksi == "input_trkasir"){
											 
												 $tampil=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM carabayar ORDER BY id_carabayar ASC");
												 while($rk=mysqli_fetch_array($tampil)){
												 echo "<option value='$rk[id_carabayar]'>$rk[nm_carabayar]</option>";
												 }
												 
											 }else{
                                                 $datacarabayar=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM carabayar where id_carabayar='$carabayar'");
                                                 $tampilcarabayar = mysqli_fetch_array($datacarabayar);

												 echo"
												 
												 <option value='$tampilcarabayar[id_carabayar]'>$tampilcarabayar[nm_carabayar]</option>
												 ";
												 $tampil=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM carabayar ORDER BY id_carabayar ASC");
												 while($rk=mysqli_fetch_array($tampil)){
												 echo "<option value='$rk[id_carabayar]'>$rk[nm_carabayar]</option>";
												 }
												
											 //   $statusbayar = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir WHERE kd_trkasir = '$kd_trkasir'");
											 //   $stb = mysqli_fetch_array($statusbayar);
											    
											 //   echo "<option value='$stb[statusbayar]'>$stb[statusbayar]</option>
											 //   <option value='PROSES'>PROSES</option>
											 //   <option value='LUNAS'>LUNAS</option>";
											 }
											 
										echo "
										</select>
									 </div>
							  </div>";
								
								     if($stt_aksi == "ubah_trkasir"):
								echo "
								<div class='text-right'>
									<label class='col-sm-6 control-label'>Status Bayar</label>        		
									 <div class='col-sm-6'>";
									  
										    $statusbayar = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir WHERE kd_trkasir = '$kd_trkasir'");
										    $stb = mysqli_fetch_array($statusbayar);
										    
										    echo "<select name='statusbayar' id='statusbayar' class='form-control' style='font-size: 13px; color: #000000; font-weight: bold;'>
										            <option value='$stb[statusbayar]'>$stb[statusbayar]</option>
										            <option value='PROSES'>PROSES</option>
											        <option value='LUNAS'>LUNAS</option>
										        </select>";
								echo "		
									 </div>
								</div>";
									endif;
								
								echo "
								<div class='text-right'>
									<label class='col-sm-6 control-label'>KEMBALIAN</label>        		
									 <div class='col-sm-6'>
										<input type='text' name='sisa_bayar' id='sisa_bayar' class='form-control' style='font-size: 18px; color: #fff; font-weight: bold; text-align: right; background: #000000;' autocomplete='off' readonly>
									 </div>
								</div>
								
							</div>
						      
							  </div>";
							  ?>
<script>


$(document).ready(function () {
        HitungOngkirDanDP();
        hitungdiskon();
        hitungdiskon2();
        $('#example9').DataTable();
    });

		
		
		//hitung dp
		$('#dp_bayar').keydown(function(e) {
		if (e.which == 13) { // e.which == 13 merupakan kode yang mendeteksi ketika anda   // menekan tombol enter di keyboard
			//letakan fungsi anda disini
   
			HitungOngkirDanDP();
				
		}
		});
		
		$('#dp_bayar_enter').on('click', function(e){
		    HitungOngkirDanDP();
		})

		//hitung diskon
		$('#diskon').keydown(function(e) {
		if (e.which == 13) { // e.which == 13 merupakan kode yang mendeteksi ketika anda   // menekan tombol enter di keyboard
			//letakan fungsi anda disini

			hitungdiskon();

		}
		});

        $('#diskon').keyup(function(e) {
    		
    		//letakan fungsi anda disini
                
    		var diskon = document.getElementById('diskon').value;
    		if(diskon.includes(',')){
    	        var diskon1 = diskon.replace(/\,/g, "");
    			document.getElementById('diskon').value = diskon1;
    			    
    		} else
    		if(diskon.includes('.')){
    		    var diskon1 = diskon.replace(/\./g, "");
    			document.getElementById('diskon').value = diskon1;
    		}
		});
		
		//hitung diskon
		$('#diskon2').keydown(function(e) {
    		if (e.which == 13) { // e.which == 13 merupakan kode yang mendeteksi ketika anda   // menekan tombol enter di keyboard
    			//letakan fungsi anda disini
    
    			hitungdiskon2();
    
    		}
		});
		
		$('#diskon_enter').on('click', function(){
		    let diskon = $('#diskon').val();
		    let diskon2 = $('#diskon2').val();
		    
		    if(diskon > 0 && diskon2 == 0){
    		    hitungdiskon();
    		    $('#diskon').attr('disabled', true);
                $('#diskon2').attr('disabled', true);
		    } else if(diskon == 0 && diskon2 > 0){
		        hitungdiskon2();
		        $('#diskon').attr('disabled', true);
                $('#diskon2').attr('disabled', true);
		    } else {
		        alert('Hanya dibolehkan 1 opsi diskon !!!')
		    }
		})
		
		//rubah format rupiah
			function formatRupiah(angka){
			 var reverse = angka.toString().split('').reverse().join(''),
			 ribuan = reverse.match(/\d{1,3}/g);
			 ribuan = ribuan.join('.').split('').reverse().join('');
			 return ribuan;
			}

			
			
	function HitungOngkirDanDP(){
	
			var ttl_trkasir = document.getElementById('ttl_trkasir').value;
			var dp_bayar = document.getElementById('dp_bayar').value;
			
			if(ttl_trkasir == ""){
			var ttl_trkasir = "0";
			}else{
			}
			
			if(dp_bayar == ""){
			var dp_bayar = "0";
			}else{
			}

					var res1 = ttl_trkasir.replace(".", "");
					var res3 = dp_bayar.replace(".", "");

					var res1x = res1.replace(".", "");
					var res3x = res3.replace(".", "");
					
			var total2 = parseInt(res1x) - parseInt(res3x);

			document.getElementById("dp_bayar").value = formatRupiah(dp_bayar);
			document.getElementById("sisa_bayar").value = formatRupiah(total2);
	
	}

	function hitungdiskon(){

        var ttl_trkasir = document.getElementById('ttl_trkasir').value;
        var diskon = document.getElementById('diskon').value;

        if(diskon == ""){
            var diskon = "0";
        }else{
        }

        var res1 = ttl_trkasir.replace(".", "");
        var res4 = diskon.replace(".", "");

        var res1x = res1.replace(".", "");
        var res4x = res4.replace(".", "");

        var total5 = parseInt(res1x) * ( 1- (parseInt(res4x)/100));

        document.getElementById("diskon").value = formatRupiah(diskon);
        document.getElementById("ttl_trkasir").value = formatRupiah(total5);


	}

	function hitungdiskon2(){

        var ttl_trkasir = document.getElementById('ttl_trkasir').value;
        var diskon2 = document.getElementById('diskon2').value;

        if(diskon2 == ""){
            var diskon2 = "0";
        }else{
        }

        var res1 = ttl_trkasir.replace(".", "");
        var res2 = diskon2.replace(".", "");

        var res1x = res1.replace(".", "");
        var res2x = res2.replace(".", "");

        var total6 = parseInt(res1x) - parseInt(res2x);

        document.getElementById("diskon2").value = formatRupiah(diskon2);
        document.getElementById("ttl_trkasir").value = formatRupiah(total6);


	}
	
</script>

