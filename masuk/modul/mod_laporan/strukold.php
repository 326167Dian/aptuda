<?php
include "../../../configurasi/koneksi.php";
require('../../assets/pdf/fpdf.php');
include "../../../configurasi/fungsi_indotgl.php";
include "../../../configurasi/fungsi_rupiah.php";

class PDF extends FPDF
{
    // Page header
    // function Header()
    // {
    //     // Logo
    //     $this->Image('logo.png',10,6,30);
    //     // Arial bold 15
    //     $this->SetFont('Arial','B',15);
    //     // Move to the right
    //     $this->Cell(80);
    //     // Title
    //     $this->Cell(30,10,'Title',1,0,'C');
    //     // Line break
    //     $this->Ln(20);
    // }
    
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

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


// $pdf = new FPDF("P","cm","Legal");
$pdf = new FPDF("P","cm","letter");
$pdf->SetMargins(0,0,0);
$pdf->AliasNbPages();
$pdf->AddPage();

//$pdf->SetAutoPageBreak(true);
//$pdf->Image('../../images/mmd.jpg',1,1.5,5,2);
//HEADER 1
$pdf->Line(0.7, 5, 21, 5); //horisontal bawah

$pdf->Line(0.7, 6, 21, 6); //judul tabel atas
$pdf->Line(0.7, 6.1, 21, 6.1); //judul tabel bawah

$pdf->ln(1.4);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','B',20);
$pdf->Cell(21,0.4,'FAKTUR PENJUALAN',0,1,'C');

$pdf->ln(1.4);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(10,0.4,$rh['satu'],0,0,'L');

$pdf->SetFont('Arial','',11);
$pdf->Cell(3, 0.4, 'Kode Transaksi', 0, 0, 'L');
$pdf->Cell(2, 0.4, ':', 0, 0, 'R');
$pdf->Cell(6, 0.4, $r1['kd_trkasir'], 0, 0, 'L');

$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',11);
$pdf->Cell(10,0.4,$rh['dua'],0,0,'L');
$pdf->Cell(3, 0.4, 'Tanggal', 0, 0, 'L');
$pdf->Cell(2, 0.4, ':', 0, 0, 'R');
$pdf->Cell(6, 0.4, tgl_indo($r1['tgl_trkasir']), 0, 0, 'L');

$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',11);
$pdf->Cell(10,0.4,$rh['tiga'],0,0,'L');
$pdf->Cell(3, 0.4, 'Nama Pelanggan', 0, 0, 'L');
$pdf->Cell(2, 0.4, ':', 0, 0, 'R');
$pdf->Cell(6, 0.4, $r1['nm_pelanggan'], 0, 0, 'L');


$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',11);
$pdf->Cell(1, 1, 'No.', 0, 0, 'C');
// $pdf->Cell(4, 1, 'Kode Barang', 0, 0, 'C');
$pdf->Cell(10, 1, 'Nama Barang', 0, 0, 'C');
$pdf->Cell(2, 1, 'Jml', 0, 0, 'R');
$pdf->Cell(1, 1, 'Sat', 0, 0, 'L');
$pdf->Cell(2, 1, 'Harga', 0, 0, 'C');
$pdf->Cell(2, 1, 'Disc', 0, 0, 'C');
$pdf->Cell(2, 1, 'Total', 0, 1, 'C');

$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',11);

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
	
    $text1 = substr($r2['nmbrg_dtrkasir'], 0,45);
    $text2 = substr($r2['nmbrg_dtrkasir'], 45,90);
    $text3 = strlen($r2['nmbrg_dtrkasir']);
    
    if ($text3 >45){
        $pdf->Cell(1, 0.6, $no.'.', 0, 0, 'C');
        $pdf->Cell(10, 0.6, $text1, 0, 0, 'L');
        $pdf->Cell(2, 0.6, $r2['qty_dtrkasir'], 0, 0, 'R');
        $pdf->Cell(1, 0.6, $r2['sat_dtrkasir'], 0, 0, 'L');
        $pdf->Cell(2, 0.6, format_rupiah($r2['hrgjual_dtrkasir']), 0, 0, 'R');
        $pdf->Cell(2, 0.6, $disc, 0, 0, 'C');
        $pdf->Cell(2, 0.6, format_rupiah($r2['hrgttl_dtrkasir']), 0, 1, 'R');
        
    	$pdf->SetX(0.6);
        $pdf->Cell(1, 0.6, '', 0, 0, 'C');
        $pdf->Cell(10, 0.6, $text2, 0, 0, 'L');
        $pdf->Cell(2, 0.6, '', 0, 0, 'R');
        $pdf->Cell(1, 0.6, '', 0, 0, 'L');
        $pdf->Cell(2, 0.6, '', 0, 0, 'R');
        $pdf->Cell(2, 0.6, '', 0, 0, 'C');
        $pdf->Cell(2, 0.6, '', 0, 1, 'R');
        
        $garis = $garis + 1.2;
    } else {
        $pdf->Cell(1, 0.6, $no.'.', 0, 0, 'C');
        // $pdf->Cell(4, 0.6, $r2['kd_barang'], 0, 0, 'L');
        $pdf->Cell(10, 0.6, $r2['nmbrg_dtrkasir'], 0, 0, 'L');
        $pdf->Cell(2, 0.6, $r2['qty_dtrkasir'], 0, 0, 'R');
        $pdf->Cell(1, 0.6, $r2['sat_dtrkasir'], 0, 0, 'L');
        $pdf->Cell(2, 0.6, format_rupiah($r2['hrgjual_dtrkasir']), 0, 0, 'R');
        $pdf->Cell(2, 0.6, $disc, 0, 0, 'C');
        $pdf->Cell(2, 0.6, format_rupiah($r2['hrgttl_dtrkasir']), 0, 1, 'R');
        
        $garis = $garis + 0.6;
    }

    $no++;
}
$garis = $garis + 0.4;
// $pdf->Line(0.7, $garis, 21, $garis);
$pdf->SetFont('Arial','U');
$pdf->Cell(21, 0.6, '..........................................................................................................................................................................................', 0, 0, 'C');

$pdf->ln(0.6);
$pdf->SetX(0.6);
$pdf->SetFont('Arial','',11);
$pdf->Cell(3.5, 0.6, 'Metode bayar', 0, 0, 'L');
$pdf->Cell(0.5, 0.6, ':', 0, 0, 'R');
$pdf->Cell(3, 0.6, $r1['nm_carabayar'], 0, 0, 'L');

$pdf->SetFont('Arial','',11);
$pdf->Cell(9.5, 0.6, 'Sub Total : ', 0, 0, 'R');
$pdf->Cell(3.5, 0.6, $subtotal, 0, 1, 'R');

$pdf->SetX(0.6);
$pdf->SetFont('Arial','',11);
$pdf->Cell(3.5, 0.6, 'petugas ', 0, 0, 'L');
$pdf->Cell(0.5, 0.6, ':', 0, 0, 'R');
$pdf->Cell(3, 0.6, $r1['petugas'], 0, 0, 'L');

$pdf->Cell(9.5, 0.6, 'Disc Faktur : ', 0, 0, 'R');
$pdf->Cell(3.5, 0.6, format_rupiah($r1['diskon2']), 0, 1, 'R');

$pdf->SetX(0.6);
$pdf->Cell(3.5, 0.6, 'Transfer BSI no Rek 1089057920 an Badiah ', 0, 0, 'L');
$pdf->Cell(13, 0.6, 'Total : ', 0, 0, 'R');
$pdf->Cell(3.5, 0.6, format_rupiah($r1['ttl_trkasir']), 0, 1, 'R');

$pdf->Output("struk_wallpaper","I");

?>

