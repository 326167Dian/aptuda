<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href=../css/style.css rel=stylesheet type=text/css>";
  echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_supplier/aksi_supplier.php";
$aksi_supplier = "masuk/modul/mod_supplier/aksi_supplier.php";
switch($_GET[act]){
  // Tampil Siswa
  default:

  
      $tampil_supplier = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM supplier ORDER BY id_supplier ASC");
      
	  ?>
			
			
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">DATA SUPPLIER</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body">
					<a  class ='btn  btn-success btn-flat' href='?module=supplier&act=tambah'>TAMBAH</a>
					<br><br>
					
					
					<table id="example1" class="table table-bordered table-striped" >
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Supplier</th>
								<th>Telp</th>
								<th>Alamat</th>
								<th>Keterangan</th>
								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php 
								$no=1;
								while ($r=mysqli_fetch_array($tampil_supplier)){
									echo "<tr class='warnabaris' >
											<td>$no</td>           
											 <td>$r[nm_supplier]</td>
											 <td>$r[tlp_supplier]</td>
											 <td>$r[alamat_supplier]</td>
											 <td>$r[ket_supplier]</td>
											 <td><a href='?module=supplier&act=edit&id=$r[id_supplier]' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 
											 <a href=javascript:confirmdelete('$aksi?module=supplier&act=hapus&id=$r[id_supplier]') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
											 
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
				
						<form method=POST action='$aksi?module=supplier&act=input_supplier' enctype='multipart/form-data' class='form-horizontal'>
						
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Supplier</label>        		
									 <div class='col-sm-4'>
										<input type=text name='nm_supplier' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Telp</label>        		
									 <div class='col-sm-4'>
										<input type=text name='tlp_supplier' class='form-control' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-4'>
										<textarea name='alamat_supplier' class='form-control' rows='3'></textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan</label>        		
									 <div class='col-sm-4'>
										<textarea name='ket_supplier' class='form-control' rows='3'></textarea>
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

  case "edit":
    $edit=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM supplier WHERE id_supplier='$_GET[id]'");
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
						<form method=POST method=POST action=$aksi?module=supplier&act=update_supplier  enctype='multipart/form-data' class='form-horizontal'>
							  <input type=hidden name=id value='$r[id_supplier]'>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Supplier</label>        		
									 <div class='col-sm-4'>
										<input type=text name='nm_supplier' class='form-control' value='$r[nm_supplier]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Telepon</label>        		
									 <div class='col-sm-4'>
										<input type=text name='tlp_supplier' class='form-control' value='$r[tlp_supplier]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-4'>
										<textarea name='alamat_supplier' class='form-control' rows='3'>$r[alamat_supplier]</textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan</label>        		
									 <div class='col-sm-4'>
										<textarea name='ket_supplier' class='form-control' rows='3'>$r[ket_supplier]</textarea>
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


}
}
?>