<?php
require "intranetlib.php";
function RefreshSession() {
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_COOKIE['auth_token'])) {

        $data = decryptData();
        $_SESSION['logged'] = $data['logged'];
        $_SESSION['loggedinfo'] = $data['loggedinfo'];
        $_SESSION['op'] = $data['op'];

        if (!isset($_COOKIE['auth_regen'])) {
        
            $_SESSION['logged'] = generateToken();
            
            $regeneration_expiry_time = time() + ( 12 * 3600);
            setcookie('auth_token', true, $regeneration_expiry_time,'/','');
            /*
            setcookie('auth_regen', true, [
                'expires' => $regeneration_expiry_time,
                'path' => '/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            */
        }

        storeToken($_SESSION['logged'],$_SESSION['loggedinfo'][0]['samaccountname'][0]);

        $new_expiry_time = time() + (7 * 24 * 3600); 

        $auth_token = encryptData($_SESSION['logged'],$_SESSION['loggedinfo'], $_SESSION['op']);
        setcookie('auth_token', $auth_token, $new_expiry_time,'/','');
        /*setcookie('auth_token', $auth_token, [
            'expires' => $new_expiry_time,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);*/
        return true;
    }
    return false;
    //echo json_encode(['success' => true]);
    exit();
}

function isToken($token) {
    return (bool) preg_match('/^[a-f0-9]{64}$/i', $token); 
}
function generateToken() {
    return bin2hex(random_bytes(32)); // Generate a 64-character hexadecimal token
}

function storeToken($token, $username) {
    try {
        $db = new SQLite3('../../contatos/protected/sessions.db');
        $stmt = $db->prepare('INSERT OR REPLACE INTO sessions (username, token) VALUES (:username, :token)');
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':token', $token, SQLITE3_TEXT);
        $stmt->execute();
        $db->close();
    } 
    catch (\Throwable $th) {
        echo json_encode($th->getMessage());
        //deadpage("SQLite3",$th->getMessage());
    }
}


RefreshSession();
?>