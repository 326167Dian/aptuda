<?php
session_start();
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
    echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
    echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{

    include "../../../configurasi/koneksi.php";
    include "../../../configurasi/fungsi_thumb.php";
    include "../../../configurasi/library.php";

    $conn = $GLOBALS["___mysqli_ston"];

    $tampil_barang = mysqli_query($conn, "
        SELECT t1.tgl_trkasir, t1.tunai, COALESCE(t2.trans, 0) as trans
        FROM (
            SELECT tgl_trkasir, SUM(nilai_transaksi) as tunai
            FROM trkasir
            WHERE id_carabayar = 1 AND statusbayar = 'LUNAS'
                AND tgl_trkasir BETWEEN '2025-02-03' AND '2025-03-28'
            GROUP BY tgl_trkasir
        ) t1
        LEFT JOIN (
            SELECT tgl_trkasir, SUM(nilai_transaksi) as trans
            FROM trkasir
            WHERE id_carabayar = 2 AND statusbayar = 'LUNAS'
                AND tgl_trkasir BETWEEN '2025-02-03' AND '2025-03-28'
            GROUP BY tgl_trkasir
        ) t2 ON t2.tgl_trkasir = t1.tgl_trkasir
    ");

    $stmt = mysqli_prepare($conn, "
        INSERT INTO jurnal (tanggal, ket, petugas, idjenis, debit, kredit, carabayar, current)
        VALUES (?, ?, 'Team IT', '6', '0', ?, ?, '2025-04-04 00:00:01')
    ");
    mysqli_stmt_bind_param($stmt, "ssds", $p_tgl, $p_ket, $p_kredit, $p_carabayar);

    while ($r1 = mysqli_fetch_assoc($tampil_barang)) {
        $p_tgl = $r1['tgl_trkasir'];

        $p_ket = "Hasil Penjualan Tunai {$p_tgl}";
        $p_kredit = $r1['tunai'];
        $p_carabayar = 'TUNAI';
        mysqli_stmt_execute($stmt);

        $p_ket = "Hasil Penjualan Transfer {$p_tgl}";
        $p_kredit = $r1['trans'];
        $p_carabayar = 'TRANSFER';
        mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
    }
     

        
    header('location:../../media_admin.php?module=trkasir');

    
?>
    