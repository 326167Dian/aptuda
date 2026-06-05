<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href=../css/style.css rel=stylesheet type=text/css>";
  echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_pelanggan/aksi_pelanggan.php";
$aksi_pelanggan = "masuk/modul/mod_pelanggan/aksi_pelanggan.php";
switch($_GET[act]){
  // Tampil Siswa
  default:

  
      $tampil_pelanggan = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM pelanggan ORDER BY id_pelanggan ASC");
      
	  ?>
			
			
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">DATA PELANGGAN</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body">
				    <?php if($_SESSION['username']):?>
					<a  class ='btn  btn-success btn-flat' href='?module=pelanggan&act=tambah'>TAMBAH</a>
					<?php endif;?>
					<br><br>
					
					
					<table id="example1" class="table table-bordered table-striped" >
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Pelanggan</th>
								<th>Telepon</th>
								<th>Alamat</th>
								<th>Faktur Jatuh Tempo</th>
								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php 
								$no=1;
								while ($r=mysqli_fetch_array($tampil_pelanggan)){
								     $tgl_awal = date('Y-m-d');
                                     $tgl_akhir = date('Y-m-d', strtotime('-30 days', strtotime( $tgl_awal)));
                				    //cek hutang
                                     $hutang = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(id_trkasir) as jml FROM trkasir WHERE nm_pelanggan = '$r[nm_pelanggan]'        and tgl_trkasir <= '$tgl_akhir' AND statusbayar = 'PROSES' ");
                                     $jumlah = mysqli_fetch_array($hutang);
								    
									echo "<tr class='warnabaris' >
											<td>$no</td>           
											 <td><a href='?module=pelanggan&act=tampil&id=$r[nm_pelanggan]' title='pelanggan'> $r[nm_pelanggan]</a></td>
											 <td>$r[tlp_pelanggan]</td>
											 <td>$r[alamat_pelanggan]</td>
											 <td style='text-align:center;' >$jumlah[jml]</td>
											 <td><a href='?module=pelanggan&act=edit&id=$r[id_pelanggan]' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 
											 <a href=javascript:confirmdelete('$aksi?module=pelanggan&act=hapus&id=$r[id_pelanggan]') title='HAPUS' class='btn btn-primary btn-xs'>HAPUS</a>
											 
											</td>
										</tr>";
								$no++;
								}
						echo "</tbody></table>";
					?>
				</div>
			</div>	
             

<?php
    
    break;
	
	case "tambah":
       
        echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form method=POST action='$aksi?module=pelanggan&act=input_pelanggan' enctype='multipart/form-data' class='form-horizontal'>
						
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Pelanggan</label>        		
									 <div class='col-sm-4'>
										<input type=text name='nm_pelanggan' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Telepon</label>        		
									 <div class='col-sm-4'>
										<input type=text name='tlp_pelanggan' class='form-control' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-4'>
										<textarea name='alamat_pelanggan' class='form-control' rows='3'></textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan</label>        		
									 <div class='col-sm-4'>
										<textarea name='ket_pelanggan' class='form-control' rows='3'></textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-info' type=submit value=SIMPAN>
											<input class='btn btn-primary' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>";
					
	
    break;

  case "edit":
    $edit=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM pelanggan WHERE id_pelanggan='$_GET[id]'");
    $r=mysqli_fetch_array($edit);
			
		echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST method=POST action=$aksi?module=pelanggan&act=update_pelanggan  enctype='multipart/form-data' class='form-horizontal'>
							  <input type=hidden name=id value='$r[id_pelanggan]'>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Pelanggan</label>        		
									 <div class='col-sm-4'>
										<input type=text name='nm_pelanggan' class='form-control' value='$r[nm_pelanggan]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Telepon</label>        		
									 <div class='col-sm-4'>
										<input type=text name='tlp_pelanggan' class='form-control' value='$r[tlp_pelanggan]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-4'>
										<textarea name='alamat_pelanggan' class='form-control' rows='3'>$r[alamat_pelanggan]</textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan</label>        		
									 <div class='col-sm-4'>
										<textarea name='ket_pelanggan' class='form-control' rows='3'>$r[ket_pelanggan]</textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-primary' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>";	
	

 
    
    break;
    
    case "tampil" :
    
    $pelanggan = $_GET[id];
    
    $tampil_faktur = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir WHERE nm_pelanggan='$pelanggan' ");
    
    ?>
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">DATA FAKTUR <?= $pelanggan ?></h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body">
					
					
					<table id="example2" class="table table-bordered table-striped" >
						<thead>
							<tr>
								<th>No</th>
								<th>Kode Faktur </th>
								<th>Tanggal Faktur</th>
								<th>Nilai Faktur</th>
								<th>STATUS BAYAR</th>
							</tr>
						</thead>
						<tbody>
						<?php 
								$no=1;
								while ($r=mysqli_fetch_array($tampil_faktur)){
								   
								    $nilai_tx = format_rupiah($r[nilai_transaksi]);
									echo "<tr class='warnabaris' >
											<td>$no</td>           
											 <td> $r[kd_trkasir]</a></td>
											 <td>$r[tgl_trkasir]</td>
											 <td style='text-align:right;'>$nilai_tx</td>
											";  if ($r[statusbayar]== 'PROSES'){
											 echo"<td style='text-align:center; background-color:#FF4500;' >$r[statusbayar]</td> ";
											} else {echo"<td style='text-align:center;' >$r[statusbayar]</td> "; }
											echo"
										</tr>";
								$no++;
								}
						echo "</tbody></table>";
					?>
				</div>
			</div>	
	<?php		
	
    break ;


}
}
?>