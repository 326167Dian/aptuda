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
//$ukuran1 = 33; //setingan kertas
//$ukuran2 = 43; //garis akhir tabel

//$tambahukuran = $jumlahdetail * 0.4;
//$tinggikertas = $ukuran1 + $tambahukuran;
//$posisigaris = $ukuran2 + $tambahukuran;



$pdf = new FPDF("P","cm","letter");
$pdf->SetMargins(0,1,0);
$pdf->AliasNbPages();
$pdf->AddPage();

//$pdf->SetAutoPageBreak(true);
//$pdf->Image('../../images/mmd.jpg',1,1.5,5,2);
//HEADER 1
$pdf->Line(0.7, 2.9, 21, 2.9); //horisontal bawah

$pdf->Line(0.7, 3.3, 21, 3.3); //judul tabel atas
$pdf->Line(0.7, 3.4, 21, 3.4); //judul tabel bawah

$pdf->ln(0.5);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(21,0.1,'FAKTUR PENJUALAN',0,1,'C');

$pdf->ln(0.4);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','B',13);
$pdf->Cell(10,0.1,$rh['satu'],0,0,'L');

$pdf->SetFont('Arial','',9);
$pdf->Cell(3, 0.2, 'Kode Transaksi', 0, 0, 'L');
$pdf->Cell(2, 0.2, ':', 0, 0, 'R');
$pdf->Cell(6, 0.2, $r1['kd_trkasir'], 0, 0, 'L');

$pdf->ln(0.3);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',9);
$pdf->Cell(10,0.2,$rh['dua'],0,0,'L');
$pdf->Cell(3, 0.2, 'Tanggal', 0, 0, 'L');
$pdf->Cell(2, 0.2, ':', 0, 0, 'R');
$pdf->Cell(6, 0.2, tgl_indo($r1['tgl_trkasir']), 0, 0, 'L');

$pdf->ln(0.3);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',9);
$pdf->Cell(10,0.2,$rh['tiga'],0,0,'L');
$pdf->Cell(3, 0.2, 'Nama Pelanggan', 0, 0, 'L');
$pdf->Cell(2, 0.2, ':', 0, 0, 'R');
$pdf->Cell(6, 0.2, $r1['nm_pelanggan'], 0, 0, 'L');


$pdf->ln(0.3);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',9);
$pdf->Cell(1, 0.4, 'No.', 0, 0, 'C');
$pdf->Cell(10, 0.4, 'Nama Barang', 0, 0, 'C');
$pdf->Cell(2, 0.4, 'Jml', 0, 0, 'R');
$pdf->Cell(1, 0.4, 'Sat', 0, 0, 'L');
$pdf->Cell(2, 0.4, 'Harga', 0, 0, 'C');
$pdf->Cell(2, 0.4, 'Disc', 0, 0, 'C');
$pdf->Cell(2, 0.4, 'Total', 0, 1, 'C');

$pdf->ln(0.3);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',9);

	$no=1;
	$query=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir_detail WHERE kd_trkasir='$_GET[kd_trkasir]'
	ORDER BY nmbrg_dtrkasir ASC");

$garis = 6.1;
while($r2=mysqli_fetch_array($query)){
    $st[] = $r2['hrgttl_dtrkasir'];
    $gt = array_sum($st);
    // $disc = (($gt-$r1['ttl_tr5kasir'])/$gt)*100;
    $disc = format_rupiah($r2['disc']);
    $tagihan = format_rupiah($r1['ttl_trkasir']);
    $subtotal = format_rupiah($gt);
	$pdf->SetX(0.6);
	
    $text1 = substr($r2['nmbrg_dtrkasir'], 0,60);
    $text2 = substr($r2['nmbrg_dtrkasir'], 60,120);
    $text3 = strlen($r2['nmbrg_dtrkasir']);
    
    if ($text3 >60){
        $pdf->Cell(1, 0.3, $no.'.', 0, 0, 'C');
        $pdf->Cell(10, 0.3, $text1, 0, 0, 'L');
        $pdf->Cell(2, 0.3, $r2['qty_dtrkasir'], 0, 0, 'R');
        $pdf->Cell(1, 0.3, $r2['sat_dtrkasir'], 0, 0, 'L');
        $pdf->Cell(2, 0.3, format_rupiah($r2['hrgjual_dtrkasir']), 0, 0, 'R');
        $pdf->Cell(2, 0.3, $disc, 0, 0, 'C');
        $pdf->Cell(2, 0.3, format_rupiah($r2['hrgttl_dtrkasir']), 0, 1, 'R');
        
    	$pdf->SetX(0.6);
        $pdf->Cell(1, 0.3, '', 0, 0, 'C');
        $pdf->Cell(10, 0.3, $text2, 0, 0, 'L');
        $pdf->Cell(2, 0.3, '', 0, 0, 'R');
        $pdf->Cell(1, 0.3, '', 0, 0, 'L');
        $pdf->Cell(2, 0.3, '', 0, 0, 'R');
        $pdf->Cell(2, 0.3, '', 0, 0, 'C');
        $pdf->Cell(2, 0.3, '', 0, 1, 'R');
        
        $garis = $garis + 1.2;
    } else {
        $pdf->Cell(1, 0.3, $no.'.', 0, 0, 'C');
        $pdf->Cell(10, 0.3, $r2['nmbrg_dtrkasir'], 0, 0, 'L');
        $pdf->Cell(2, 0.3, $r2['qty_dtrkasir'], 0, 0, 'R');
        $pdf->Cell(1, 0.3, $r2['sat_dtrkasir'], 0, 0, 'L');
        $pdf->Cell(2, 0.3, format_rupiah($r2['hrgjual_dtrkasir']), 0, 0, 'R');
        $pdf->Cell(2, 0.3, $disc, 0, 0, 'C');
        $pdf->Cell(2, 0.3, format_rupiah($r2['hrgttl_dtrkasir']), 0, 1, 'R');
        
        $garis = $garis + 0.6;
    }

    $no++;
}
$garis = $garis + 0.4;
$pdf->SetFont('Arial','U');
$pdf->Cell(21, 0.2, '.........................................................................................................................................................................................................................................', 0, 0, 'C');

$pdf->ln(0.4);
$pdf->SetX(0.6);
$pdf->Cell(10.5, 0.3, 'Transfer BSI no Rek 1089057920 an Badiah ', 0, 0, 'L');

$pdf->ln(0.4);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',9);
$pdf->Cell(4, 0.3,'Hormat Kami', 0, 0, 'C');

$pdf->SetFont('Arial','',9);
$pdf->Cell(4, 0.3,'Penerima',0,0,'C');

$pdf->SetFont('Arial','',9);
$pdf->Cell(2.5, 0.3, 'Metode bayar', 0, 0, 'R');
$pdf->Cell(0.3, 0.3, ':', 0, 0, 'R');
$pdf->Cell(2.5, 0.3, $r1['nm_carabayar'], 0, 0, 'L');

$pdf->SetFont('Arial','',9);
$pdf->Cell(3, 0.3, 'Sub Total : ', 0, 0, 'R');
$pdf->Cell(3, 0.3, $subtotal, 0, 1, 'R');

$pdf->SetX(0.6);
$pdf->SetFont('Arial','',9);
$pdf->Cell(10.5, 0.3, 'petugas ', 0, 0, 'R');
$pdf->Cell(0.3, 0.3, ':', 0, 0, 'R');
$pdf->Cell(2.5, 0.3,$r1['petugas'], 0, 0, 'L');

$pdf->SetFont('Arial','',9);
$pdf->Cell(3, 0.3, 'Disc Faktur : ', 0, 0, 'R');
$pdf->Cell(3, 0.3, format_rupiah($r1['diskon2']), 0, 1, 'R');

$pdf->SetFont('Arial','',9);
$pdf->Cell(16.9, 0.3, 'Total : ', 0, 0, 'R');
$pdf->Cell(3, 0.3, format_rupiah($r1['ttl_trkasir']), 0, 1, 'R');

$pdf->ln(0.4);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',9);
$pdf->Cell(4, 0.3,'( _____________ )', 0, 0, 'C');

$pdf->SetFont('Arial','',9);
$pdf->Cell(4, 0.3,'( ______________ )',0,0,'C');





$pdf->Output("struk_wallpaper","I");

?>

