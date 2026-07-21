<?php
include "../../../configurasi/koneksi.php";

$conn     = $GLOBALS["___mysqli_ston"];
$id_tempo = mysqli_real_escape_string($conn, $_POST['id_tempo']);
mysqli_query($conn, "DELETE FROM trkasir_tempo WHERE id_tempo = '$id_tempo'");
?>
