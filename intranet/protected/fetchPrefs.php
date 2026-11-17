<?php
require "../protected/dbconnect.php";

try {
    if (!isset($_SESSION)) {
        session_start();
    }
    $username = "guest";
    if (isset($_SESSION['loggedinfo'][0]['mail'][0])){
        $username  = $_SESSION['loggedinfo'][0]['mail'][0];
    }

    /*$conn = sqlconnect();
    $query = $conn->prepare("SELECT * FROM users WHERE username=:username");
    $query->bindValue(':username', $username);

    $result = $query->execute();

    $row = $result->fetchArray(MYSQL_ASSOC);*/
    $conn = sqlconnect();
    $query = $conn->prepare("SELECT * FROM users WHERE username=:username");
    $query->bindValue(':username', $username);
    $query->execute();

    $row = $query->fetch(PDO::FETCH_ASSOC);

    if (!isset($row['lang'])) {
        echo 'null';
        exit;
    }

    echo $row['lang']==null||$row['lang']=="" ? 'null' : $row['lang'];

} catch (\Throwable $th) {
    echo $th;
}
