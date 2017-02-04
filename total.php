<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "mariadb-134.wc1.ord1.stabletransit.com";
$user = "833125_toddyp";
$pass = "r8xr95D)fW-px8Yb";
$name = "833125_toddyp";

$link = mysqli_connect($host, $user, $pass) or die(mysqli_error());
mysqli_select_db($link, $name) or die(mysqli_error());

$sql = "select count(*) as total from wp_dhvc_form_entry_data";

$exe = mysqli_query($link, $sql) or die(mysqli_error());

$row = mysqli_fetch_array($exe);
echo "Registro Toddy - ".$row['total'];
?>