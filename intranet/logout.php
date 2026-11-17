<?php

function removeSessionToken($username) {
    $db = new SQLite3('protected/sessions.db');

    $stmt = $db->prepare('DELETE FROM sessions WHERE username = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->execute();

    $db->close();
}

session_start();
@removeSessionToken($_SESSION['loggedinfo'][0]['samaccountname'][0]);
    if(isset($_SESSION['logged']))
        unset($_SESSION['logged']); 
    if(isset($_SESSION['loggedinfo']))
        unset($_SESSION['loggedinfo']);
    if(isset($_SESSION['op']))
        unset($_SESSION['op']);

setcookie('auth_token', '', time() - 3600, '/', '', true, true); 

setcookie('auth_regen', '', time() - 3600, '/', '', true, true); 

header("location: index.php");
?>