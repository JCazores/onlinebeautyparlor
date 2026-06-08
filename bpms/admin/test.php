<?php
echo "PHP is working!";
$con = mysqli_connect('acela.proxy.rlwy.net', 'root', 'oCZnrPaBlUHwYSSosvPTWAFRnKiSwQJI', 'rfbs', 58509);
if(mysqli_connect_errno()){
    echo " DB Error: " . mysqli_connect_error();
} else {
    echo " DB Connected!";
}
?>