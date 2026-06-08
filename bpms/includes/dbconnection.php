<?php
$host = getenv('DB_HOST') ?: 'acela.proxy.rlwy.net';
$port = intval(getenv('DB_PORT') ?: 58509);
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'oCZnrPaBlUHwYSSosvPTWAFRnKiSwQJI';
$db   = getenv('DB_NAME') ?: 'rfbs';

$con = mysqli_connect($host, $user, $pass, $db, $port);
if(mysqli_connect_errno()){
    echo "Connection Fail: ".mysqli_connect_error();
}
?>