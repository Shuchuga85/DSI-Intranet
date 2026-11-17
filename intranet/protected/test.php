<?php
/*
require "dbconnect.php";

$id = 13;
if(!($conn = sqlconnect())){
    echo "Connection failed error #001";
    return;
}
$query = "SELECT list_fields FROM checklist WHERE utilizador='".$id."'";
if(!($result = mysqli_query($conn, $query))){
    echo "Request Failed, error #";
    return;
}
$row = mysqli_fetch_assoc($result);
$fields = $row['list_fields'];
$n = 2;
function check(){
    global $fields, $n, $f; 
    $quant = substr_count($fields,"field".$n.".".$f);
    if($quant >0)
        echo "selected";
}

$f = 0;
$n = 1;
check();
$f = 0;
$n = 2;
check();
check();
check();
$f = 0;
$n = 3;
check();
$f = 0;
$n = 4;
check();*/
?>