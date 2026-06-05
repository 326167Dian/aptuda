<script>
function confirmdelete(delUrl) {
if (confirm("Anda yakin ingin menghapus?")) {
document.location = delUrl;
}
}
</script>
<script type="text/javascript">
    var grafikTrbmasukData = [];

    window.onload = function() { jam(); drawGrafikTrbmasuk(); }

    function jam() {
        var e = document.getElementById('jam'),
            d = new Date(), h, m, s;
        h = d.getHours();
        m = set(d.getMinutes());
        s = set(d.getSeconds());

        e.innerHTML = h +':'+ m +':'+ s;

        setTimeout('jam()', 1000);
    }

    function drawGrafikTrbmasuk() {
        if (typeof Morris === 'undefined') {
            return;
        }

        var data = window.grafikTrbmasukData || [];
        if (!data || !data.length) {
            return;
        }

        new Morris.Line({
            element: 'grafik_trbmasuk',
            data: data,
            xkey: 'hari',
            ykeys: ['BulanIni', 'BulanLalu'],
            labels: ['Bulan Ini', 'Bulan Lalu'],
            lineColors: ['#0073b7', '#f39c12'],
            pointFillColors: ['#0073b7', '#f39c12'],
            hideHover: 'auto',
            resize: true,
            parseTime: false,
            xLabelAngle: 35
        });
    }

    function set(e) {
        e = e < 10 ? '0'+ e : e;
        return e;
    }
</script>
<?php
include "../configurasi/koneksi.php";
include "../configurasi/library.php";
include "../configurasi/fungsi_indotgl.php";
include "../configurasi/fungsi_rupiah.php";
include "../configurasi/fungsi_combobox.php";
include "../configurasi/class_paging.php";
$tgl_awal = date('d-m-Y');

// Bagian Home
if ($_GET['module']=='home'){

    $selectedMonth = isset($_GET['bulan']) ? intval($_GET['bulan']) : intval(date('m'));
    $selectedYear = isset($_GET['tahun']) ? intval($_GET['tahun']) : intval(date('Y'));
    $chartMode = isset($_GET['tampilan']) ? $_GET['tampilan'] : 'pembelian';
    $chartMode = $chartMode === 'penjualan' ? 'penjualan' : 'pembelian';
    $chartModeLabel = $chartMode === 'penjualan' ? 'Penjualan' : 'Pembelian';

    $selectedMonth = max(1, min(12, $selectedMonth));
    $selectedYear = max(2000, min(2100, $selectedYear));
    $selectedMonth = str_pad($selectedMonth, 2, '0', STR_PAD_LEFT);
    $currentPeriod = "$selectedYear-$selectedMonth";
    $currentStart = "$currentPeriod-01";
    $currentEnd = date('Y-m-t', strtotime($currentStart));
    $previousStart = date('Y-m-01', strtotime($currentStart.' -1 month'));
    $previousEnd = date('Y-m-t', strtotime($previousStart));
    $previousPeriod = date('Y-m', strtotime($previousStart));

    if ($chartMode === 'penjualan') {
        $dateField = 'tgl_trkasir';
        $totalField = 'nilai_transaksi';
        $tableName = 'trkasir';
    } else {
        $dateField = 'tgl_trbmasuk';
        $totalField = 'ttl_trbmasuk';
        $tableName = 'trbmasuk';
    }

    $sqlGrafik = "SELECT DATE_FORMAT($dateField, '%Y-%m-%d') AS tgl, SUM($totalField) AS total ";
    $sqlGrafik .= "FROM $tableName ";
    $sqlGrafik .= "WHERE $dateField BETWEEN '$previousStart' AND '$currentEnd' ";
    $sqlGrafik .= "GROUP BY DATE_FORMAT($dateField, '%Y-%m-%d') ";
    $sqlGrafik .= "ORDER BY tgl";
    $hasilGrafik = mysqli_query($GLOBALS['___mysqli_ston'], $sqlGrafik);
    $currentTotals = array();
    $previousTotals = array();
    while ($rg = mysqli_fetch_assoc($hasilGrafik)) {
        if (strpos($rg['tgl'], $currentPeriod) === 0) {
            $currentTotals[$rg['tgl']] = (float) $rg['total'];
        } elseif (strpos($rg['tgl'], $previousPeriod) === 0) {
            $previousTotals[$rg['tgl']] = (float) $rg['total'];
        }
    }

    $currentTotal = array_sum($currentTotals);
    $previousTotal = array_sum($previousTotals);
    $difference = $currentTotal - $previousTotal;
    $changePercent = $previousTotal > 0 ? round(($difference / $previousTotal) * 100, 2) : ($currentTotal > 0 ? 100 : 0);

    $daysCurrent = date('t', strtotime($currentStart));
    $daysPrevious = date('t', strtotime($previousStart));
    $maxDays = max($daysCurrent, $daysPrevious);
    $chartData = array();
    for ($day = 1; $day <= $maxDays; $day++) {
        $label = str_pad($day, 2, '0', STR_PAD_LEFT);
        $currentKey = "$currentPeriod-$label";
        $previousKey = "$previousPeriod-$label";
        $chartData[] = array(
            'hari' => $label,
            'BulanIni' => isset($currentTotals[$currentKey]) ? $currentTotals[$currentKey] : 0,
            'BulanLalu' => isset($previousTotals[$previousKey]) ? $previousTotals[$previousKey] : 0,
        );
    }
    $chartDataJson = json_encode($chartData);

    $bulanNama = array(
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    );

	?>
	  <!-- Small boxes (Stat box) -->
	
	   <div class="box-body">
			<h1>SISTEM INVENTORY FOR APOTEK</h1>
			<div class="row">
				<div class="callout callout-info"  style="margin:20px 20px 20px 20px">
					<h4><?php echo "Hai $_SESSION[namalengkap]"; ?></h4>
					<p><?php echo "Selamat datang di halaman SMART INVENTORY FOR APOTEK "; ?>
                        <BR>
                        Silahkan klik menu pilihan yang berada di sebelah kiri untuk mengelola aplikasi
					</p>
				</div>
			</div>
        <div class="row" style="margin:0 20px 20px 20px;">
            <div class="col-md-12">
                <form method="get" class="form-inline" style="margin-bottom:15px;">
                    <input type="hidden" name="module" value="home" />
                    <label style="margin-right:8px;">Bulan</label>
                    <select name="bulan" class="form-control" style="margin-right:10px;">
                        <?php foreach ($bulanNama as $num => $name) { ?>
                            <option value="<?php echo $num; ?>" <?php echo $selectedMonth === $num ? 'selected' : ''; ?>><?php echo $name; ?></option>
                        <?php } ?>
                    </select>
                    <label style="margin-right:8px; margin-left:15px;">Tahun</label>
                    <select name="tahun" class="form-control" style="margin-right:10px;">
                        <?php for ($y = date('Y') - 3; $y <= date('Y') + 1; $y++) { ?>
                            <option value="<?php echo $y; ?>" <?php echo $selectedYear === $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                        <?php } ?>
                    </select>
                    <label style="margin-right:8px; margin-left:15px;">Tampilan</label>
                    <select name="tampilan" class="form-control" style="margin-right:10px;">
                        <option value="pembelian" <?php echo $chartMode === 'pembelian' ? 'selected' : ''; ?>>Pembelian</option>
                        <option value="penjualan" <?php echo $chartMode === 'penjualan' ? 'selected' : ''; ?>>Penjualan</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Refresh</button>
                    <span style="margin-left:20px; color:#666;">Update terakhir: <?php echo date('Y-m-d H:i:s'); ?></span>
                </form>
            </div>
        </div>

        <div class="row" style="margin:0 20px 20px 20px;">
            <div class="col-md-4">
                <div style="background:#00c0ef;color:#fff;padding:20px;border-radius:6px;">
                    <div style="font-size:18px;">Total <?php echo $chartModeLabel; ?> Bulan Dipilih</div>
                    <div style="font-size:32px;font-weight:bold; margin-top:10px;">Rp <?php echo number_format($currentTotal,0,',','.'); ?></div>
                    <div style="margin-top:10px;"><?php echo $selectedMonthName.' '.$selectedYear; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div style="background:#f39c12;color:#fff;padding:20px;border-radius:6px;">
                    <div style="font-size:18px;">Total Bulan Lalu</div>
                    <div style="font-size:32px;font-weight:bold; margin-top:10px;">Rp <?php echo number_format($previousTotal,0,',','.'); ?></div>
                    <div style="margin-top:10px;"><?php echo $previousMonthName.' '.date('Y', strtotime($previousStart)); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div style="background:#dd4b39;color:#fff;padding:20px;border-radius:6px;">
                    <div style="font-size:18px;">Perubahan</div>
                    <div style="font-size:32px;font-weight:bold; margin-top:10px;"><?php echo ($changePercent >= 0 ? '+' : '') . $changePercent; ?>%</div>
                    <div style="margin-top:10px;">Rp <?php echo number_format($difference,0,',','.'); ?></div>
                </div>
            </div>
        </div>

        <div class="row" style="margin:0 20px 20px 20px;">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $chartModeLabel.' '.$selectedMonthName.' '.$selectedYear; ?> vs <?php echo $previousMonthName.' '.date('Y', strtotime($previousStart)); ?></h3>
                    </div>
                    <div class="box-body">
                        <p>X = tanggal <?php echo strtolower($chartModeLabel); ?>, Y = total Rupiah dari <code><?php echo $totalField; ?></code>.</p>
                        <div id="grafik_trbmasuk" style="height:340px;"></div>
                    </div>
                </div>
            </div>
        </div>
	 
  <?php
  echo "<p align=right>Login : $hari_ini, $tgl_awal <br>
   <b><span id=\"jam\" style=\"font-size:24\"></span></b></p>
  <span id='date'></span>, <span id='clock'></span></p>
  </div>
 
 ";
  
  echo '<script>window.grafikTrbmasukData = ' . $chartDataJson . ';</script>';
}
// Bagian user admin
elseif ($_GET['module']=='admin'){
    include "modul/mod_admin/admin.php";
}

// Bagian setheader
elseif ($_GET['module']=='setheader'){
    include "modul/mod_setheader/setheader.php";
}

// Bagian satuan
elseif ($_GET['module']=='satuan'){
    include "modul/mod_satuan/satuan.php";
}

  // Bagian jenisobat
  elseif ($_GET['module']=='jenisobat'){
      include "modul/mod_jenisobat/jenisobat.php";
  }
// Bagian profil
elseif ($_GET['module']=='profil'){
    include "modul/mod_profil/profil.php";
}

// Bagian pelanggan
elseif ($_GET['module']=='pelanggan'){
    include "modul/mod_pelanggan/pelanggan.php";
	
}

// Bagian supplier
elseif ($_GET['module']=='supplier'){
    include "modul/mod_supplier/supplier.php";
	
}

// Bagian carabayar
elseif ($_GET['module']=='carabayar'){
    include "modul/mod_carabayar/carabayar.php";
	
}

// Bagian barang
elseif ($_GET['module']=='barang'){
    include "modul/mod_barang/barang.php";

}
// elseif ($_GET['module']=='barcodebarang'){
//     include "modul/mod_barang/barcodebarang.php";

// }

// Bagian trbmasuk
elseif ($_GET['module']=='trbmasuk'){
    include "modul/mod_trbmasuk/trbmasuk.php";
	
}
// Bagian trbmasuk
elseif ($_GET['module']=='trbmasukpbf'){
    include "modul/mod_trbmasukpbf/trbmasukpbf.php";

}

// Bagian trkasir
elseif ($_GET['module']=='trkasir'){
    include "modul/mod_trkasir/trkasir.php";
}

// Bagian lapbarang
elseif ($_GET['module']=='lapbarang'){
    include "modul/mod_lapbarang/lapbarang.php";
}

// Bagian lappenjualan
elseif ($_GET['module']=='lappenjualan'){
    include "modul/mod_lappenjualan/lappenjualan.php";
}

// Bagian lap brg masuk
elseif ($_GET['module']=='lapbrgmasuk'){
    include "modul/mod_lapbrgmasuk/lapbrgmasuk.php";
}

// Bagian nilai stok barang
elseif ($_GET['module']=='lapstok'){
    include "modul/mod_lapstok/lapstok.php";

}// Bagian nilai stok kritis
elseif ($_GET['module']=='stok_kritis'){
    include "modul/mod_lapstok/stok_kritis.php";
}
// Bagian orders
elseif ($_GET['module']=='orders'){
    include "modul/mod_orders/orders.php";
}// Penjualan sebelumnya
elseif ($_GET['module']=='penjualansebelumnya'){
    include "modul/mod_trkasir/trkasir2.php";
}
//Laba Penjualan
elseif ($_GET['module']=='labapenjualan'){
    include "modul/mod_lappenjualan/labapenjualan.php";
}
//Terima Barang Masuk Oleh Manager
elseif ($_GET['module']=='byrkredit'){
    include "modul/mod_trbmasuk/byrkredit.php";
}//Stok Opname
elseif ($_GET['module']=='stokopname'){
    include "modul/mod_lapstok/stokopname.php";
}
elseif ($_GET['module']=='soharian'){
    include "modul/mod_lapstok/soharian.php";
}
elseif ($_GET['module']=='labajenisobat'){
    include "modul/mod_lappenjualan/labapenjualanjenisobat.php";
}
//koreksi stok karena sistem
elseif ($_GET['module']=='koreksistok'){
    include "modul/mod_lapstok/koreksistok.php";
}//input shiftkerja
elseif ($_GET['module']=='shiftkerja'){
    include "modul/mod_shiftkerja/shiftkerja.php";
}

// neraca
elseif ($_GET['module'] == 'neraca') {
    include "modul/mod_laporan/neraca.php";
}
//piutang
elseif ($_GET['module'] == 'piutang') {
    include "modul/mod_trkasir/piutang.php";
}
//laporan stok opname bulanan
  elseif ($_GET['module'] == 'lapstokopname') {
      include "modul/mod_laporan/laporan_stokopname.php";
  }
  // catatan
  elseif ($_GET['module'] == 'catatan') {
      include "modul/mod_catatan/catatan.php";
  }
?>
