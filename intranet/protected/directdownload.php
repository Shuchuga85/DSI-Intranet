<?php
require 'protected/dbconnect.php';
require 'protected/formprint.php';

try {
    if (isset($_GET['idpedido'])) {
        $id = $_GET['idpedido'];
    }else{
        throw 'Missing Id';
    }
    
    $conn = sqlconnect();   

    printe($conn, $id);

} catch (\Throwable $th) {
    echo $th->getMessage();
}




?>