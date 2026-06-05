<?php
include "../../../configurasi/koneksi.php";
require('../../assets/pdf/fpdf.php');
include "../../../configurasi/fungsi_indotgl.php";
include "../../../configurasi/fungsi_rupiah.php";

//ambil header
$ah = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM setheader ");
	  $rh=mysqli_fetch_array($ah);
	  
$dt = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir
JOIN carabayar ON trkasir.id_carabayar = carabayar.id_carabayar
WHERE trkasir.kd_trkasir='$_GET[kd_trkasir]'");
	  $r1=mysqli_fetch_array($dt);


$jumlahdetail = mysqli_num_rows(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir_detail WHERE kd_trkasir='$_GET[kd_trkasir]'"));

// $ukuran1 = 14.7; //setingan kertas
// $ukuran2 = 5.4; //garis akhir tabel
$ukuran1 = 33; //setingan kertas
$ukuran2 = 43; //garis akhir tabel

$tambahukuran = $jumlahdetail * 0.4;
$tinggikertas = $ukuran1 + $tambahukuran;
$posisigaris = $ukuran2 + $tambahukuran;


//$pdf = new FPDF("P","cm","A4");
$pdf = new FPDF("P","cm",array($tinggikertas,33));
$pdf->SetMargins(-0.3,-0.8,0);
$pdf->AliasNbPages();
$pdf->AddPage();

//$pdf->Image('../../images/mmd.jpg',1,1.5,5,2);
//HEADER 1
$pdf->Line(0.7,4,32,4); //horisontal bawah

$pdf->Line(0.7,6.8,32,6.8); //judul tabel atas



$pdf->ln(1.4);
$pdf->SetX(0.6);
// $pdf->SetFont('Arial','',9);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,0.4,$rh['satu'],0,1,'L');
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(0,0.6,$rh['dua'],0,1,'L');

$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->SetX(0.6);
$pdf->Cell(0,0.6,$rh['tiga'],0,1,'L');
$pdf->SetX(0.6);
$pdf->Cell(0,0.6,$rh['empat'],0,1,'L');
$pdf->SetX(0.6);
$pdf->Cell(0,0.6,$rh['lima'],0,1,'L');
$pdf->SetX(0.6);
$pdf->Cell(0,0.6,$rh['enam'],0,1,'L');
$pdf->SetX(0.6);
$pdf->Cell(0,0.6,$rh['tujuh'],0,1,'L');

//KIRI 1
$pdf->ln(0);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(2,0,'No Nota',0,0,'L');
$pdf->Cell(0.5,0,':',0,0,'L');
$pdf->SetFont('Arial','',14);
$pdf->Cell(1,0,$r1['kd_trkasir'],0,0,'L');

//KIRI 2
$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(2,0,'Tanggal',0,0,'L');
$pdf->Cell(0.5,0,':',0,0,'L');
$pdf->SetFont('Arial','',14);
$pdf->Cell(1,0,tgl_indo($r1['tgl_trkasir']),0,0,'L');


//KIRI 3
$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(2,0,'Pelanggan',0,0,'L');
$pdf->Cell(0.5,0,':',0,0,'L');
$pdf->SetFont('Arial','',14);
$pdf->Cell(1,0,$r1['nm_pelanggan'],0,0,'L');

//KIRI 4
$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(2,0,'No Telp/HP',0,0,'L');
$pdf->Cell(0.5,0,':',0,0,'L');
$pdf->SetFont('Arial','',14);
$pdf->Cell(1,0,$r1['tlp_pelanggan'],0,0,'L');

$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(8, 0.8, 'Item', 0, 0, 'L');
$pdf->Cell(6, 0.8, 'Qty', 0, 0, 'C');
$pdf->Cell(8, 0.8, 'Harga', 0, 0, 'R');
$pdf->Cell(8, 0.8, 'Jumlah', 0, 1, 'R');

$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);

	$no=1;
	$query=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir_detail WHERE kd_trkasir='$_GET[kd_trkasir]'
	ORDER BY id_dtrkasir ASC");

while($r2=mysqli_fetch_array($query)){
    $st[] = $r2['hrgttl_dtrkasir'];
    $gt = array_sum($st);
    $disc = (($gt-$r1['ttl_trkasir'])/$gt)*100;
    $tagihan = format_rupiah($r1['ttl_trkasir']);
    $subtotal = format_rupiah($gt);
	$pdf->SetX(0.6);
	
	$pdf->Cell(8, 0.6, $r2['nmbrg_dtrkasir'],0, 0, 'L');
	$pdf->Cell(3, 0.6, $r2['qty_dtrkasir'],0, 0, 'R');
	$pdf->Cell(3, 0.6, $r2['sat_dtrkasir'],0, 0, 'L');
	$pdf->Cell(8, 0.6, format_rupiah($r2['hrgjual_dtrkasir']), 0, 0,'R');
	$pdf->Cell(8, 0.6, format_rupiah($r2['hrgttl_dtrkasir']), 0, 1,'R');

	$no++;
}
$pdf->ln(1);
$pdf->SetFont('Arial','',14);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(17, 0.6, 'Metode bayar : ', 0, 0, 'R');
$pdf->Cell(3, 0.6, $r1['nm_carabayar'], 0, 0, 'L');
$pdf->SetFont('Arial','',14);
$pdf->Cell(8, 0.6, 'Sub Total : ', 0, 0, 'R');
$pdf->Cell(2, 0.6, $subtotal, 0, 1, 'R');
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
// $pdf->Cell(20, 0.6, $r1['nm_carabayar'], 0, 0, 'R');
$pdf->Cell(28, 0.6, 'Diskon (%) : ', 0, 0, 'R');
$pdf->Cell(2, 0.6, $disc, 0, 1, 'R');
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(28, 0.6, 'Tagihan : ', 0, 0, 'R');
$pdf->Cell(2, 0.6, $tagihan, 0, 1, 'R');
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(28, 0.6, 'Uang Cash : ', 0, 0, 'R');
$pdf->Cell(2, 0.6, format_rupiah($r1['dp_bayar']), 0, 1, 'R');
$pdf->SetX(0.6);
$pdf->Cell(28, 0.6, 'Kembalian : ', 0, 0, 'R');
$pdf->Cell(2, 0.6, format_rupiah($r1['sisa_bayar']), 0, 1, 'R');





$pdf->ln(0.1);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(0,0.5,'',0,1,'L');
$pdf->SetX(0.6);
$pdf->Cell(0,0.5,$r1['ket_trkasir'],0,0,'L');

$pdf->ln(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(0,0.5,$rh['delapan'],0,1,'C');
$pdf->Cell(0,0.5,$rh['sembilan'],0,1,'C');
$pdf->Cell(0,0.5,$rh['sepuluh'],0,1,'C');
$pdf->Cell(0,0.5,"Kasir : ".$r1['petugas'],0,1,'C');

$pdf->Output("struk_wallpaper","I");

?>

