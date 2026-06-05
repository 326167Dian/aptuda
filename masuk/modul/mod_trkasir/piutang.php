<?php
session_start();
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

    switch($_GET['act']){
        default:

            ?>


            <div class="box box-primary box-solid table-responsive">
                <div class="box-header with-border">
                    <h3 class="box-title">LAPORAN PIUTANG PELANGGAN</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools
                    -->
                </div>

                <table class="table-striped table-bordered table-responsive">
                    <tr>
                        <td> <div class="box-body">Search By Tanggal </div></td>
                        <td><a  class ='btn  btn-primary' href='?module=piutang&act=searchtanggal'>SHOW</a></td></tr>
                    <tr>
                        <td><div class="box-body">Search By Nama Pelanggan </div></td>
                        <td><a  class ='btn  btn-primary' href='?module=piutang&act=searchpelanggan'>SHOW</a></td>
                    </tr>

                </table>
                <div class="box-body">

                    <div class="buttons col-sm-4">
                        <input class='btn btn-danger' type=button value=KEMBALI onclick=self.history.back()>
                </div>

            </div>


            <?php

            break;

        case "searchtanggal" :
            ?>
            <form method="POST" action="?module=piutang&act=piutangtanggal" target="_blank" enctype="multipart/form-data" class="form-horizontal">

                        </br></br>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tanggal Awal</label>
                            <div class="col-sm-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                    <input type="text" required="required" class="datepicker" name="tgl_awal" autocomplete="off">
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
                                    <input type="text" required="required" class="datepicker" name="tgl_akhir" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="buttons col-sm-4">
                                <input class="btn btn-primary" type="submit" name="btn" value="TAMPIL">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                <input class='btn btn-danger' type=button value=KEMBALI onclick=self.history.back()>
                            </div>
                        </div>

                    </form>
        <?php
            break;
        case "piutangtanggal" :
            $tgl_awal = $_POST['tgl_awal'];
            $tgl_akhir = $_POST['tgl_akhir'];

            $tampil_trkasir = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir  
            where statusbayar = 'PROSES' and tgl_trkasir between '$tgl_awal' and '$tgl_akhir' ") ;

            ?>
            <div class="box box-primary box-solid table-responsive">
                <div class="box-header with-border">
                    <h3 class="box-title">DATA PIUTANG</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div>
                <div class="box-body table-responsive">

                        <table id="example1" class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr>
                                <th style="text-align: right; ">No</th>
                                <th style="text-align: right; ">Kode Transaksi</th>
                                <th style="text-align: right; ">Tgl Transaksi</th>
                                <th style="text-align: right; ">Nama Pelanggan</th>
                                <th style="text-align: right; ">Metode Bayar</th>
                                <th style="text-align: right; ">Status Bayar</th>
                                <th style="text-align: right; ">dibayar</th>
                                <th style="text-align: right; ">Sisa</th>
                                <th style="text-align: right; ">detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no=1;
                            while ($tp=mysqli_fetch_array($tampil_trkasir)) {
                                $dp_bayar = format_rupiah($tp['dp_bayar']);
                                $sisa_bayar = format_rupiah($tp['sisa_bayar']);
                                $cb = $db->query("select * from carabayar where id_carabayar='$tp[id_carabayar]'");
                                $tcb = $cb->fetch_array();
                                $jumlah[]=$tp['sisa_bayar'];

                                echo "
                                <tr>
                                    <td>$no</td>
                                    <td>$tp[kd_trkasir]</td>
                                    <td>$tp[tgl_trkasir]</td>
                                    <td align='center'>$tp[nm_pelanggan]</td>
                                    <td align='center'>$tcb[nm_carabayar]</td>
                                    <td align='center'>$tcb[statusbayar]</td>
                                    <td align='center'>$dp_bayar</td>
                                    <td align='center'>$sisa_bayar</td>
                                    <td align='center'>
                                        <a href='?module=piutang&act=detail&id=$tp[kd_trkasir]' class='btn btn-warning btn-xs'>DETAIL</a> 
                                    
                                    </td>
                                </tr>
                                                       
                            ";
                            $no++;}

                            ?>
                            </tbody>
                        </table>

                    <table class="table table-bordered table-striped table-responsive" >
                    <?php
                    $total = format_rupiah(array_sum($jumlah));
                    echo" <tr style='text-align:center; font-weight: bold; font-size: 6vh;background-color: #00fafa'><td colspan='6'>Total</td><td colspan='2'>Rp. $total</td></tr>";
                    ?>
                    </table>
                </div>
            </div>

            <?php
            break;

            case "detail":
            $kode = $_GET['id'];

            //$tamp_piutang = mysqli_query($GLOBALS["___mysqli_ston"],"select * from trkasir where kd_trkasir='$kode'");
            $tamp_piutang = $db->query("select * from trkasir where kd_trkasir='$kode' 
             ;");
            $ttp = $tamp_piutang->fetch_array();
                    $cabay1 = $db->query("select * from carabayar where id_carabayar='$ttp[id_carabayar]' ");
                    $cabay2 = $cabay1->fetch_array();
                    $cabay = $cabay2['nm_carabayar'];
            ?>
            <div class="box box-primary box-solid table-responsive">
                <div class="box header with-border">
                    <h3 class="box-title">DETAIL PIUTANG</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools-->
                </div>
                <div>
                <table class="table table-bordered table-striped"">
                    <tr><td width="200 px">Kode Transaksi</td><td><?= $ttp['kd_trkasir'] ?></td></tr>
                    <tr><td width="200 px">Nama Pelanggan</td><td><?= $ttp['nm_pelanggan'] ?></td></tr>
                    <tr><td width="200 px">Tanggal Transaksi</td><td><?= $ttp['tgl_trkasir'] ?></td></tr>
                    <tr><td width="200 px">Petugas</td><td><?= $ttp['petugas'] ?> </td></tr>
                    <tr><td width="200 px">Cara Bayar</td><td style="font-size: 20px;font-weight: bold;"><?= $cabay  ?><td></tr>
                    <tr><td width="200 px">Status Bayar</td><td style="font-size: 20px;font-weight: bold;"><?= $ttp['statusbayar'] ?><td></tr>
                </table>
                </div>
                <div>
            <table id="example2" class="table table-bordered table-striped table-responsive">
                <thead align="center">
                   <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Nama Barang</th>
                    <th style="text-align: center;">Satuan</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: center;">Harga</th>
                    <th style="text-align: center;">Sub Total</th>
                   </tr>
                </thead>
            <tbody>
                <?php
                $no=1;
                //$detail = mysqli_query($GLOBALS["___mysqli_ston"],"select * from trkasir_detail where kd_trkasir='$kode'");
                $detail = $db->query("select 
                                            kd_trkasir,
                                            nmbrg_dtrkasir,
                                            sat_dtrkasir,
                                            qty_dtrkasir,
                                            hrgjual_dtrkasir                                    
                                            from trkasir_detail where kd_trkasir='$kode' order by nmbrg_dtrkasir asc");
                while($td=$detail->fetch_array()){
                    $hargajual = format_rupiah($td['hrgjual_dtrkasir']);
                    $totaljual = format_rupiah($td['qty_dtrkasir'] * $td['hrgjual_dtrkasir']);
                    $grandtot[]= $td['qty_dtrkasir']* $td['hrgjual_dtrkasir'];

                    echo" 
                            <tr>
                            <td style='text-align: center'>$no</td>                           
                            <td style='text-align: left'>$td[nmbrg_dtrkasir]</td>
                            <td style='text-align: center'>$td[sat_dtrkasir]</td>
                            <td style='text-align: center'>$td[qty_dtrkasir]</td>
                            <td style='text-align: center'>$hargajual</td>
                            <td style='text-align: center'>$totaljual</td>                   
                          </tr>
                          ";
                $no++; }
                 $tot=array_sum($grandtot);
                 $grandtotal = $tot - $ttp['diskon2'];
                ?>
             </tbody>

            </table>
            </div>
            <table CLASS="TABLE table-bordered">
                <tr>
                    <td style="text-align: right">DISKON</td>
                    <td style="text-align:right"><?= format_rupiah($ttp['diskon2']) ?></td>
                </tr>
                <tr>
                    <td style="text-align: right; background-color:#00FFFF; font-size: 20px;font-weight: bold;">GRAND TOTAL</td>
                    <td style="text-align:right;background-color:#00FFFF;font-size: 20px;font-weight: bold;">Rp. <?= format_rupiah($grandtotal) ?> </td>
                </tr>
            </table>
            </div>
            <div class="box box-primary box-solid table-responsive">
                    <div class="box header with-border">
                        <h3 class="box-title">DETAIL PEMBAYARAN</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools-->
                    </div>
                    <table id="example3" class="table table-bordered table-striped">
                        <thead align="center">
                            <tr>
                                <th>No.</th>
                                <th>Angsuran</th>
                                <th>Waktu</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ttempo = mysqli_query($GLOBALS["___mysqli_ston"],"select * from trkasir_tempo where kd_trkasir='$kode';");
                            $ttttempo = $db->query("select sum(angsuran) as ttlbayar from trkasir_tempo where 
                                          kd_trkasir='$kode'");
                            $tam_bayar = $ttttempo->fetch_array();
                            $no=1;
                            while($ttt=mysqli_fetch_array($ttempo))
                            {   $angsuran= format_rupiah($ttt['angsuran']);
                                 echo"
                                                                <tr>
                                                                    <td>$no</td>
                                                                    <td style='text-align: right'>Rp. $angsuran</td>
                                                                    <td>$ttt[waktu]</td>
                                                                    <td>$ttt[petugas]</td>
                                                                </tr>
                                                                ";
                                $no++;}

                           ?>
                        </tbody>
                    </table>
                <table class="table table-bordered table-responsive">
                    <tr style="background-color:#00FFFF"><td style="font-size:20px;text-align: center"><strong>TOTAL CICILAN</strong></td>
                        <td style="font-size:20px;text-align: center"><strong>Rp. <?= format_rupiah($tam_bayar['ttlbayar'])?></strong></td>
                    </tr>
                </table>

                </div>
            </div>
            <div style="text-align: center">
            <a class='btn btn-primary xxs' onclick="window.open('modul/mod_laporan/faktur.php?kd_trkasir=<?php echo $kode ?>',
                        'nama window','width=700,height=400,toolbar=no,location=no,directories=no,status=no,menubar=no, ' +
                        'scrollbars=no,resizable=yes,copyhistory=no')">PRINT</a>
            <input class='btn btn-success' type=button value=KEMBALI onclick=self.history.back()>
            </div>
                <?PHP

            break;

        case "searchpelanggan" :

        ?>
            <div class="box box-primary box-solid table-responsive">
                <div class="box-header with-border">
                    <h3 class="box-title">LAPORAN PIUTANG BERDASARKAN NAMA PELANGGAN</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools
                                -->
                </div>
                <table id="example4" class="table table-bordered table-striped table-responsive">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Piutang </th>
                        <th>Detail</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                        $temponama = $db->query("select * from trkasir where statusbayar ='PROSES' group by nm_pelanggan");
                        $no=1;
                        while ($tnama= $temponama->fetch_array()){
                        $totnama = $db->query("select sum(nilai_transaksi) as sisa from trkasir where nm_pelanggan='$tnama[nm_pelanggan]' AND statusbayar = 'PROSES'");
                        $tamnama = $totnama->fetch_array();
                        $tamsisa = format_rupiah($tamnama['sisa']);
                        $totalpelanggan[] = $tamnama['sisa'];

                        echo"
                        <tr>
                            <td>$no</td>
                            <td>$tnama[nm_pelanggan]</td>                         
                            <td style='text-align: right'>$tamsisa</td>                       
                            <td> <a href='?module=piutang&act=detailtransaksi&id=$tnama[nm_pelanggan]' class='btn btn-warning btn-xs'>DETAIL</a> </td>
                        </tr>";
                        $no++;
                        }
                       ?>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped table-responsive" >
                    <?php
                    $total = format_rupiah(array_sum($totalpelanggan));
                    echo" <tr style='text-align:center; font-weight: bold; font-size: 6vh;background-color: #00fafa'><td colspan='6'>Total</td><td colspan='2'>Rp. $total</td></tr>";
                    ?>
                </table>
                <div style="text-align: center">
                <input class='btn btn-danger' type=button value=KEMBALI onclick=self.history.back()>
                </div>
            </div>
        <?php
        break;
        case "detailtransaksi" :
                $pelanggan = $_GET['id'];

        ?>
        <div class="box box-primary box-solid table-responsive">
                <div class="box-header with-border">
                    <h3 class="box-title">LAPORAN PIUTANG PELANGGAN <?= $pelanggan ?> </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools
        -->
                </div>
                <table id="example5" class="table table-bordered table-striped table-responsive">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Petugas</th>
                        <th>Total Piutang </th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                        $temponama = $db->query("select * from trkasir where nm_pelanggan='$pelanggan' and statusbayar = 'PROSES' ");
                        $no=1;
                        while ($tnama= $temponama->fetch_array()){
//                        $totnama = $db->query("select sum(sisa_bayar) as sisa from trkasir where nm_pelanggan='$tnama[nm_pelanggan]'
//                                    and kd_trkasir='$tnama[kd_trkasir]' ");
//                        $tamnama = $totnama->fetch_array();
//                        $tamsisa = format_rupiah($tamnama['sisa']);
                        $piutang = format_rupiah($tnama['nilai_transaksi']);
                            $tgl_awal = date('Y-m-d');
                            $tgl_akhir = date('Y-m-d', strtotime('-30 days', strtotime( $tgl_awal)));

                            echo"
                        <tr>
                            <td>$no</td>
                            <td>$tnama[kd_trkasir]</td> 
                            ";
                            if($tnama['tgl_trkasir']<$tgl_akhir)
                            {
                            echo"
                            <td style='text-align: right;background-color:#FF4500;'>$tnama[tgl_trkasir]</td>    "; 
                            } 
                            else {echo"
                            <td style='text-align: right'>$tnama[tgl_trkasir]</td>    "; }
                            echo"
                            <td style='text-align: right'>$tnama[petugas]</td>                       
                            <td style='text-align: right'>$piutang</td>                       
                            <td> <a href='?module=piutang&act=detail&id=$tnama[kd_trkasir]' class='btn btn-warning btn-xs'>Tampil</a> </td>
                        </tr>";
                            $no++;
                        }
                    ?>
                    </tbody>

                </table>
                <div style='text-align: center;'>
                <input class='btn btn-success' type=button value=KEMBALI onclick=self.history.back()>
                </div>
        </div>
                <?php
            break;
    }
}
?>
<script type="text/javascript">
    $(function(){
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    });
</script>
