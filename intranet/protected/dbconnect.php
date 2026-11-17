<?php

function deadpage($title= 'Error.',$message = 'Something went wrong. Please Return.',$type = 'dead'){
    setcookie('e_title',$title,time() + 30 * 60);
    //12759 setcookie('e_message','Something went wrong. Please Return.',time() + 30 * 60);
    setcookie('e_message',$message,time() + 30 * 60);
    setcookie('e_type',$type,time() + 30 * 60);
    error_log("title ->".$title."  message -> ".$message);
    header("location: error.php");
}


function sqlconnect(){
    try {
        $dbname = getenv('DB_NAME') ?: 'CHANGE_ME_DB';
        $host = getenv('DB_HOST') ?: 'db.example.com';
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $user = getenv('DB_USER') ?: 'CHANGE_ME_USER';
        $pass = getenv('DB_PASSWORD') ?: 'CHANGE_ME_PASSWORD';
        $db = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        if ($db) {
            return $db;
        }
    } catch (PDOException $th) {
        deadpage("Connection failed. #001", $th->getMessage());
    }
}


function getparam() {
    try {
        $conn = sqlconnect();
        $query = "SELECT * FROM intranet_param WHERE id=1";
        $res = $conn->query($query);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row;
    } catch (PDOException $th) {
        deadpage("Function failed. #008",$th->getMessage());
    }

}

function updparam() {
    try {
        if (!isset($_POST['submit'])) {
            return;
        }

        $conn = sqlconnect();
        $prm = getparam();
        $fis = array(); 
        $vas = array();
    
        foreach($_POST as $field=>$val) {
            if($field!="submit"){
                if ($field=="email_sender_pass") {
                    $fis[] = $field; 
                    $vas[] = encryptPassword($val, $prm['secretkey']);
                }else{
                    $fis[] = $field; 
                    $vas[] = $val;
                }
            }
        }

        $query = "UPDATE intranet_param SET ";

        if (count($fis)<1){
            return;
        }
        for ($i=0; $i < count($fis) ; $i++) { 
            $query = $query.$fis[$i]."='".$vas[$i]."', ";
        }
        $query = substr($query, 0, -2)." WHERE id=1";
        if ($conn->query($query)) {
            setcookie('updparam','1',time()+60);
            header("location: settings.php");
        }
        
    } catch (\Throwable $th) {
        //Fus throw $th;
    }
}
function defaultparam() {
    $conn = sqlconnect();

    $query = "DELETE FROM intranet_param WHERE id=1";
    $conn->query($query);

    $query = "INSERT INTO intranet_param (id) VALUES (1)";
    $conn->query($query);

    setcookie('updparam','1',time()+60);
    header("location: settings.php");
}

function validID($conn,$id) : bool {
    try {
        $query = "SELECT DISTINCT EXISTS(SELECT * FROM form_values WHERE id_pedido = ".$id.")AS op;";
        $res = $conn->query($query);
        $op = $res->fetch();
        if ($op[0]==1) {
            return true;
        }else{
            deadpage("Id not found. #003","Id not found in database");
            return false;
        }
    } catch (\Throwable $th) {
        deadpage("Query Failed. #0011",$th->getMessage());
        return false;
    }
}

function encryptPassword($password, $key) {
    if ($password == "") {
        return $password;
    }
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($password, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decryptPassword($encrypted, $key) {
    list($encrypted_data, $iv) = explode('::', base64_decode($encrypted), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}