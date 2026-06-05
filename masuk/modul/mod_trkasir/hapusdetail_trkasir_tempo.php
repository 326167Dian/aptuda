<?php
include "../../../configurasi/koneksi.php";

$id_tempo  = $_POST['id_tempo'];
mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM trkasir_tempo WHERE id_tempo = '$id_tempo'");

?>
