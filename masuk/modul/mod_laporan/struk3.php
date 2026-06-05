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
$ukuran1 = 10; //setingan kertas
$ukuran2 = 43; //garis akhir tabel

$tambahukuran = $jumlahdetail * 0.4;
$tinggikertas = $ukuran1 + $tambahukuran;
$posisigaris = $ukuran2 + $tambahukuran;


//$pdf = new FPDF("P","cm","A4");
$pdf = new FPDF("P","cm",array($tinggikertas,33));
$pdf->SetMargins(-0.3,1,0);
$pdf->AliasNbPages();
$pdf->AddPage();

//$pdf->Image('../../images/mmd.jpg',1,1.5,5,2);
//HEADER 1
$pdf->Line(0.7, 6.2, 32, 6.2); //horisontal bawah

$pdf->Line(0.7, 7.1, 32, 7.1); //judul tabel atas
$pdf->Line(0.7, 7.2, 32, 7.2); //judul tabel atas

$pdf->ln(1.4);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','B',20);
$pdf->Cell(33,0.4,'FAKTUR PENJUALAN',0,1,'C');

$pdf->ln(1.4);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(18,0.4,$rh['satu'],0,0,'L');

$pdf->SetFont('Arial','',14);
$pdf->Cell(3, 0.4, 'Kode Transaksi', 0, 0, 'L');
$pdf->Cell(2, 0.4, ':', 0, 0, 'R');
$pdf->Cell(7, 0.4, $r1['kd_trkasir'], 0, 0, 'L');

$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(18,0.4,$rh['dua'],0,0,'L');
$pdf->Cell(3, 0.4, 'Tanggal', 0, 0, 'L');
$pdf->Cell(2, 0.4, ':', 0, 0, 'R');
$pdf->Cell(7, 0.4, tgl_indo($r1['tgl_trkasir']), 0, 0, 'L');

$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(18,0.4,$rh['tiga'],0,0,'L');
$pdf->Cell(3, 0.4, 'Nama Pelanggan', 0, 0, 'L');
$pdf->Cell(2, 0.4, ':', 0, 0, 'R');
$pdf->Cell(7, 0.4, $r1['nm_pelanggan'], 0, 0, 'L');


$pdf->ln(0.7);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(2, 1, 'No.', 0, 0, 'C');
$pdf->Cell(4, 1, 'Kode Barang', 0, 0, 'C');
$pdf->Cell(10, 1, 'Nama Barang', 0, 0, 'C');
$pdf->Cell(2, 1, 'Jumlah', 0, 0, 'R');
$pdf->Cell(3, 1, 'Satuan', 0, 0, 'L');
$pdf->Cell(3, 1, 'Harga', 0, 0, 'C');
$pdf->Cell(3, 1, 'Disc', 0, 0, 'C');
$pdf->Cell(3, 1, 'Total', 0, 1, 'C');

$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);

	$no=1;
	$query=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir_detail WHERE kd_trkasir='$_GET[kd_trkasir]'
	ORDER BY id_dtrkasir ASC");

$garis = 5.9;
while($r2=mysqli_fetch_array($query)){
    $st[] = $r2['hrgttl_dtrkasir'];
    $gt = array_sum($st);
    // $disc = (($gt-$r1['ttl_trkasir'])/$gt)*100;
    $disc = format_rupiah($r2['disc']);
    $tagihan = format_rupiah($r1['ttl_trkasir']);
    $subtotal = format_rupiah($gt);
	$pdf->SetX(0.6);
	
    $text1 = substr($r2['nmbrg_dtrkasir'], 0,32);
    $text2 = substr($r2['nmbrg_dtrkasir'], 32,65);
    $text3 = strlen($r2['nmbrg_dtrkasir']);
    
    if ($text3 > 32){
        $pdf->Cell(2, 0.6, $no.'.', 0, 0, 'C');
        $pdf->Cell(4, 0.6, $r2['kd_barang'], 0, 0, 'L');
        $pdf->Cell(10, 0.6, $text1, 0, 0, 'L');
        $pdf->Cell(2, 0.6, $r2['qty_dtrkasir'], 0, 0, 'C');
        $pdf->Cell(3, 0.6, $r2['sat_dtrkasir'], 0, 0, 'L');
        $pdf->Cell(3, 0.6, format_rupiah($r2['hrgjual_dtrkasir']), 0, 0, 'R');
        $pdf->Cell(3, 0.6, $disc, 0, 0, 'C');
        $pdf->Cell(3, 0.6, format_rupiah($r2['hrgttl_dtrkasir']), 0, 1, 'R');
        
    	$pdf->SetX(0.6);
        $pdf->Cell(2, 0.6, '', 0, 0, 'C');
        $pdf->Cell(4, 0.6, '', 0, 0, 'L');
        $pdf->Cell(10, 0.6, $text2, 0, 0, 'L');
        $pdf->Cell(2, 0.6, '', 0, 0, 'R');
        $pdf->Cell(3, 0.6, '', 0, 0, 'L');
        $pdf->Cell(3, 0.6, '', 0, 0, 'R');
        $pdf->Cell(3, 0.6, '', 0, 0, 'C');
        $pdf->Cell(3, 0.6, '', 0, 1, 'R');
        
        $garis = $garis + 1.2;
    } else {
        $pdf->Cell(2, 0.6, $no.'.', 0, 0, 'C');
        $pdf->Cell(4, 0.6, $r2['kd_barang'], 0, 0, 'L');
        $pdf->Cell(10, 0.6, $text1, 0, 0, 'L');
        $pdf->Cell(2, 0.6, $r2['qty_dtrkasir'], 0, 0, 'C');
        $pdf->Cell(3, 0.6, $r2['sat_dtrkasir'], 0, 0, 'L');
        $pdf->Cell(3, 0.6, format_rupiah($r2['hrgjual_dtrkasir']), 0, 0, 'R');
        $pdf->Cell(3, 0.6, $disc, 0, 0, 'C');
        $pdf->Cell(3, 0.6, format_rupiah($r2['hrgttl_dtrkasir']), 0, 1, 'R');
        
        $garis = $garis + 0.6;
    }

	$no++;
// 	$garis = (1.6 * ($no))+4.5;
}

$garis = $garis - 42;
$pdf->Line(0.7, $garis, 32, $garis);

$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(3.5, 0.6, 'Metode bayar', 0, 0, 'L');
$pdf->Cell(2, 0.6, ':', 0, 0, 'R');
$pdf->Cell(3, 0.6, $r1['nm_carabayar'], 0, 0, 'L');

$pdf->SetFont('Arial','',14);
$pdf->Cell(13.5, 0.6, 'Sub Total : ', 0, 0, 'R');
$pdf->Cell(8, 0.6, $subtotal, 0, 1, 'R');

$pdf->SetX(0.6);
$pdf->SetFont('Arial','',14);
$pdf->Cell(3.5, 0.6, 'petugas ', 0, 0, 'L');
$pdf->Cell(2, 0.6, ':', 0, 0, 'R');
$pdf->Cell(3, 0.6, $r1['petugas'], 0, 0, 'L');

$pdf->Cell(13.5, 0.6, 'Disc Faktur : ', 0, 0, 'R');
$pdf->Cell(8, 0.6, format_rupiah($r1['diskon2']), 0, 1, 'R');

$pdf->SetX(0.6);
$pdf->Cell(3.5, 0.6, 'Transfer BSI no Rek 1089057920 an Badiah ', 0, 0, 'L');
$pdf->Cell(18.5, 0.6, 'Total : ', 0, 0, 'R');
$pdf->Cell(8, 0.6, format_rupiah($r1['ttl_trkasir']), 0, 1, 'R');

$pdf->Output("struk_wallpaper","I");

?>

