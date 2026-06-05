<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href=../css/style.css rel=stylesheet type=text/css>";
  echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_catatan/aksi_catatan.php";
$aksi2="modul/mod_catatan/aksi_jenistransaksi.php";

switch($_GET[act]){
  // tampil catatan
  default:

      $tgl = date('Y-m-d');
      $tampiljurnal = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jurnal where tanggal ='$tgl' order by id_jurnal desc");
      
	  ?>
			
			
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">JURNAL HARI INI </h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body">
					<a  class ='btn  btn-danger btn-flat' href='?module=catatan&act=tambah'>Input Pengeluaran</a>
					<a  class ='btn  btn-info btn-flat' href='?module=catatan&act=tambah2'>Input Pemasukan</a>
					<a  class ='btn  btn-warning btn-flat' href='?module=catatan&act=jenistransaksi'>Jenis Transaksi</a>
					<a  class ='btn  btn-success btn-flat' href='?module=catatan&act=pilihhari'>Pilih Hari</a>
                    <?php
                    $lupa = $_SESSION['level'];
                    if ($lupa == 'pemilik') {
                    echo " <a  class ='btn  btn-primary btn-flat' href='?module=catatan&act=kemarin'>Catatan Kemarin</a>
                           <a  class ='btn  btn-success btn-flat' href='?module=catatan&act=rekap'>Rekapitulasi</a>
                    ";
                    }

					?>
					<br><br>
					
					
					<table id="example1" class="table table-bordered table-striped" >
						<thead style="text-align: center;>
							<tr">
								<th>No</th>
								<th>tanggal</th>
								<th>keterangan</th>
                                <th>Petugas</th>
                                <th>jenis transaksi</th>
                                <th>cash / Transfer</th>
                                <th>debit</th>
                                <th>Kredit</th>
								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php 
								$no=1;
								while ($r=mysqli_fetch_array($tampiljurnal)){
								    $tipe = $db->query("select * from jenis_jurnal where idjenis='$r[idjenis]' ");
								    $show = $tipe->fetch_array();
                                    $debit = format_rupiah($r['debit']);
                                    $kredit = format_rupiah($r['kredit']);

									echo "<tr class='warnabaris' >
											<td>$no</td>           
											 <td>$r[tanggal]</td>
											 <td>$r[ket]</td>
											 <td>$r[petugas]</td>
											 <td>$show[nm_jurnal]</td>
											 <td style='text-align: center;'>$r[carabayar]</td>
											 <td style='text-align: right;'>Rp. $debit</td>											 
											 <td style='text-align: right;'>Rp. $kredit</td>											 
											 <td>
											 <a href='?module=catatan&act=edit&id=$r[id_jurnal]' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 									 
										     <a href=javascript:confirmdelete('$aksi?module=catatan&act=hapus&id=$r[id_jurnal]') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
											</td>
										</tr>";
								$no++;
								}
						echo "</tbody>
                        <tfoot>
                            ";
							$kaki = $db->query("select sum(debit) as debt,sum(kredit) as kre from jurnal where tanggal ='$tgl' ");
							$kk = $kaki->fetch_array();
                            $akhir = format_rupiah($kk['debt']);
                            $akhir2 = format_rupiah($kk['kre']);
                            $akhir3 = format_rupiah($kk['kre'] - $kk['debt']);

                            $kanan = $db->query("select sum(debit) as kn from jurnal where tanggal='$tgl' and carabayar = 'TUNAI'");
                            $kanan2 = $kanan->fetch_array();
                            $kanan3 = format_rupiah($kanan2['kn']);

                            $kiri = $db->query("select sum(debit) as kk from jurnal where tanggal='$tgl' and carabayar = 'TRANSFER'");
                            $kiri2 = $kiri->fetch_array();
                            $kiri3 = format_rupiah($kiri2['kk']);

                            $atas = $db->query("select sum(kredit) as kn from jurnal where tanggal='$tgl' and carabayar = 'TUNAI'");
                            $atas2 = $atas->fetch_array();
                            $atas3 = format_rupiah($atas2['kn']);

                            $bawah = $db->query("select sum(kredit) as kn from jurnal where tanggal='$tgl' and carabayar = 'TRANSFER'");
                            $bawah2 = $bawah->fetch_array();
                            $bawah3 = format_rupiah($bawah2['kn']);

                            $saldotunai = format_rupiah($atas2['kn'] - $kanan2['kn']);
                            $saldotransfer = format_rupiah($bawah2['kn'] - $kiri2['kk']);

							echo "	
                            <tr style='font-weight: bold; background-color: #DB7093;text-align: center; font-size:large;' >
                            <td colspan='6'>Pengeluaran Tunai Rp. $kanan3 dan Transfer Rp. $kiri3 </td><td>Total</td><td colspan='2'>Rp &nbsp;&nbsp; $akhir</td>
                            </tr>
                            <tr style='font-weight: bold; background-color: #98FB98;text-align: center; font-size:large;' >
                            <td colspan='6'>Pemasukan Tunai Rp. $atas3 dan Transfer Rp. $bawah3</td><td>Total</td><td colspan='2'>Rp &nbsp;&nbsp; $akhir2</td>
                            </tr>
                            <tr style='font-weight: bold; background-color: #87CEEB;text-align: center; font-size:large;' >
                            <td colspan='6'>Saldo Tunai Rp. $saldotunai dan Saldo Transfer Rp. $saldotransfer </td><td>Saldo</td><td colspan='2'>Rp &nbsp;&nbsp;$akhir3</td>
                            </tr>
                        </tfoot>
</table>";
					?>
				</div>
                
			</div>

             

<?php
    
    break;
	
	case "tambah":

        echo "
		  <div class='box box-danger box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH PENGELUARAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				<a  class ='btn  btn-danger btn-flat' href='?module=catatan&act=tambah'>Input Pengeluaran</a>
					<a  class ='btn  btn-info btn-flat' href='?module=catatan&act=tambah2'>Input Pemasukan</a>
					<a  class ='btn  btn-warning btn-flat' href='?module=catatan&act=jenistransaksi'>Jenis Transaksi</a>
					<a  class ='btn  btn-success btn-flat' href='?module=catatan&act=pilihhari'>Pilih Hari</a>
                    ";
                    $lupa = $_SESSION['level'];
                    if ($lupa == 'pemilik') {
                    echo " <a  class ='btn  btn-primary btn-flat' href='?module=catatan&act=kemarin'>Catatan Kemarin</a>
                    ";
                    }

					echo"
					<br><br>
				
						<form method=POST action='$aksi?module=catatan&act=input_jurnal' enctype='multipart/form-data' class='form-horizontal'>
						
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Jenis Transaksi</label>        		
									 <div class='col-sm-6'>
										<select name='idjenis' class='form-control' >";
                            			$tampil = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jenis_jurnal where tipe = 1");
                            			while ($rk = mysqli_fetch_array($tampil)) {
                            				echo "<option value=$rk[idjenis]>$rk[nm_jurnal]</option>";
                            			}
                            			echo "
                            			</select>
									 </div>
							  </div> 
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan Detail</label>        		
									 <div class='col-sm-6'>
										<input type=text name='ket' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Cara Bayar</label>        		
									 <div class='col-sm-6'>
										<select name='carabayar' class='form-control' required='required' >
											<option value='TUNAI'>TUNAI  </option>
											<option value='TRANSFER'>TRANSFER  </option>									
										</select>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nilai Transaksi </label>        		
									 <div class='col-sm-6'>
										<input type=number name='debit' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>";
					
	
    break;

  case "edit":
      $kode = $_GET['id'];
      $edit = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jurnal where id_jurnal = '$kode' ");
      $apt = mysqli_fetch_array($edit);
      echo "
		  <div class='box box-warning box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>EDIT JURNAL</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form method=POST action='$aksi?module=catatan&act=update_jurnal' enctype='multipart/form-data' class='form-horizontal'>
						  <input type=hidden name=id value='$apt[id_jurnal]'>
						
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Jenis Transaksi</label>        		
									 <div class='col-sm-6'>
										<select name='idjenis' class='form-control' >
										"; $tampol = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jenis_jurnal where idjenis='$apt[idjenis]' ");
										   $tmp = mysqli_fetch_array($tampol);
										   echo "
                                           <option  value=$tmp[idjenis] selected>$tmp[nm_jurnal]</option>";
                                          $tampil = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jenis_jurnal");
                                          while ($rk = mysqli_fetch_array($tampil)) {
                                              echo "<option value=$rk[idjenis]>$rk[nm_jurnal]</option>";
                                          }
                                          echo "
                            			</select>
									 </div>
							  </div> 
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan Detail</label>        		
									 <div class='col-sm-6'>
										<input type=text name='ket' class='form-control' required='required' value='$apt[ket]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Cara Bayar</label>        		
									 <div class='col-sm-6'>
										<select name='carabayar' class='form-control' required='required' >
										";
                                        $kas1 = $db->query("select * from jurnal where id_jurnal=$kode ");
                                        $kas2 = $kas1->fetch_array();
                                        $kas3 = $kas2['carabayar'];
                                        echo "<option>$kas3</option>" ;
										echo"<option value='TUNAI'>TUNAI  </option>
											 <option value='TRANSFER'>TRANSFER  </option>											
										</select>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nilai Pengeluaran </label>        		
									 <div class='col-sm-6'>
										<input type=number name='debit' class='form-control' required='required' value='$apt[debit]' autocomplete='off'>
									 </div>
							  </div>
							  						  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nilai Pemasukan </label>        		
									 <div class='col-sm-6'>
										<input type=number name='kredit' class='form-control' required='required' value='$apt[kredit]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>";
	
    break;
    case "tampil" :
        $edit=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM catatan WHERE id_catatan='$_GET[id]'");
        $r=mysqli_fetch_array($edit);
        echo "$r[deskripsi]
        <input class='btn btn-primary' type=button value=KEMBALI onclick=self.history.back()>";
    break ;

    case "jenistransaksi" :

        $tampiljenis = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jenis_jurnal ");
    ?>
        <div class="box box-warning box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">JENIS TRANSAKSI JURNAL </h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body">
					<a  class ='btn  btn-warning btn-flat' href='?module=catatan&act=add'>Tambah Jenis Transaksi</a><br><br>
					<a  class ='btn  btn-danger btn-flat' href='?module=catatan&act=tambah'>Input Pengeluaran</a>
					<a  class ='btn  btn-info btn-flat' href='?module=catatan&act=tambah2'>Input Pemasukan</a>
					<a  class ='btn  btn-warning btn-flat' href='?module=catatan&act=jenistransaksi'>Jenis Transaksi</a>
					<a  class ='btn  btn-success btn-flat' href='?module=catatan&act=pilihhari'>Pilih Hari</a>
                    <?php
                    $lupa = $_SESSION['level'];
                    if ($lupa == 'pemilik') {
                    echo " <a  class ='btn  btn-primary btn-flat' href='?module=catatan&act=kemarin'>Catatan Kemarin</a>
                    ";
                    }

					?>
					<br><br>

					<br><br>


					<table id="example1" class="table table-bordered table-striped" >
						<thead>
							<tr>
								<th>No</th>
								<th>ID Transaksi</th>
								<th>Nama Transaksi</th>
								<th >Tipe Transaksi</th>
								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php
								$no=1;
								while ($r=mysqli_fetch_array($tampiljenis)){
                                    if($r['tipe']==1)
                                    {$tipe = 'KELUAR';}
                                    elseif ($r['tipe']==2)
                                    {$tipe = 'MASUK';}
									echo "<tr class='warnabaris' >
											<td>$no</td>           
											 <td>$r[idjenis]</td>
											 <td>$r[nm_jurnal]</td>
											 <td style='text-align: center;'>$tipe</td>
											 
											 <td>
											 <a href='?module=catatan&act=ubah&id=$r[idjenis]' title='UBAH' class='btn btn-warning btn-xs'>UBAH</a> 											
										     <a href=javascript:confirmdelete('$aksi2?module=catatan&act=hapus&id=$r[idjenis]') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
											</td>
										</tr>";
								$no++;
								}
						echo "</tbody></table>";
					?>
				</div>

			</div>
    <?php
    echo "<input class='btn btn-primary' type=button value=KEMBALI onclick=self.history.back()>";
    break ;
    case "add" :
        echo "
		  <div class='box box-warning box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH JENIS TRANSAKSI</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form method=POST action='$aksi2?module=catatan&act=input_jenistransaksi' enctype='multipart/form-data' class='form-horizontal'>
						
						      <div class='form-group'>
									<label class='col-sm-2 control-label'>Arus Kas</label>        		
									 <div class='col-sm-6'>
										<select name='tipe' class='form-control' required='required' >
											<option value='1'>KELUAR KAS </option>
											<option value='2'>MASUK KAS </option>											
										</select>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Transaksi</label>        		
									 <div class='col-sm-6'>
										<input type=text name='nm_jurnal' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>
        <input class='btn btn-primary' type=button value=KEMBALI onclick=self.history.back()>";
    break ;

    case "ubah" :

        $ubah=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jenis_jurnal WHERE idjenis='$_GET[id]'");
        $r=mysqli_fetch_array($ubah);
        echo "
            $kode
             <div class='box box-warning box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH TRANSAKSI</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST method=POST action=$aksi2?module=catatan&act=update_jenistransaksi  enctype='multipart/form-data' class='form-horizontal'>
							  <input type=hidden name=id value='$r[idjenis]'>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Transaksi</label>        		
									 <div class='col-sm-6'>
										<input type=text name='nm_jurnal' class='form-control' value='$r[nm_jurnal]' autocomplete='off'>
									 </div>
							  </div>
							  
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div> 
        <input class='btn btn-primary' type=button value=KEMBALI onclick=self.history.back()>";
    break ;
    case "kemarin" :

        $tgl_awal = date('Y-m-d');
        $tgl_kemarin = date('Y-m-d', strtotime('-1 days', strtotime( $tgl_awal)));
        $tgl_akhir = date('Y-m-d', strtotime('-180 days', strtotime( $tgl_awal)));
        $tampiljurnal = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jurnal where tanggal between '$tgl_akhir' and '$tgl_kemarin' order by id_jurnal desc");

        ?>
                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">JURNAL PENGELUARAN KEMARIN </h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                    <a  class ='btn  btn-danger btn-flat' href='?module=catatan&act=tambah'>Input Pengeluaran</a>
					<a  class ='btn  btn-info btn-flat' href='?module=catatan&act=tambah2'>Input Pemasukan</a>
					<a  class ='btn  btn-warning btn-flat' href='?module=catatan&act=jenistransaksi'>Jenis Transaksi</a>
					<a  class ='btn  btn-success btn-flat' href='?module=catatan&act=pilihhari'>Pilih Hari</a>
                    <?php
                    $lupa = $_SESSION['level'];
                    if ($lupa == 'pemilik') {
                    echo " <a  class ='btn  btn-primary btn-flat' href='?module=catatan&act=kemarin'>Catatan Kemarin</a>
                    ";
                    }

					?>
					<br><br>
                        <br><br>


                        <table id="example1" class="table table-bordered table-striped" >
                            <thead style="text-align: center;>
                                            <tr">
                            <th>No</th>
                            <th>tanggal</th>
                            <th>keterangan</th>
                            <th>Petugas</th>
                            <th>jenis transaksi</th>
                            <th>debit</th>
                            <th>kredit</th>
                            <th width="70">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                                $no=1;
                                                while ($r=mysqli_fetch_array($tampiljurnal)){
                                                    $tipe = $db->query("select * from jenis_jurnal where idjenis='$r[idjenis]' ");
                                                    $show = $tipe->fetch_array();
                                                    $debit = format_rupiah($r['debit']);
                                                    $kredit = format_rupiah($r['kredit']);
                                                    echo "<tr class='warnabaris' >
                                                            <td>$no</td>           
                                                             <td>$r[tanggal]</td>
                                                             <td>$r[ket]</td>
                                                             <td>$r[petugas]</td>
                                                             <td>$show[nm_jurnal]</td>
                                                             <td style='text-align: right;'>Rp. $debit</td>											 
                                                             <td style='text-align: right;'>Rp. $kredit</td>											 
                                                             <td>
                                                             <a href='?module=catatan&act=edit&id=$r[id_jurnal]' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 									 
                                                             <a href=javascript:confirmdelete('$aksi?module=catatan&act=hapus&id=$r[id_jurnal]') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
                                                            </td>
                                                        </tr>";
                                                $no++;
                                                }
                                        echo "</tbody>
                                        
                        </table>
                        <a  class ='btn  btn-danger' href='?module=catatan'>KEMBALI</a>
                        ";
                                            ?>

<?php
        break ;
case "tambah2" :

    echo "
		  <div class='box box-info box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>INPUT PEMASUKAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				<a  class ='btn  btn-danger btn-flat' href='?module=catatan&act=tambah'>Input Pengeluaran</a>
					<a  class ='btn  btn-info btn-flat' href='?module=catatan&act=tambah2'>Input Pemasukan</a>
					<a  class ='btn  btn-warning btn-flat' href='?module=catatan&act=jenistransaksi'>Jenis Transaksi</a>
					<a  class ='btn  btn-success btn-flat' href='?module=catatan&act=pilihhari'>Pilih Hari</a>
                    ";
                    $lupa = $_SESSION['level'];
                    if ($lupa == 'pemilik') {
                    echo " <a  class ='btn  btn-primary btn-flat' href='?module=catatan&act=kemarin'>Catatan Kemarin</a>
                    ";
                    }

				echo"
					<br><br>
						<form method=POST action='$aksi?module=catatan&act=input_jurnal2' enctype='multipart/form-data' class='form-horizontal'>
						
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Jenis Transaksi</label>        		
									 <div class='col-sm-6'>
										<select name='idjenis' class='form-control' >";
                                        $tampil = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jenis_jurnal where tipe = 2");
                                        while ($rk = mysqli_fetch_array($tampil)) {
                                            echo "<option value=$rk[idjenis]>$rk[nm_jurnal]</option>";
                                        }
                                        echo "
                            			</select>
									 </div>
							  </div> 
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan Detail</label>        		
									 <div class='col-sm-6'>
										<input type=text name='ket' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Cara Bayar</label>        		
									 <div class='col-sm-6'>
										<select name='carabayar' class='form-control' required='required' >
											<option value='TUNAI'>TUNAI  </option>
											<option value='TRANSFER'>TRANSFER  </option>									
										</select>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nilai Transaksi </label>        		
									 <div class='col-sm-6'>
										<input type=number name='kredit' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>";

    break ;

case "pilihhari" :
    ?>


    <div class="box box-success box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">LAPORAN KAS</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box-tools
                    -->
        </div>
        <div class="box-body">
                    <a  class ='btn  btn-danger btn-flat' href='?module=catatan&act=tambah'>Input Pengeluaran</a>
					<a  class ='btn  btn-info btn-flat' href='?module=catatan&act=tambah2'>Input Pemasukan</a>
					<a  class ='btn  btn-warning btn-flat' href='?module=catatan&act=jenistransaksi'>Jenis Transaksi</a>
					<a  class ='btn  btn-success btn-flat' href='?module=catatan&act=pilihhari'>Pilih Hari</a>
                    <?php
                    $lupa = $_SESSION['level'];
                    if ($lupa == 'pemilik') {
                    echo " <a  class ='btn  btn-primary btn-flat' href='?module=catatan&act=kemarin'>Catatan Kemarin</a>
                    ";
                    }

					?>
					<br><br>
            <form method="POST" action="?module=catatan&act=tampil2"  enctype="multipart/form-data" class="form-horizontal">

                </br></br>


                <div class="form-group">
                    <label class="col-sm-2 control-label">Tanggal Awal</label>
                    <div class="col-sm-4">
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                            <input type="text" required="required" class="datepicker" id="tgl_awal" name="tgl_awal" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Tanggal Akhir</label>
                    <div class="col-sm-4">
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                            <input type="text" required="required" class="datepicker" id="tgl_akhir" name="tgl_akhir" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="buttons col-sm-4">
                        <input class="btn btn-primary" type="submit" name="btn" value="TAMPIL">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                        <a  class ='btn  btn-danger' href='?module=catatan'>KEMBALI</a>
                    </div>
                </div>

            </form>
        </div>

    </div>


    <?php
    break ;
    case "tampil2" :
        $tgl_awal = $_POST['tgl_awal'];
        $tgl_akhir = $_POST['tgl_akhir'];

        $tampiljurnal1 = mysqli_query($GLOBALS["___mysqli_ston"],
                 "SELECT * FROM jurnal where tanggal between '$tgl_awal' and '$tgl_akhir'order by id_jurnal desc");
?>


                            <div class="box box-primary box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">JURNAL PILIHAN TANGGAL <?= $tgl_awal ?> DAN <?= $tgl_akhir ?></h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /.box-tools -->
                                </div>
                                <div class="box-body">

                                    <br><br>


                                    <table id="example2" class="table table-bordered table-striped" >
                                        <thead style="text-align: center;>
							<tr">
                                        <th>No</th>
                                        <th>tanggal</th>
                                        <th>keterangan</th>
                                        <th>Petugas</th>
                                        <th>jenis transaksi</th>
                                        <th>Cash / Transfer</th>
                                        <th>debit</th>
                                        <th>Kredit</th>
                                        <th width="70">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $no=1;
                                        while ($r=mysqli_fetch_array($tampiljurnal1)){
                                            $tipe = $db->query("select * from jenis_jurnal where idjenis='$r[idjenis]' ");
                                            $show = $tipe->fetch_array();
                                            $debit = format_rupiah($r['debit']);
                                            $kredit = format_rupiah($r['kredit']);
                                            echo "<tr class='warnabaris' >
											<td>$no</td>           
											 <td>$r[tanggal]</td>
											 <td>$r[ket]</td>
											 <td>$r[petugas]</td>
											 <td>$show[nm_jurnal]</td>
											 <td style='text-align:center;'>$r[carabayar]</td>
											 <td style='text-align: right;'>Rp. $debit</td>											 
											 <td style='text-align: right;'>Rp. $kredit</td>											 
											 <td>
											 <a href='?module=catatan&act=edit&id=$r[id_jurnal]' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 									 
										     <a href=javascript:confirmdelete('$aksi?module=catatan&act=hapus&id=$r[id_jurnal]') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
											</td>
										</tr>";
                                            $no++;
                                        }
                                        echo "</tbody>
                        <tfoot>
                            ";
                                        $kaki = $db->query("select sum(debit) as debt,sum(kredit) as kre from jurnal where tanggal between '$tgl_awal' and '$tgl_akhir' ");
                                        $kk = $kaki->fetch_array();
                                        $akhir = format_rupiah($kk['debt']);
                                        $akhir2 = format_rupiah($kk['kre']);
                                        $akhir3 = format_rupiah($kk['kre'] - $kk['debt']);

                                        $kanan = $db->query("select sum(debit) as kn from jurnal where tanggal between '$tgl_awal' and '$tgl_akhir' and carabayar = 'TUNAI'");
                                        $kanan2 = $kanan->fetch_array();
                                        $kanan3 = format_rupiah($kanan2['kn']);

                                        $kiri = $db->query("select sum(debit) as kk from jurnal where tanggal between '$tgl_awal' and '$tgl_akhir' and carabayar = 'TRANSFER'");
                                        $kiri2 = $kiri->fetch_array();
                                        $kiri3 = format_rupiah($kiri2['kk']);

                                        $atas = $db->query("select sum(kredit) as kn from jurnal where tanggal between '$tgl_awal' and '$tgl_akhir' and carabayar = 'TUNAI'");
                                        $atas2 = $atas->fetch_array();
                                        $atas3 = format_rupiah($atas2['kn']);

                                        $bawah = $db->query("select sum(kredit) as kn from jurnal where tanggal between '$tgl_awal' and '$tgl_akhir' and carabayar = 'TRANSFER'");
                                        $bawah2 = $bawah->fetch_array();
                                        $bawah3 = format_rupiah($bawah2['kn']);

                                        $saldotunai = format_rupiah($atas2['kn'] - $kanan2['kn']);
                                        $saldotransfer = format_rupiah($bawah2['kn'] - $kiri2['kk']);
                                        echo "	
                            <tr style='font-weight: bold; background-color: #DB7093;text-align: center; font-size:large;' >
                            <td colspan='6'>Pengeluaran Tunai Rp. $kanan3 dan Transfer Rp. $kiri3 </td><td>Total</td><td colspan='2'>Rp &nbsp;&nbsp; $akhir</td>
                            </tr>
                            <tr style='font-weight: bold; background-color: #98FB98;text-align: center; font-size:large;' >
                            <td colspan='6'>Pemasukan Tunai Rp. $atas3 dan Transfer Rp. $bawah3</td><td>Total</td><td colspan='2'>Rp &nbsp;&nbsp; $akhir2</td>
                            </tr>
                            <tr style='font-weight: bold; background-color: #87CEEB;text-align: center; font-size:large;' >
                            <td colspan='6'>Saldo Tunai Rp. $saldotunai dan Saldo Transfer Rp. $saldotransfer </td><td>Saldo</td><td colspan='2'>Rp &nbsp;&nbsp;$akhir3</td>
                            </tr>
                        </tfoot>
</table>
<a  class ='btn  btn-danger' href='?module=catatan'>KEMBALI</a>
";
    break;
    case "rekap" :
  ?>
        <div class="box box-success box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">REKAPITULASI</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div><!-- /.box-tools
                    -->
            </div>
            <div class="box-body">
                <a  class ='btn  btn-danger btn-flat' href='?module=catatan&act=tambah'>Input Pengeluaran</a>
                <a  class ='btn  btn-info btn-flat' href='?module=catatan&act=tambah2'>Input Pemasukan</a>
                <a  class ='btn  btn-warning btn-flat' href='?module=catatan&act=jenistransaksi'>Jenis Transaksi</a>
                <a  class ='btn  btn-success btn-flat' href='?module=catatan&act=pilihhari'>Pilih Hari</a>
                <?php
                $lupa = $_SESSION['level'];
                if ($lupa == 'pemilik') {
                    echo " <a  class ='btn  btn-primary btn-flat' href='?module=catatan&act=kemarin'>Catatan Kemarin</a>
                           <a  class ='btn  btn-success btn-flat' href='?module=catatan&act=rekap'>Rekapitulasi</a>
                    ";
                }

                ?>
                <br><br>
                <form method="POST" action="?module=catatan&act=tampil3"  enctype="multipart/form-data" class="form-horizontal">

                    </br></br>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Tanggal Awal</label>
                        <div class="col-sm-4">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                                <input type="text" required="required" class="datepicker" id="tgl_awal" name="tgl_awal" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Tanggal Akhir</label>
                        <div class="col-sm-4">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                                <input type="text" required="required" class="datepicker" id="tgl_akhir" name="tgl_akhir" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="buttons col-sm-4">
                            <input class="btn btn-primary" type="submit" name="btn" value="TAMPIL">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            <a  class ='btn  btn-danger' href='?module=catatan'>KEMBALI</a>
                        </div>
                    </div>

                </form>
            </div>

        </div>
  <?php
        break;
   case "tampil3" :
       $tgl_awal = $_POST['tgl_awal'];
       $tgl_akhir = $_POST['tgl_akhir'];

       $tampiljurnal3 = mysqli_query($GLOBALS["___mysqli_ston"],
           "SELECT * FROM jenis_jurnal order by idjenis desc");
?>
    <table id="example3" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="5px" align="center">No</th>
                <th>Jenis Jurnal</th>
                <th>Debit</th>
                <th>Kredit</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $w=1;
            while ($q3 = $tampiljurnal3->fetch_array())
            { $r1 = $db->query("select sum(debit) as debt, sum(kredit) as kred from jurnal 
                                    where idjenis ='$q3[idjenis]' and tanggal between '$tgl_awal' and '$tgl_akhir'  ");
                $r2=$r1->fetch_array();
                $debit= format_rupiah($r2['debt']);
                $kredit= format_rupiah($r2['kred']);
                $de[]=$r2['debt'];
                $kr[]=$r2['kred'];
                echo"
                <tr>
                    <td width='5px' align='center'>$w</td>
                    <td><a href='?module=catatan&act=detail&id=$q3[idjenis]&tgl_awal=$tgl_awal&tgl_akhir=$tgl_akhir' title='EDIT' >$q3[nm_jurnal]</a></td>
                    <td style='text-align:right;'>$debit</td>
                    <td style='text-align:right;'>$kredit</td>
                </tr>
                ";
             $w++;
            }
        ?>
        </tbody>
        <?php
        $de1 = array_sum($de);
        $de2 = format_rupiah($de1);
        $kr1 = array_sum($kr);
        $kr2 = format_rupiah($kr1);
        $tde = $kr1 - $de1 ;
        $tde1 = format_rupiah($tde);
        echo "
       <tr style='background-color:lightblue;font-size:4vh;'>
        <td colspan='2'>Sub Total</td>
        <td style='text-align: right;'>$de2</td>
        <td style='text-align: right;'>$kr2</td>
       </tr>
       <tr style='background-color:lightblue;font-size:4vh;'>
        <td colspan='3'>Total</td>
        <td style='text-align: right;'>$tde1</td>
       
       </tr>
       ";
        ?>
    </table>

<?php
   break ;

   case "detail" :
    $det = $_GET['id'];
    $tgl_awal = $_GET['tgl_awal'];
    $tgl_akhir = $_GET['tgl_akhir'];

       $tampiljurnal4 = mysqli_query($GLOBALS["___mysqli_ston"],
           "SELECT * FROM jurnal where idjenis='$det' and tanggal between '$tgl_awal' and '$tgl_akhir' ");

   ?>
       <table id="example4" class="table table-bordered table-striped">
           <thead>
           <tr>
               <th width="5px" align="center">No</th>
               <th>Keterangan Jurnal</th>
               <th>Debit</th>
               <th>Kredit</th>
           </tr>
           </thead>
           <tbody>
           <?php
           $w=1;
           while ($q4 = $tampiljurnal4->fetch_array())
           {
               $qiu1=format_rupiah($q4['debit']);
               $qiu2 =format_rupiah($q4['kredit']);
               $de[]=$q4['debit'];
               $kr[]=$q4['kredit'];
               echo"
                <tr>
                    <td width='5px' align='center'>$w</td>
                    <td> $q4[ket]</td>
                    <td style='text-align:right;'>$qiu1</td>
                    <td style='text-align:right;'>$qiu2</td>
                </tr>
                ";
               $w++;
           }
           ?>

           </tbody>
           <?php
           $de1 = array_sum($de);
           $de2 = format_rupiah($de1);
           $kr1 = array_sum($kr);
           $kr2 = format_rupiah($kr1);
           $tde = $kr1 - $de1 ;
           $tde1 = format_rupiah($tde);
           echo "
       <tr style='background-color:lightblue;font-size:4vh;'>
        <td colspan='2'>Sub Total</td>
        <td style='text-align: right;'>$de2</td>
        <td style='text-align: right;'>$kr2</td>
       </tr>
       
       ";
           ?>

       </table>
       <a  class ='btn  btn-danger' href='?module=catatan'>KEMBALI</a>

   <?php
   break ;
}

}
?>
<script type="text/javascript" src="vendors/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    var editor = CKEDITOR.replace("content", {
        filebrowserBrowseUrl: '',
        filebrowserWindowWidth: 1000,
        filebrowserWindowHeight: 500
    });
</script>
<script type="text/javascript">
 $(function(){
  $(".datepicker").datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      todayHighlight: true,
  });
 });
</script>