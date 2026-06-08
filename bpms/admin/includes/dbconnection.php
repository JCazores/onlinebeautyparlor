<?php
$host = 'acela.proxy.rlwy.net';
$port = 58509;
$user = 'root';
$pass = 'oCZnrPaBlUHwYSSosvPTWAFRnKiSwQJI';
$db   = 'rfbs';

$con = mysqli_connect($host, $user, $pass, $db, $port);
if(mysqli_connect_errno()){
    echo "Connection Fail: ".mysqli_connect_error();
    exit();
}
?>