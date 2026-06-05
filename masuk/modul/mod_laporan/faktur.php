<?php
session_start();
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else {

    include "../../../configurasi/koneksi.php";
    require('../../assets/pdf/fpdf.php');
    include "../../../configurasi/fungsi_indotgl.php";
    include "../../../configurasi/fungsi_rupiah.php";

//ambil header
    $ah = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM setheader ");
    $rh = mysqli_fetch_array($ah);

    $dt = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir
JOIN carabayar ON trkasir.id_carabayar = carabayar.id_carabayar
WHERE trkasir.kd_trkasir='$_GET[kd_trkasir]'");
    $r1 = mysqli_fetch_array($dt);
    $niltransaksi = $r1['nilai_transaksi'];


    $jumlahdetail = mysqli_num_rows(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir_detail WHERE kd_trkasir='$_GET[kd_trkasir]'"));

// $ukuran1 = 14.7; //setingan kertas
// $ukuran2 = 5.4; //garis akhir tabel
//$ukuran1 = 33; //setingan kertas
//$ukuran2 = 43; //garis akhir tabel

//$tambahukuran = $jumlahdetail * 0.4;
//$tinggikertas = $ukuran1 + $tambahukuran;
//$posisigaris = $ukuran2 + $tambahukuran;


    $pdf = new FPDF("P", "cm", "A4");
    $pdf->SetMargins(0, 0.8, 0);
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->Image('../../images/logo.png', 1, 1, 1.5, 1.5);
//HEADER 1
    $pdf->SetLineWidth(0.1);
    $pdf->Line(0.7, 4.2, 20.5, 4.2); //horisontal bawah


    $pdf->SetLineWidth(0.05);
    $pdf->Line(0.7, 5.7, 20.5, 5.7); //garis tabel atas
    $pdf->Line(0.7, 6.3, 20.5, 6.3); //garis tabel bawah

    $pdf->ln(0.1);
    $pdf->SetX(3.5);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(16.5, 0.5, 'APOTEK UDA', 0, 1, 'L');

    $pdf->ln(0.1);
    $pdf->SetX(3.5);
    $pdf->SetFont('Arial', 'b', 10);
    $pdf->Cell(8, 0.1, 'SIA : 503/053/DPMPTSP/KAPT/VII/2021', 0, 0, 'L');


    $pdf->ln(0.3);
    $pdf->SetX(3.5);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(8, 0.2, 'Jl. Mayor Oking Blok A6', 0, 1, 'L');

    $pdf->ln(0.1);
    $pdf->SetX(3.5);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(8, 0.2, 'Kota Bekasi', 0, 1, 'L');

    $pdf->ln(0.1);
    $pdf->SetX(3.5);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(8, 0.2, 'Whatsapp : 085100480611 /  082112212002', 0, 1, 'L');


    $pdf->ln(0.4);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(4, 0.1, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0.3, 0.1, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(10, 0.1, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(2, 0.2, 'No. Faktur', 0, 0, 'L');
    $pdf->Cell(0.3, 0.2, ':', 0, 0, 'R');
    $pdf->Cell(6, 0.2, $r1['kd_trkasir'], 0, 1, 'L');

    $pdf->ln(0.2);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(4, 0.1, "Pembayaran Non Tunai", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0.3, 0.1, ":", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(10, 0.1, '', 0, 0, 'L');
    $pdf->Cell(2, 0.2, 'Tgl Faktur', 0, 0, 'L');
    $pdf->Cell(0.3, 0.2, ':', 0, 0, 'R');
    $pdf->Cell(6, 0.2, tgl_indo($r1['tgl_trkasir']), 0, 1, 'L');

    $pdf->ln(0.2);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(4, 0.1, $rh['enam'], 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0.3, 0.1, '', 0, 0, 'L');
    $pdf->Cell(10, 0.1, '', 0, 0, 'L');
    $pdf->Cell(2, 0.2, 'Kasir', 0, 0, 'L');
    $pdf->Cell(0.3, 0.2, ':', 0, 0, 'R');
    $pdf->Cell(6, 0.2, $r1['petugas'], 0, 1, 'L');

    $pdf->ln(0.6);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(2, 0.1, 'Kepada Yth', 0, 1, 'L');

    $pdf->ln(0.2);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(20, 0.2, $r1['nm_pelanggan'], 0, 1, 'L');

    $pdf->ln(0.2);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(3.3, 0.2, strip_tags($r1['alamat_pelanggan']), 0, 1, 'L');

    $pdf->ln(0.3);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(1, 0.4, 'No.', 0, 0, 'C');
    $pdf->Cell(10, 0.4, 'Nama Barang', 0, 0, 'C');
    $pdf->Cell(2, 0.4, 'Sat', 0, 0, 'C');
    $pdf->Cell(1, 0.4, 'Qty', 0, 0, 'C');
    $pdf->Cell(2, 0.4, 'Harga', 0, 0, 'R');
    $pdf->Cell(2, 0.4, 'Disc', 0, 0, 'C');
    $pdf->Cell(2, 0.4, 'Sub total', 0, 1, 'R');

    $pdf->ln(0.3);
    $pdf->SetX(0.6);
    $pdf->SetFont('Times', '', 9);

    $no = 1;
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir_detail WHERE kd_trkasir='$_GET[kd_trkasir]'
	ORDER BY nmbrg_dtrkasir ASC");

    $garis = 6.1;
    while ($r2 = mysqli_fetch_array($query)) {
        $st[] = $r2['hrgttl_dtrkasir'];
        $gt = array_sum($st);
        // $disc = (($gt-$r1['ttl_tr5kasir'])/$gt)*100;
        $disc = format_rupiah($r2['disc']);
        $tagihan = format_rupiah($r1['ttl_trkasir']);
        $subtotal = format_rupiah($gt);
        $pdf->SetX(0.6);

        $text1 = substr($r2['nmbrg_dtrkasir'], 0, 60);
        $text2 = substr($r2['nmbrg_dtrkasir'], 60, 120);
        $text3 = strlen($r2['nmbrg_dtrkasir']);

        if ($text3 > 60) {
            $pdf->Cell(1, 0.4, $no . '.', 0, 0, 'C');
            $pdf->Cell(10, 0.4, $text1, 0, 0, 'L');
            $pdf->Cell(2, 0.4, $r2['sat_dtrkasir'], 0, 0, 'R');
            $pdf->Cell(1, 0.4, $r2['qty_dtrkasir'], 0, 0, 'L');
            $pdf->Cell(2, 0.4, format_rupiah($r2['hrgjual_dtrkasir']), 0, 0, 'R');
            $pdf->Cell(2, 0.4, $disc, 0, 0, 'C');
            $pdf->Cell(2, 0.4, format_rupiah($r2['hrgttl_dtrkasir']), 0, 1, 'R');

            $pdf->SetX(0.6);
            $pdf->Cell(1, 0.4, '', 0, 0, 'C');
            $pdf->Cell(10, 0.4, $text2, 0, 0, 'L');
            $pdf->Cell(2, 0.4, '', 0, 0, 'R');
            $pdf->Cell(1, 0.4, '', 0, 0, 'L');
            $pdf->Cell(2, 0.4, '', 0, 0, 'R');
            $pdf->Cell(2, 0.4, '', 0, 0, 'C');
            $pdf->Cell(2, 0.4, '', 0, 1, 'R');

            $garis = $garis + 1.2;
        } else {
            $pdf->Cell(1, 0.4, $no . '.', 0, 0, 'C');
            $pdf->Cell(10, 0.4, $r2['nmbrg_dtrkasir'], 0, 0, 'L');
            $pdf->Cell(2, 0.4, $r2['sat_dtrkasir'], 0, 0, 'C');
            $pdf->Cell(1, 0.4, $r2['qty_dtrkasir'], 0, 0, 'C');
            $pdf->Cell(2, 0.4, format_rupiah($r2['hrgjual_dtrkasir']), 0, 0, 'R');
            $pdf->Cell(2, 0.4, $disc, 0, 0, 'C');
            $pdf->Cell(2, 0.4, format_rupiah($r2['hrgttl_dtrkasir']), 0, 1, 'R');

            $garis = $garis + 0.6;
        }

        $no++;
    }
    $garis = $garis + 0.4;
    $pdf->SetX(0.5);
    $pdf->SetFont('Arial', 'U');
    $pdf->Cell(21, 0.2, '...............................................................................................................................................................................................................................', 0, 0, 'C');

    $pdf->ln(0.4);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(8, 0.3, 'Terbilang :', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(9, 0.3, 'Total Transaksi : ', 0, 0, 'R');
    $pdf->Cell(3, 0.3, $subtotal, 0, 1, 'R');

    //$niltransaksi = $r1['nilai_transaksi'];
    $diskonfaktur = $gt - $niltransaksi;

    if (terbilang($niltransaksi) > 66) {
        $pdf->SetX(0.6);
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->Cell(8, 0.4, $terbilang1, 0, 0, 'L');

        $pdf->SetX(0.6);
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->Cell(8, 0.4, $terbilang2 . ' Rupiah.', 0, 0, 'L');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(9, 0.4, 'Diskon Transaksi : ', 0, 0, 'R');
        $pdf->Cell(3, 0.4, format_rupiah($diskonfaktur), 0, 1, 'R');

    } else {
        $pdf->SetX(0.6);
        $pdf->SetFont('Arial', 'I', 11);
        $pdf->Cell(8, 0.4, terbilang($niltransaksi) . ' Rupiah.', 0, 0, 'L');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(9, 0.4, 'Diskon Transaksi : ', 0, 0, 'R');
        $pdf->Cell(3, 0.4, format_rupiah($diskonfaktur), 0, 1, 'R');

    }

    if ($r1['id_carabayar'] == 1) {
        $dana = format_rupiah($niltransaksi);
    } elseif ($r1['id_carabayar'] == 2)
        {$dana = 'Menunggu Validasi'; } 
      elseif ($r1['id_carabayar'] == 3)
       {$dana = 'Belum diterima'; }

    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(17, 0.4, 'Total Setelah Diskon : ', 0, 0, 'R');
    $pdf->Cell(3, 0.4, format_rupiah($niltransaksi), 0, 1, 'R');
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(17, 0.4, 'Total Pembayaran : ', 0, 0, 'R');
    $pdf->Cell(3, 0.4, $dana, 0, 1, 'R');

    $pdf->ln(0.4);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(10, 0.4, 'Catatan/Ketentuan :', 'LTR', 0, 'C');
    $pdf->Cell(5, 0.4, 'Penerima', 0, 0, 'C');
    $pdf->Cell(5, 0.4, 'Dicetak Oleh', 0, 0, 'C');

    $pdf->ln(0.4);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(2, 0.4, 'Pembayaran', 'L', 0, 'L');
    $pdf->Cell(0.3, 0.4, ':', 0, 0, 'L');
    $pdf->Cell(2, 0.4, $r1['nm_carabayar'], 0, 0, 'L');
    $pdf->Cell(2, 0.4, 'Pengirim', 0, 0, 'L');
    $pdf->Cell(0.3, 0.4, ':', 0, 0, 'L');
    $pdf->Cell(3.4, 0.4, $r1['ket_trkasir'], 'R', 1, 'L');

    $pdf->ln(0.1);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(2, 0.1, 'Faktur ini sah bila pembayaran telah diterima', 'L', 0, 'L');
    $pdf->Cell(0.3, 0.1, '', 0, 0, 'L');
    $pdf->Cell(7.7, 0.1, '', 'R', 1, 'L');

    $pdf->ln(0.1);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(10, 0.1, '', 'LBR', 0, 'L');
    $pdf->Cell(10, 0.1, '', 0, 1, 'L');

    $pdf->ln(0.5);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(10, 0.3, '', 0, 0, 'L');
    $pdf->Cell(5, 0.3, '( ____________________ )', 0, 0, 'C');

    $petugas = $_SESSION['namalengkap'];
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(5, 0.3, $petugas, 0, 1, 'C');

    $pdf->ln(0.1);
    $pdf->SetX(0.6);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(15, 0.3, '', 0, 0, 'L');
   


    $pdf->Output("faktur_penjualan_" . $r1['nm_pelanggan'] . $r1['tgl_trkasir'], "I");
}
?>

