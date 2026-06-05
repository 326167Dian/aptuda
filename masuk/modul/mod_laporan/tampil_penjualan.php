<?php
session_start();
include "../../../configurasi/koneksi.php";
require('../../assets/pdf/fpdf.php');
include "../../../configurasi/fungsi_indotgl.php";
include "../../../configurasi/fungsi_rupiah.php";


$tgl_awal = $_POST['tgl_awal'];
$tgl_akhir = $_POST['tgl_akhir'];

$pdf = new FPDF("P","cm","A4");

$pdf->SetMargins(1,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25.5,0.7,"LAPORAN PENJUALAN",0,10,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(5.5,0.5,"Tanggal Cetak : ".date('d-m-Y h:i:s'),0,0,'L');
$pdf->Cell(5,0.5,"Dicetak Oleh : ".$_SESSION['namalengkap'],0,1,'L');
$pdf->Cell(5.5,0.5,"Periode : ".tgl_indo($tgl_awal)." - ".tgl_indo($tgl_akhir),0,0,'L');


$pdf->ln(0.5);
$pdf->SetFont('Arial','',9);

$no=1;
$penjualan = $db->query("select * from trkasir where tgl_trkasir between '$tgl_awal' and '$tgl_akhir' order by id_carabayar ");
while ($jual = $penjualan->fetch_array()) {
	$cabay = $db->query("select * from carabayar where id_carabayar='$jual[id_carabayar]' ");
	$cba = $cabay->fetch_array();
	//hitung angsuran
	
	if ($cba['id_carabayar']<3)
	 {$masuk=$jual['ttl_trkasir'];}
	else  {$angsuran = $db->query("select sum(angsuran) as angsur from trkasir_tempo where kd_trkasir='$jual[kd_trkasir]' ");
	$angs = $angsuran->fetch_array();
	$masuk=$angs['angsur']; }
	
	
	

	$pdf->Cell(3, 0.4, 'No', 0, 0, 'L');
	$pdf->Cell(0.5, 0.4, ': ', 0, 0, 'L');
	$pdf->Cell(5, 0.4, $no, 0, 1, 'L');

	$pdf->Cell(3, 0.4, 'Nama Pelanggan', 0, 0, 'L');
	$pdf->Cell(0.5, 0.4, ': ', 0, 0, 'L');
	$pdf->Cell(5, 0.4, $jual['nm_pelanggan'], 0, 1, 'L');

	$pdf->Cell(3, 0.4, 'Kode Transaksi', 0,0 , 'L');
	$pdf->Cell(0.5, 0.4, ': ', 0, 0, 'L');
	$pdf->Cell(5, 0.4, $jual['kd_trkasir'], 0, 1, 'L');

	$pdf->Cell(3, 0.4, 'Metode Bayar', 0,0 , 'L');
	$pdf->Cell(0.5, 0.4, ': ', 0, 0, 'L');
	$pdf->Cell(5, 0.4, $cba['nm_carabayar'], 0, 1, 'L');

	$pdf->Cell(3, 0.4, 'Dana Masuk', 0,0 , 'L');
	$pdf->Cell(0.5, 0.4, ': ', 0, 0, 'L');
	$pdf->Cell(5, 0.4, format_rupiah($masuk), 0, 1, 'L');

	$detail = $db->query("select * from trkasir_detail where kd_trkasir='$jual[kd_trkasir]' order by nmbrg_dtrkasir ");
	$no2=1;

	$pdf->Cell(1, 0.7, 'No', 1, 0, 'C');
	$pdf->Cell(9.5, 0.7, 'Nama Barang', 1, 0, 'C');
	$pdf->Cell(1, 0.7, 'Jml', 1, 0, 'C');
	$pdf->Cell(1.5, 0.7, 'Sat', 1, 0, 'C');
	$pdf->Cell(2, 0.7, 'Harga', 1, 0, 'C');
	$pdf->Cell(2, 0.7, 'Disc', 1, 0, 'C');
	$pdf->Cell(2, 0.7, 'Sub Total', 1, 1, 'C');
	$pdf->SetFont('Arial','',8);


	while($det=$detail->fetch_array()){
	$hrgawl = $det['hrgjual_dtrkasir'] + $det['disc'];
	$pdf->Cell(1, 0.6,$no2, 1, 0, 'C');
	$pdf->Cell(9.5, 0.6,$det['nmbrg_dtrkasir'], 1, 0, 'L');
	$pdf->Cell(1, 0.6, $det['qty_dtrkasir'], 1, 0, 'C');
	$pdf->Cell(1.5, 0.6, $det['sat_dtrkasir'], 1, 0, 'C');
	$pdf->Cell(2, 0.6, format_rupiah($hrgawl), 1, 0, 'R');
	$pdf->Cell(2, 0.6, format_rupiah($det['disc']), 1, 0, 'R');
	$pdf->Cell(2, 0.6, format_rupiah($det['hrgttl_dtrkasir']), 1, 1, 'R');
	$no2++;

	}
	$sub=$db->query("select sum(hrgttl_dtrkasir) as total from trkasir_detail where kd_trkasir='$jual[kd_trkasir]' ");
	$subt = $sub->fetch_array();
	$sttl = format_rupiah($subt['total']);
	$grand = $db->query("select nilai_transaksi from trkasir where kd_trkasir='$jual[kd_trkasir]' ");
	$akhir = $grand->fetch_array();
	$discfaktur = $subt['total'] - $akhir['nilai_transaksi'];


	$pdf->Cell(17, 0.6,'Total', 1, 0, 'R');
	$pdf->Cell(2, 0.6,$sttl, 1, 1, 'R');
	$pdf->Cell(17, 0.6,'Diskon Faktur', 1, 0, 'R');
	$pdf->Cell(2, 0.6,format_rupiah($discfaktur), 1, 1, 'R');
	$pdf->Cell(17, 0.6,'Nilai transaksi', 1, 0, 'R');
	$pdf->Cell(2, 0.6,format_rupiah($akhir['nilai_transaksi']), 1, 1, 'R');
	$pdf->Cell(3, 0.6, '', 0, 1, 'R');
$no++;
}

$finish = $db->query("select 
							sum(nilai_transaksi) as nilai
					from trkasir where tgl_trkasir between '$tgl_awal' and '$tgl_akhir'  ");
$akh = $finish->fetch_array();
$subfinish1 = $db->query("select 
							sum(nilai_transaksi) as sisa														
					from trkasir where tgl_trkasir between '$tgl_awal' and '$tgl_akhir' and id_carabayar=2  ");
$subakh1 = $subfinish1->fetch_array();

$subfinish2 = $db->query("select 
							sum(nilai_transaksi) as sisa1														
					from trkasir where tgl_trkasir between '$tgl_awal' and '$tgl_akhir' and id_carabayar=3  ");
$subakh2 = $subfinish2->fetch_array();

$subfinish3 = $db->query("select 
							sum(nilai_transaksi) as sisa3														
					from trkasir where tgl_trkasir between '$tgl_awal' and '$tgl_akhir' and id_carabayar=1  ");
$subakh3 = $subfinish3->fetch_array();


$pdf->SetFont('Arial','B',14);
$pdf->Cell(6, 0.7, 'Total Nilai transaksi', 0, 0, 'L');
$pdf->Cell(0.5, 0.7, ': Rp. ', 0, 0, 'L');
$pdf->Cell(5, 0.7, format_rupiah($akh['nilai']), 0, 1, 'R');

$pdf->Cell(6, 0.7, 'Pembayaran Tunai', 0,0 , 'L');
$pdf->Cell(0.5, 0.7, ': Rp. ', 0, 0, 'L');
$pdf->Cell(5, 0.7, format_rupiah($subakh3['sisa3']), 0, 1, 'R');

$pdf->Cell(6, 0.7, 'Pembayaran Transfer', 0,0 , 'L');
$pdf->Cell(0.5, 0.7, ': Rp. ', 0, 0, 'L');
$pdf->Cell(5, 0.7, format_rupiah($subakh1['sisa']), 0, 1, 'R');

$pdf->Cell(6, 0.7, 'Pembayaran Tempo', 0,0 , 'L');
$pdf->Cell(0.5, 0.7, ': Rp.', 0, 0, 'L');
$pdf->Cell(5, 0.7, format_rupiah($subakh2['sisa1']), 0, 1, 'R');

$pdf->Output("Laporan_data_barang.pdf","I");

?>

