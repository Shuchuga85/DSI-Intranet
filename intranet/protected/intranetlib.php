<?php

include "popup.php";

const SESSIONS = 'protected/sessions.db';
const NTF = 'protected/ntf.db';

function trackFailedLoginAttempts($ip) {
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION['login_attempts'][$ip])) {
        $_SESSION['login_attempts'][$ip] = 1;
    } else {
        $_SESSION['login_attempts'][$ip]++;
    }
}

function isTimeout($ip, $threshold, $timeout) {
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_SESSION['login_attempts'][$ip]) && $_SESSION['login_attempts'][$ip] >= $threshold) {
        if (!isset($_SESSION['login_timeout'][$ip]) || $_SESSION['login_timeout'][$ip] < time()) {
            $_SESSION['login_timeout'][$ip] = time() + $timeout;
            $_SESSION['login_attempts'][$ip] = 0;
            return false;
        } else {
            return true; 
        }
    }
    return false;
}

function last_update($conn,$id){
    try {
        $quer = "UPDATE form_values SET last_update='".date('d-m-Y H:i:s')."' WHERE id_pedido=$id";
        //$conn->query($quer);
    } catch (\Throwable $th) {
        deadpage("Query Failed. #00412",$th->getMessage());
    }
}

function delf($conn,$id){
    try {
        $query= "DELETE FROM form_values WHERE id_pedido=".$id;
        if ($conn->query($query)) {

            $con = sqlconnect();
            $quer = $con->prepare("DELETE FROM notifications WHERE pedido_id=:id");
            $quer->bindValue(':id', $id);
            if ($quer->execute()){
                HeadTo('formslist');
            }else{
                echo "Removal Failure, error #009";
            }
        }else{
            echo "Removal Failure, error #009";
        } 
    } catch (\Throwable $th) {
        deadpage("Query Failed. #0041",$th->getMessage());
    }
    
}
function predelf() {
    try {
            echo '<div id="popup" style="display:block;">';
            echo '<div class="blocker"></div>';
            echo '<div class="contents">';
            echo '<img src="design/exclamation.png" height="40px">';
            echo "<p style='color:red;' data-tr='pre_delete'>All data on this form will be permanently deleted. Do you wish to confirm.</p>";
            echo '<form name="123" action="" method="post">';
            echo '<button id="close" type="submit" data-tr="delete" name="delete">Delete</button>';
            echo '</form>';
            echo '<button id="close" class="close" data-tr="exit" >Exit</button>';
            echo "</div></div>";
    } catch (\Throwable $th) {
        deadpage("Function Failed. #00411",$th->getMessage());
    }
}

function submit(){
    try {
        require "dbconnect.php";

    if(!($conn = sqlconnect())){
        echo "Connection failed error #001";
        return;
    }

    $fis = array(); 
    $vas = array();
    foreach($_POST as $field=>$val) {
        if($field!="submit"){
            if($field!="type_mail"){
                if($field!="type_tele"){
                    if($field!="office_lang"){
                        if($field!="services"){
                            $fis[] = $field; 
                            $vas[] = $val;
                        }else{
                            $fis[]= $field;
                            $name=$_POST['services'];
                            $aux = "";
                            foreach ($name as $valor){ 
                                $aux = $aux."-".$valor;
                            }
                            $vas[]=$aux;
                        }
                    }else{
                        $fis[]= $field;
                        $name=$_POST['office_lang'];
                        $aux = "";
                        foreach ($name as $valor){ 
                            $aux = $aux."-".$valor;
                        }
                        $vas[]=$aux;
                    }
                }else{
                    $fis[]= $field;
                    $name=$_POST['type_tele'];
                    $aux = "";
                    foreach ($name as $valor){ 
                        $aux = $aux."-".$valor;
                    }
                    $vas[]=$aux;
                }
            }else{
                $fis[]= $field;
                $name=$_POST['type_mail'];
                $aux = "";
                foreach ($name as $valor){ 
                    $aux = $aux."-".$valor;
                }
                $vas[]=$aux;
            }
        }
    }
    if (isset($_SESSION['loggedinfo'])) {
        $ldap = $_SESSION['loggedinfo'];
        if (isset($ldap[0]['mail'][0])) {
            $useremail = $ldap[0]['mail'][0];
        }
        $usernome = mb_convert_encoding($ldap[0]['givenname'][0],'UTF-8');
    }else {
        $useremail = "none";
        $usernome = "none";
    }
    $query = "INSERT INTO form_values (".implode(",",$fis).", status_pedido, contact_email, contact_nome) VALUES ('".implode("','",$vas)."', 'em-espera','".$useremail."','".$usernome."')";
    if ($conn->query($query)) {
        $res = $conn->query("SELECT Max(id_pedido) AS a FROM form_values");
        $last_id = $res->fetch();
        $query = "INSERT INTO computador (pedido_id) VALUES (".$last_id[0].")";
        if($conn->query($query)){
            require "formprint.php";
            require "sendmail.php";
            //last_update($conn,$id);
            $param = getparam();
            if ($param['activate_return_postform']==true||$param['activate_return_postform']==1) {
        	    sendmessage(getidrow($conn,$last_id[0]),'return');   
    	    } 
            if ($param['activate_receiver_feedback']==true||$param['activate_receiver_feedback']==1) {
                printe($conn,$last_id[0],true);
    	    } 
            HeadTo('postsend.php', ['id' => $last_id[0]]);
        }
    }
    } catch (\Throwable $th) {
        deadpage("Query Failed. #0042",$th->getMessage());
    }
    
}

function updatef($conn,$id){

try {
    if (!isset($_POST['updatef'])) {
        return;
    }

    $fis = array(); 
    $vas = array();
    
    foreach($_POST as $field=>$val) {
        if($field!="submit"){
            if($field!="type_mail"){
                if($field!="type_tele"){
                    if($field!="office_lang"){
                        if($field!="services"){
                            $fis[] = $field; 
                            $vas[] = $val;
                        }else{
                            $fis[]= $field;
                            $name=$_POST['services'];
                            $aux = "";
                            foreach ($name as $valor){ 
                                $aux = $aux."-".$valor;
                            }
                            $vas[]=$aux;
                        }
                    }else{
                        $fis[]= $field;
                        $name=$_POST['office_lang'];
                        $aux = "";
                        foreach ($name as $valor){ 
                            $aux = $aux."-".$valor;
                        }
                        $vas[]=$aux;
                    }
                }else{
                    $fis[]= $field;
                    $name=$_POST['type_tele'];
                    $aux = "";
                    foreach ($name as $valor){ 
                        $aux = $aux."-".$valor;
                    }
                    $vas[]=$aux;
                }
            }else{
                $fis[]= $field;
                $name=$_POST['type_mail'];
                $aux = "";
                foreach ($name as $valor){ 
                    $aux = $aux."-".$valor;
                }
                $vas[]=$aux;
            }
        }
    }
    /*foreach($fis as $a){
        echo $a."<br>";
    }
    echo "<hr>";
    foreach($vas as $a){
        echo $a."<br>";
    }*/

    $query = "UPDATE form_values SET ";

    if (count($fis)<=2){
        return;
    }
    for ($i=0; $i < count($fis)-2 ; $i++) { 
        $query = $query.$fis[$i]."='".$vas[$i]."', ";
    }
    $query = substr($query, 0, -2)." WHERE id_pedido=".$id;
    if ($conn->query($query)) {
        last_update($conn,$id);
        Popup::Call('fupdated', 'Form Updated Sucessefully',Popup::SUCCESS_ICON, Popup::BUTTON_CLOSE, 'data-tr="form_upd"');
        //setcookie('fupdated','1',time()+60);
        HeadTo('form-reader', ['id_pedido' => $id]);
    }
} catch (\Throwable $th) {
    deadpage("Query Failed. #0043",$th->getMessage());
}
    
}

function HeadTo($base, $params = [], $keep_params = true) {
    $existing_params = $_GET;

    if ($keep_params) {
        $params = array_merge($existing_params, $params);
    }

    $query_string = http_build_query($params);

    $url = $base;
    if (!empty($query_string)) {
        $url .= '?' . $query_string;
    }

    header('Location: ' . $url);
}



function updatepc($conn,$id){
try {
    $prm = getparam();
    $vas = array();
    $fis = array();
    
    foreach ($_POST as $field=>$val) {
        if($field!="submit"&&$field!="user_pass"&&$field!="admin_pass"){
            $fis[] = $field; 
            $vas[] = $val;
        }
    }
    $query = "UPDATE computador SET ";
    
    if (count($fis)<=2){
        return;
    }
    for ($i=1; $i < count($fis)-1 ; $i++) { 
        $query = $query.$fis[$i]."='".$vas[$i]."', ";
    }
    
    $query = substr($query, 0, -2)." WHERE pedido_id=".$id;

    if ($conn->query($query)) {
        last_update($conn,$id);
        Popup::Call('pcupdated','Computer form updated successfully.',Popup::SUCCESS_ICON, Popup::BUTTON_CLOSE, 'data-tr="comp_upd"');
        HeadTo('assignpc', ['id_pedido' => $id]);
    }
    
} catch (\Throwable $th) {
    deadpage("Query Failed. #0044",$th->getMessage());
}
    
}


function makec($conn,$id){
try {
    $cquery = "SELECT DISTINCT EXISTS (SELECT * FROM checklist WHERE id_maquina = (SELECT pc_id FROM computador WHERE pedido_id = ".$id."))AS op";
    if(!($cresult = $conn->query($cquery))){
        echo "Request Failed, error #";
        return;
    }else{
        $ans = 1;
        $rows = $cresult->fetch(PDO::FETCH_ASSOC);
        foreach ($rows as $value) {
            $ans = $value;
        }
        if(!($ans==0||$ans=="0")){
            HeadTo("checklist", ['id_pedido' => $id], true);
            return;
        }else{
            $query = "INSERT INTO checklist (id_maquina) SELECT pc_id FROM computador WHERE pedido_id = ".$id.";";
            if ($conn->query($query)) {
                $jquery ="UPDATE form_values SET status_pedido='em-processo' WHERE id_pedido=".$id;
                $conn->query($jquery);
                last_update($conn,$id);
                HeadTo("checklist", ['id_pedido' => $id], true);
            }    
        }
    }
} catch (\Throwable $th) {
    deadpage("Query Failed. #0045",$th->getMessage());
}
    
}

function updatec($conn,$id){
try {
    $fields=$_POST['list_fields'];
    $aux = "-";
    foreach ($fields as $valor){
        $aux = $aux.$valor."-";
    }
    $query = "UPDATE checklist SET list_fields='".$aux."' WHERE id_maquina=(SELECT pc_id FROM computador WHERE pedido_id=".$id.")";
    if($conn->query($query)){
        if (strlen($aux)==555) {
            $query ="UPDATE form_values SET status_pedido='concluido' WHERE id_pedido=".$id;
            $conn->query($query);
        }else {
            $query ="UPDATE form_values SET status_pedido='em-processo' WHERE id_pedido=".$id;
            $conn->query($query);
        }
        last_update($conn,$id);
        Popup::Call('cupdated','Checklist updated successfully.',Popup::SUCCESS_ICON,Popup::BUTTON_CLOSE,'data-tr="check_upd"');
        HeadTo('checklist',['id_pedido' => $id]);
    }else{
        echo "Submission Failed, error #005";
    }    
    
} catch (\Throwable $th) {
    deadpage("Query Failed. #0046",$th->getMessage());
}
    
}

function existcomp($conn,$id) : bool {
try {
    $query = "SELECT DISTINCT EXISTS (SELECT pc_id FROM computador WHERE pedido_id = ".$id.")AS op";
    $result = $conn->query($query);
    $row = $result->fetch();
    if ($row[0]==0){
        return false;
    }else{
        return true;
    }
} catch (\Throwable $th) {
    deadpage("Query Failed. #0047",$th->getMessage());
    return false;
}
}

function unsetSession() {
    @removeSessionToken($_SESSION['loggedinfo'][0]['samaccountname'][0]);
    if(isset($_SESSION['logged']))
        unset($_SESSION['logged']); 
    if(isset($_SESSION['loggedinfo']))
        unset($_SESSION['loggedinfo']);
    if(isset($_SESSION['op']))
        unset($_SESSION['op']);

    setcookie('auth_token','',time()-3600,'/','');
    setcookie('auth_regen','',time()-3600,'/','');

    /*setcookie('auth_token', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    setcookie('auth_regen', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);*/
}

function generateRandomToken() {
    return bin2hex(random_bytes(32));
}

function isRemember() {
    return isset($_COOKIE['auth_token']);
}

function doRememeber() {
    if (isRemember()) {
        setcookie('auth_token','',time()-3600,'/','');
        setcookie('auth_regen','',time()-3600,'/','');
        /*
        setcookie('auth_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        setcookie('auth_regen', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);*/
    }else {
        regenerateSessionToken();
        $expiry_time = time() + (7 * 24 * 3600); // 7 days
        $auth_token = encryptData($_SESSION['logged'],$_SESSION['loggedinfo'], $_SESSION['op']);

        setcookie('auth_token', $auth_token, $expiry_time,'/','');
        /*
        setcookie('auth_token', $auth_token, [
            'expires' => $expiry_time,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);*/
        storeSessionToken($_SESSION['logged'],$_SESSION['loggedinfo'][0]['samaccountname'][0]);
    }
}

function regenerateSessionToken() {
    $_SESSION['logged'] = generateRandomToken();
    
    $regeneration_expiry_time = time() + ( 12 * 3600);

    setcookie('auth_regen',true,$regeneration_expiry_time,'/','');

    setcookie('auth_regen', true, [
        'expires' => $regeneration_expiry_time,
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}

function login($back = 'index.php'): bool {
    try {
        if (!isset($_SESSION)) {
            session_start();
        }
        require "dbconnect.php";
        $row = getparam();

        // Validate user input
        if (!isset($_POST['user']) || !isset($_POST['pass'])) {
            return false;
        }
        $user = $_POST['user'];
        $password = $_POST['pass'];

        // Validate user input to prevent LDAP injection
        if (!preg_match('/^[a-zA-Z0-9]+$/', $user)) {
            return false;
        }
        // LDAP configuration
        $ldap_dn = $user . $row['ldap_domain'];
        $ldap_password = $password;
        $ldap_base = $row['ldap_base'];
        $ldap_host = $row['ldap_host'];
        $ldap_port = $row['ldap_port'];

        // Connect to LDAP server
        $ldapcon = ldap_connect($ldap_host, $ldap_port);
        if (!$ldapcon) {
            return false;
        }
        ldap_set_option($ldapcon, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapcon, LDAP_ESCAPE_DN, 1);

        // Bind to LDAP server
        $ldap_bind = @ldap_bind($ldapcon, $ldap_dn, $ldap_password);
        if (!$ldap_bind) {
            return sqlLogin($user,$password,$back);
        }
        $filter = "(&(userprincipalname=*)(sAMAccountName=$user))";
        $attributes = ['givenname', 'mail', 'memberof','sAMAccountName'];
        $result = @ldap_search($ldapcon, $ldap_base, $filter, $attributes);
        if (!$result) {
            return false;
        }
        $data = @ldap_get_entries($ldapcon, $result);
        if ($data['count'] !== 1) {
            return false;
        }


        // Set session tokens
        $session_token = generateRandomToken();
        $_SESSION['logged'] = $session_token;
        $_SESSION['loggedinfo'] = $data;
        //$_SESSION['op'] = $session_token;

        // Check for administrative privileges
        foreach ($data['0']['memberof'] as $value) {
            if (strpos($value, "Administrators") !== false) {
                $_SESSION['op'] = $session_token;
                break;
            }
        }

        if ($user == 'serviceuser' || $user == 'EstagioDSI') {
            $_SESSION['op'] = $session_token;
            $isOP = $session_token;
        }

        if (isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
            regenerateSessionToken();
            $expiry_time = time() + (7 * 24 * 3600); // 7 days
            //$auth_token = json_encode(['token' => $_SESSION['logged'], 'data' => json_encode($_SESSION['loggedinfo']), 'op' => $_SESSION['op']]);
            $auth_token = encryptData($_SESSION['logged'],$_SESSION['loggedinfo'], $_SESSION['op']);
            
            setcookie('auth_token', $auth_token, $expiry_time,'/','');
        }

        storeSessionToken($_SESSION['logged'],$_SESSION['loggedinfo'][0]['samaccountname'][0]);
        @ldap_unbind($ldapcon);
        header("Location: $back");
        return true;

    } catch (\Throwable $th) {
        error_log("Login failed: " . $th->getMessage());
        return false;
    }
}


function sqlLogin($user,$pass,$back){
    try{
        $conn = sqlconnect();
        $rowcount = 0;
        $query = "select count(*) from ldap where username = '$user' AND password = '$pass'";

        $rowcount = $conn->query($query)->fetchColumn(); 

        if ($rowcount != 0) {

            $query = "select * from ldap where username = '$user' AND password = '$pass'";

            if($result = $conn->query($query)){
                $row = $result->fetch(PDO::FETCH_ASSOC);
            }

            $data[0]['mail'][0] = $row["mail"];
            $data[0]['givenname'][0] = $row["givenname"];
            $data[0]['samaccountname'][0] = $row["samaccountname"];


            $session_token = generateRandomToken();
            $_SESSION['logged'] = $session_token;
            $_SESSION['loggedinfo'] = $data;
            //$_SESSION['op'] = $session_token;
    
            // Check for administrative privileges
           
            if ($row["memberof"]=="admin") {
                $_SESSION['op'] = $session_token;
            }
    
    
            if (isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
                regenerateSessionToken();
                $expiry_time = time() + (7 * 24 * 3600); // 7 days
                //$auth_token = json_encode(['token' => $_SESSION['logged'], 'data' => json_encode($_SESSION['loggedinfo']), 'op' => $_SESSION['op']]);
                $auth_token = encryptData($_SESSION['logged'],$_SESSION['loggedinfo'], $_SESSION['op']);
                
                setcookie('auth_token', $auth_token, $expiry_time,'/','');
            }
    
            storeSessionToken($_SESSION['logged'],$_SESSION['loggedinfo'][0]['samaccountname'][0]);
            header("Location: $back");
            return true;

        }

        return false;

    } catch (\Throwable $th) {
        error_log("Login failed: " . $th->getMessage());
        return false;
    }

}

function isValidSessionToken($token) {
    return (bool) preg_match('/^[a-f0-9]{64}$/i', $token); 
}

function logged() : bool {
try {
    //return true;
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_SESSION['logged'])&&isset($_SESSION['loggedinfo'][0]['samaccountname'][0])&&isValidToken($_SESSION['logged'], $_SESSION['loggedinfo'][0]['samaccountname'][0])) {
        return true;
    }else{
        if (isset($_COOKIE['auth_token'])) {

            $data = decryptData();
            $_SESSION['logged'] = $data['logged'];
            $_SESSION['loggedinfo'] = $data['loggedinfo'];
            $_SESSION['op'] = $data['op'];
    
            if (!isset($_COOKIE['auth_regen'])) {
                regenerateSessionToken();
            }

            $new_expiry_time = time() + (7 * 24 * 3600); 
            //$array = ['token'=> $_SESSION['logged'],'data'=> json_encode($_SESSION['loggedinfo']),'op'=> $_SESSION['op']];
            $auth_token = encryptData($_SESSION['logged'],$_SESSION['loggedinfo'], $_SESSION['op']);
            setcookie('auth_token', $auth_token, $new_expiry_time,'/','');

            storeSessionToken($_SESSION['logged'],$_SESSION['loggedinfo'][0]['samaccountname'][0]);
            return true;
        }
        return false;
    }
    
} catch (\Throwable $th) {
    //deadpage("Query Failed. #0049",$th->getMessage());
    return false;
}
}
function isValidToken($token, $username) {
    try {
        $db = new SQLite3(SESSIONS);
        if (!$db) {
            throw new Exception('Failed to connect to SQLite database');
        }
        $stmt = $db->prepare('SELECT COUNT(*) FROM sessions WHERE token = :token AND username = :username');
        $stmt->bindValue(':token', $token, SQLITE3_TEXT);
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $stmt->execute();
        $count = $result->fetchArray()[0];
        $db->close();
        return $count > 0;
    } 
    catch (\Throwable $th) {
        deadpage("validation",$th->getMessage());
    }
}

function storeSessionToken($token, $username) {
    try {
        $db = new SQLite3(SESSIONS);
        $stmt = $db->prepare('INSERT OR REPLACE INTO sessions (username, token) VALUES (:username, :token)');
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':token', $token, SQLITE3_TEXT);
        $stmt->execute();
        $db->close();
    } 
    catch (\Throwable $th) {
        deadpage("SQLite3",$th->getMessage());
    }
}

function removeSessionToken($username) {
    try {
        $db = new SQLite3('protected/sessions.db');
        $stmt = $db->prepare('DELETE FROM sessions WHERE username = :username');
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->execute();
        $db->close();
    } 
    catch (\Throwable $th) {
        deadpage("SQLite3",$th->getMessage());
    }
}

function drawList(){
try{
    $conn = sqlconnect();

    $search = "";
    $fstat = "";

    if (isset($_GET['filterstat'])) {
        $fstat = $_GET['filterstat'];
    }
    $query = "SELECT * FROM maquinasview ";

    if ($fstat != "") {
        $query = $query . "WHERE status_pedido='" . $fstat . "'";
    }

    $query = $query . " ORDER BY data_pedido DESC";
    if ($result = $conn->query($query)) {
                            
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $field0 = mb_convert_encoding($row['id_pedido'], "UTF-8");
            $field1 = mb_convert_encoding($row['comp_nome'], "UTF-8");
            $field1 = formatName($field1, 20);
            $field2 = mb_convert_encoding($row['act_empresa'], "UTF-8");
            $field3 = mb_convert_encoding($row['pc_name'], "UTF-8");
            $field4 = formatDate($row['data_pedido']);
            $field5 = mb_convert_encoding($row['status_pedido'], "UTF-8");
            $flagged = UnseenFlags($field0) ? "true" : "false";
            $add = UnseenFlags($field0) ? '<img src="design/flag_white.svg" class="unseenflag" height="30px">' : '';
            //for ($i=0; $i < 10; $i++) { 
                echo '<tr class="lrow rqtBox" data-value="'. $field0 .'" flagged="'.$flagged.'">
                <td class="rqtBoxContent" id="rcell1"> '. $add .' '. $field1 .'</td>
                <td class="rqtBoxContent" id="rcell2">' . $field2 . '</td>
                <td class="rqtBoxContent" id="rcell3">' .$field3 . '</td>
                <td class="rqtBoxContent" id="rcell4">' . $field4 . '</td>
                <td class="rqtBoxContent '. $field5 .'" id="rcell5">' . $field5 . '<i class="status ' . $field5 . '"></i></td>
            </tr>';
            //}
           
            
        }
    }else{
        echo "Request Failed, error $006";
        return;
    }
} catch (\Throwable $th) {
    deadpage("Page malfunction. #0051",$th->getMessage());
}

}

function drawStatusFilter() {
    try {
        $stat = isset($_GET["filterstat"]) ? $_GET["filterstat"] : "";
        $conn = sqlconnect();
        $alt_query = "SELECT DISTINCT status_pedido FROM form_values";
        if ($alt_result = $conn->query($alt_query))
        while ($row = $alt_result->fetch(PDO::FETCH_ASSOC)) {
            $val = $row['status_pedido'];
            $add = $stat == $val ? "selected" : "";
            echo '<option ' . $add . ' value="' . $val . '">' . $val . '</option>';
        }            
    } catch (\Throwable $th) {
        deadpage("Page malfunction. #0051",$th->getMessage());
    }
}

function getListCount() {
    try {
        $conn = sqlconnect();
        $rowcount = $conn->query('select count(*) from maquinasview')->fetchColumn(); 
        return $rowcount;
    } catch (\Throwable $th) {
        deadpage("Page malfunction. #0051",$th->getMessage());
        return 0;
    }
}

function setpopup($cookie,$line = "",$type = 'info'){
    try {
        if (isset($_COOKIE[$cookie])) {
            echo '<div id="popup" style="display:block;">';
            echo '<div class="blocker"></div>';
            echo '<div class="contents">';
            switch ($type) {
                case 'info':
                    echo '<img src="design/information.png" height="40px">';
                    echo "<p style='color:#2b86cb;'>$line</p>";
                    break;
                case 'right':
                    echo '<img src="design/success.png" height="40px">';
                    echo "<p style='color:#2ad352;'>$line</p>";
                    break;
                case 'wrong':
                    echo '<img src="design/exclamation.png" height="40px">';
                    echo "<p style='color:red;'>$line</p>";
                    break;
                default:
                    echo '<img src="design/information.png" height="40px">';
                    echo "<p style='color:#2b86cb;'>$line</p>";
                    break;
            }
            echo '<button id="close" class="close">Close</button>';
            echo "</div></div>";
            setcookie($cookie,"",time() - 3600);
        }
        
    } catch (\Throwable $th) {
        deadpage("Function Failed. #00411",$th->getMessage());
    }
}

function existcheck($conn,$id) : bool {
    try {
        $query ="SELECT DISTINCT EXISTS (SELECT * FROM checklist WHERE id_maquina = (SELECT pc_id FROM computador WHERE pedido_id = $id))";
        $result = $conn->query($query);
        $row = $result->fetch();
        if ($row[0]==0){
            return false;
        }else{
            return true;
        }
    } catch (\Throwable $th) {
        deadpage("Query Failed. #0047",$th->getMessage());
        return false;
    }
}


//v.3
function geturl() : string {
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url = "https://";   
    else  
        $url = "http://";   

    $url.= $_SERVER['HTTP_HOST'];   

    $url.= $_SERVER['REQUEST_URI'];    
 
    return $url;  
}


function prerespond(){
    try {
        echo '<div id="popup" style="display:block;">';
        echo '<div class="blocker" ></div>';
        echo '<div class="contents">';
        echo '<img src="design/information.png" height="40px">';
        echo "<p style='color:#2b86cb;' data-tr='prebuilt_message'>If you want to send a pre-built message to the user's email, please choose the type.</p>";
        echo '<form id="messageForm" name="123" action="" method="post">';
        echo '<table align=center cellspacing="15"><tr>';
        echo '<td><button style="color:darkgreen;font-size:small;" id="close" class="close" type="submit" name="respond" data-tr="complete" value="compl">Complete</button></td>';
        echo '<td><button style="color:#2b86cb;font-size:small;" id="close" class="close" type="submit" name="respond" data-tr="in_process" value="proc">In-Process</button></td>';
        echo '<td><button style="color:red;font-size:small;" id="close" class="close" type="submit" name="respond" data-tr="refused" value="refus">Refused</button></td>';
        echo '</tr></table></form>';
        echo '<button id="close" class="close" >Exit</button>';
        echo "</div></div>";
} catch (\Throwable $th) {
    deadpage("Function Failed. #00411",$th->getMessage());
}
}
function is_op() :bool {
    try {
        //return true;
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['op'])&&isValidSessionToken($_SESSION['op'])&&isValidToken($_SESSION['logged'], $_SESSION['loggedinfo'][0]['samaccountname'][0])) {
            return true;
        }else{
            
            return false;
        }
        
    } catch (\Throwable $th) {
        deadpage("Function Failed. #009",$th->getMessage());
        return false;
    }    
}

function generateCSRFToken() {
    return bin2hex(random_bytes(32));
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function getidrow($conn, $id) {
    $query = "SELECT * FROM maquinasview WHERE id_pedido = ".$id;
    if($result = $conn->query($query)){
        $row = $result->fetch(PDO::FETCH_ASSOC);
    }
    return $row;
}


function encryptData($session, $data, $op){

    $session_encryption_key = getenv('SESSION_ENCRYPTION_KEY') ?: 'CHANGE_ME_SESSION_ENCRYPTION_KEY';
    $is_op_encryption_key = getenv('IS_OP_ENCRYPTION_KEY') ?: 'CHANGE_ME_IS_OP_ENCRYPTION_KEY';
    $ldap_encryption_key = getenv('LDAP_ENCRYPTION_KEY') ?: 'CHANGE_ME_LDAP_ENCRYPTION_KEY';
    
    $session_encryption_iv = getenv('SESSION_ENCRYPTION_IV') ?: 'CHANGE_ME_IV_0001'; 
    $ldap_encryption_iv = getenv('LDAP_ENCRYPTION_IV') ?: 'CHANGE_ME_IV_0002'; 
    $is_op_encryption_iv = getenv('IS_OP_ENCRYPTION_IV') ?: 'CHANGE_ME_IV_0003'; 

    $session_mac_key = getenv('SESSION_MAC_KEY') ?: 'CHANGE_ME_SESSION_MAC_KEY'; 
    $ldap_mac_key = getenv('LDAP_MAC_KEY') ?: 'CHANGE_ME_LDAP_MAC_KEY';
    $is_op_mac_key = getenv('IS_OP_MAC_KEY') ?: 'CHANGE_ME_IS_OP_MAC_KEY';

    $encrypted_session_token = openssl_encrypt($session, 'aes-256-cbc', $session_encryption_key, 0, $session_encryption_iv);

    $session_hmac = hash_hmac('sha256', $encrypted_session_token, $session_mac_key);

    $encrypted_ldap_data = openssl_encrypt(json_encode($data), 'aes-256-cbc', $ldap_encryption_key, 0, $ldap_encryption_iv);

    $ldap_hmac = hash_hmac('sha256', $encrypted_ldap_data, $ldap_mac_key);

    $encrypted_is_op = openssl_encrypt($op, 'aes-256-cbc', $is_op_encryption_key, 0, $is_op_encryption_iv);

    $is_op_hmac = hash_hmac('sha256', $encrypted_is_op, $is_op_mac_key);

    $data_parts = [
        'encrypted_session_token' => $encrypted_session_token,
        'session_hmac' => $session_hmac,
        'encrypted_ldap_data' => $encrypted_ldap_data,
        'ldap_hmac' => $ldap_hmac,
        'encrypted_is_op' => $encrypted_is_op,
        'is_op_hmac' => $is_op_hmac
    ];

    $encoded_data = json_encode($data_parts);


    $cookie_value = base64_encode($encoded_data);
    return $cookie_value;
}

function decryptData() {
    $session_encryption_key = getenv('SESSION_ENCRYPTION_KEY') ?: 'CHANGE_ME_SESSION_ENCRYPTION_KEY'; 
    $is_op_encryption_key = getenv('IS_OP_ENCRYPTION_KEY') ?: 'CHANGE_ME_IS_OP_ENCRYPTION_KEY';  
    $ldap_encryption_key = getenv('LDAP_ENCRYPTION_KEY') ?: 'CHANGE_ME_LDAP_ENCRYPTION_KEY'; 

    $session_encryption_iv = getenv('SESSION_ENCRYPTION_IV') ?: 'CHANGE_ME_IV_0001'; 
    $ldap_encryption_iv = getenv('LDAP_ENCRYPTION_IV') ?: 'CHANGE_ME_IV_0002'; 
    $is_op_encryption_iv = getenv('IS_OP_ENCRYPTION_IV') ?: 'CHANGE_ME_IV_0003'; 

    $session_mac_key = getenv('SESSION_MAC_KEY') ?: 'CHANGE_ME_SESSION_MAC_KEY'; 
    $ldap_mac_key = getenv('LDAP_MAC_KEY') ?: 'CHANGE_ME_LDAP_MAC_KEY'; 
    $is_op_mac_key = getenv('IS_OP_MAC_KEY') ?: 'CHANGE_ME_IS_OP_MAC_KEY';
    
    $cookie_value = $_COOKIE['auth_token'] ?? '';

    $decoded_value = base64_decode($cookie_value);
    if ($decoded_value) {

    $data_parts = json_decode($decoded_value, true);
    if ($data_parts !== null) {

        $encrypted_session_token = $data_parts['encrypted_session_token'];
        $session_hmac = $data_parts['session_hmac'];
        $encrypted_ldap_data = $data_parts['encrypted_ldap_data'];
        $ldap_hmac = $data_parts['ldap_hmac'];
        $encrypted_is_op = $data_parts['encrypted_is_op'];
        $is_op_hmac = $data_parts['is_op_hmac'];

    $computed_session_hmac = hash_hmac('sha256', $encrypted_session_token, $session_mac_key);
    if ($computed_session_hmac === $session_hmac) {

        $session_token = openssl_decrypt($encrypted_session_token, 'aes-256-cbc', $session_encryption_key, 0, $session_encryption_iv);

        $computed_ldap_hmac = hash_hmac('sha256', $encrypted_ldap_data, $ldap_mac_key);
        if ($computed_ldap_hmac === $ldap_hmac) {
            
            $ldap_data = openssl_decrypt($encrypted_ldap_data, 'aes-256-cbc', $ldap_encryption_key, 0, $ldap_encryption_iv);

            $ldap_data_array = json_decode($ldap_data, true);

            $computed_is_op_hmac = hash_hmac('sha256', $encrypted_is_op, $is_op_mac_key);
            if ($computed_is_op_hmac === $is_op_hmac) {
                
                $is_op_token = openssl_decrypt($encrypted_is_op, 'aes-256-cbc', $is_op_encryption_key, 0, $is_op_encryption_iv);

                return ['logged' => $session_token, 'loggedinfo' => $ldap_data_array, 'op'=> $is_op_token];
            }
        }
    }
    }
}
}

const NTF_CATG_COMMENTS = "Comments";
const NTF_CATG_ALERT = "Warnings";
const NTF_CATG_STATUS = "Status";
const NTF_CATG_REQUEST = "Requests";

const NTF_ICON_MESSAGE = "design/chat.svg";
const NTF_ICON_RIGHT = "design/success.svg";
const NTF_ICON_WRONG = "design/exclamation.png";
const NTF_ICON_INFO = "design/information.png";

function makeNtf(string $id, string $to, string $title, string $body, string $category = NTF_CATG_COMMENTS, string $icon = NTF_ICON_MESSAGE, string $origin = "") {
    try {
        $data = (new DateTime())->format('d/m/Y');
        $conn = sqlconnect();
        $query = $conn->prepare("INSERT INTO notifications (origin, category, title, body, img, destination, pedido_id) 
                                VALUES (:origin, :category, :title, :body, :img, :destination, :id)");
        $query->bindValue(':origin', $origin);
        $query->bindValue(':category', $category);
        $query->bindValue(':title', $title);
        $query->bindValue(':body', $body);
        $query->bindValue(':img', $icon); 
        $query->bindValue(':destination', $to);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    } catch (\Throwable $th) {
        echo $th;
    }
}



function DrawNtf() {
    try {
        if (!isset($_SESSION['loggedinfo'][0]['givenname'][0])) {
            throw new Exception('Missing user data.');
        }
        $user = mb_convert_encoding($_SESSION['loggedinfo'][0]['givenname'][0], 'UTF-8');
        $conn = sqlconnect();
        $query = $conn->prepare("SELECT * FROM notifications WHERE destination=:user ORDER BY ntf_id DESC");
        $query->bindValue(':user', $user);
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $id = $row["ntf_id"];
            $title = $row['title'];
            $body = $row['body'];
            $img = $row['img'];
            $catg = $row['category'];
            $data = $row['date'];
            htmlPushNtf($id, $title, $body, $img, $catg, $data);
        }
        if (empty($rows)) {
            echo "<color=green><p data-tr='no_ntf'></p>";
        }
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}


function htmlPushNtf($id, $title, $body, $img, $catg, $date){
    $date = substr($date, 0, 10);
    echo '<div class="ntfBox" id="ntf'.$id.'">
            <div><img class="ntfImg" src="'.$img.'"></div>
            <div class="ntfBoxContent">
                <span class="ntfTitle">'.$title.'</span><span class="ntfDate">'.$date.'</span><br>
                <span class="ntfBody">'.$body.'</span>
            </div>
            <span class="ntfCatg">'.$catg.'</span>
            
    </div>';
}

function DrawRqt() {
    try {
        if (!isset($_SESSION['loggedinfo'][0]['givenname'][0])) {
            throw new Exception('Missing user data.');
        }
        $user = mb_convert_encoding($_SESSION['loggedinfo'][0]['givenname'][0], 'UTF-8');
        $conn = sqlconnect();
        $query = $conn->prepare("SELECT * FROM maquinasview WHERE contact_nome=:user ORDER BY data_pedido desc");
        $query->bindValue(':user', $user);
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $id = $row["id_pedido"];
            $name = $row['comp_nome']; 
            $name = FormatName($name, 20);
            $date = $row['data_pedido'];
            $date = substr($date, 0, 10);
            $status = $row['status_pedido'];
            htmlPushRqt($id, $name, $date, $status);
        }
        if (empty($rows)) {
            echo "<p data-tr='no_rqt'></p>";
        }
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}


function htmlPushRqt($id, $name ,$date, $status) {
    echo '<div class="rqtBox" id="rqt'.$id.'">
    <div class="rqtBoxContent flex-column-center"><form class="fake-form" method="post"><button type="submit" method="post" name="download" value="'.$id.'" class="download-btn btn-round-icon">PDF&nbsp;&nbsp;&#129139;</button></form></div>
    <div id="dateBox" class="rqtBoxContent flex-column-center">
        <span data-tr="ntf_number" class="rqtTitle">Número:</span>
        <span class="rqtBody">'.$id.'</span>
    </div>
    <div id="dateBox" class="rqtBoxContent flex-column-center">
        <span data-tr="date:" class="rqtTitle">Data:</span>
        <span class="rqtBody">'.$date.'</span>
    </div>
    <div id="nameBox" class="rqtBoxContent flex-column-center">
        <span data-tr="to:" class="rqtTitle">Para:</span>
        <span class="rqtBody">'.$name.'</span>
    </div>
    <div id="satusBox" class="rqtBoxContent flex-column-center">
        <span data-tr="status:" class="rqtTitle">Estado:</span>
        <span class="rqtBody '.$status.'" >'.$status.'</span>
    </div>
    <div id="toolsBox" class="rqtBoxContent flex-column-center ">
        <div class="flex-row-evenly">
            <form class="fake-form" method="post"><button type="button" method="post" name="edit" value="'.$id.'" rqt-id="'.$id.'" class="btn-rqt-edit btn-margin btn-round-icon btn-padding"><img class="btn-img" src="design/eye_white.svg"></button></form>
            <button type="submit" method="post" name="delete" value="'.$id.'" rqt-id="'.$id.'" class="btn-rqt-delete btn-margin btn-round-icon btn-padding"><img class="btn-img" src="design/pencil.svg"></button>
        </div>
    </div>
    </div>';
}


function statusNtf($row,$type) {
    try {
        $to  = $row['contact_nome'];
        $name = $row['comp_nome'];
        $id = $row['id_pedido'];
        $title = '<span data-tr="ntf_title">Atualização no estado do pedido</span>';
        $body = "<span data-tr='ntf_body'>Aviso, o atual estado do seu pedido numero foi alterado para: </span>&nbsp;";
        $add = "<br><br><span data-tr='ntf_number'>Numero:</span>&nbsp;<b>$id</b><br><span data-tr='ntf_to'>Para:</span>&nbsp;<b>$name</b>";
        $img = NTF_ICON_INFO;
        $catg = NTF_CATG_STATUS;
        $origin = 'Admin Messages';
        switch ($type) {
        case 'compl':
            $img = NTF_ICON_RIGHT;
            $body = $body."<span class='status complete' data-tr='complete'>Concluído</span>";
            break;
        case 'proc':
            $img = NTF_ICON_INFO;
            $body = $body."<span class='status in_process' data-tr='in_process'>Em-Processo</span>";
            break;
        case 'refus':
            $img = NTF_ICON_WRONG;
            $body = $body."<span class='status refused' data-tr='refused'>Recusado</span>";
            break;
        default:
            throw new Exception("Error: no message type in SendMessage()", 1);
            break;
        }
        $body = $body.$add;
        makeNtf($id, $to, $title, $body, $catg, $img, $origin);
    } catch (\Throwable $th) {
        //throw $th;
    }
}


function warningNtf($row,$message) {
    try {
        $to  = $row['contact_nome'];
        $name = $row['comp_nome'];
        $id = $row['id_pedido'];
        $title = '<span data-tr="ntf_del_title">Pedido foi cancelado</span>';
        $body = "<span data-tr='ntf_del_body'>Aviso, o pedido foi cancelado por um adiministrador: </span><br>";
        $add = "<br><br><span data-tr='ntf_number'>Numero:</span>&nbsp;<b>$id</b><br><span data-tr='ntf_to'>Para:</span>&nbsp;<b>$name</b>";
        $img = NTF_ICON_INFO;
        $catg = NTF_CATG_STATUS;
        $origin = 'Admin Messages';
        $body = $body.$message.$add;
        makeNtf($id, $to, $title, $body, $catg, $img, $origin);
    } catch (\Throwable $th) {
        //throw $th;
    }
}

function commentNtf($row, $value,){
    try {
        $to  = $row['contact_nome'];
        $title = 'Comentário no pedido para '.$row['comp_nome'];
        $id = $row['id_pedido'];
        $body = $value;
        $img = NTF_ICON_MESSAGE;
        $catg = NTF_CATG_COMMENTS;
        $origin = 'Admin Messages';
        makeNtf($id, $to, $title, $body, $catg, $img, $origin);
    } catch (\Throwable $th) {
        //throw $th;
    }
}

function updatepass($conn, $id , $pass){
    try {

        $prm = getparam();
        foreach ($pass as $field=>$val) {
            if ($field == "admin_pass" || $field == "user_pass") {
                $fis[] = $field; 
                $vas[] = encryptPassword($val,$prm['secretkey']);
            }
        }

        $query = "UPDATE computador SET ";
        
        if (count($fis)<1){
            return;
        }
        for ($i=0; $i < count($fis); $i++) { 
            $query = $query.$fis[$i]."='".$vas[$i]."', ";
        }
        
        $query = substr($query, 0, -2)." WHERE pedido_id=".$id;
    
        if ($conn->query($query)) {
            last_update($conn,$id);
            Popup::Call('pcupdated','Computer form updated successfully.',Popup::SUCCESS_ICON, Popup::BUTTON_CLOSE, 'data-tr="comp_upd"');
            HeadTo('assignpc', ['id_pedido' => $id]);
        }
        
    } catch (\Throwable $th) {
        deadpage("Query Failed. #0044",$th->getMessage());
    }
        
}

const FLAG_DELETE = "del";
const FLAG_MESSAGE = "mes";

function makeFlag($type, $id, $message) {
    try {
        $user = isset($_SESSION['loggedinfo'][0]['givenname'][0]) ? $_SESSION['loggedinfo'][0]['givenname'][0] : "";
        $conn = sqlconnect();
        $query = $conn->prepare("INSERT INTO flags (flag_type, destination, message, origin, seen) 
                                VALUES (:flagtype, :destination, :message, :origin, :seen)");
        $query->bindValue(':flagtype', $type);
        $query->bindValue(':destination', $id);
        $query->bindValue(':message', $message);
        $query->bindValue(':origin', $user);
        $query->bindValue(':seen', 0);
        $query->execute();
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}

function DrawFlag($id) {
    try {
        $conn = sqlconnect();
        $query = $conn->prepare("SELECT * FROM flags WHERE destination=:id");
        $query->bindValue(':id', $id);
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            echo '<fieldset disabled="disabled" id="flagset" class="fielset">';
            foreach ($rows as $row) {
                $idd = $row["flag_id"];
                $message = $row['message'];
                $origin = $row['origin'];
                $date = formatDate($row['date']); 
                htmlPushFlag($idd, $origin, $message, $date);
            }
            echo '</fieldset>';
            SeenFlag($id);
        }
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}

function SeenFlag($id) {
    try {
        $conn = sqlconnect();
        $query = $conn->prepare("UPDATE flags SET seen=1 WHERE destination=:id");
        $query->bindValue(':id', $id);
        $query->execute();
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}

function UnseenFlags($id) {
    try {
        $conn = sqlconnect();
        $query = $conn->prepare("SELECT count(*) FROM flags WHERE destination=:id AND seen=0");
        $query->bindValue(':id', $id);
        $query->execute();
        $count = $query->fetchColumn();
        return $count > 0;
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}

function htmlPushFlag($id, $origin, $message ,$date){
    echo '<div class="flag" id="flag'.$id.'">
                <div class="flagfield">
                    <img src="design/flag_white.svg" class="delflag" alt="" height="30px">
                </div>
                <div class="flagfield">
                    <span><b data-tr="from">From:</b></span>
                    <span>
                        '.$origin.'
                    </span>
                </div>
                <div class="flagfield">
                    <span>
                        '.$message.'
                    </span>
                </div>
                <div class="flagfield">
                    <span>
                        '.$date.'
                    </span>
                </div>
            </div>';
}



function saveBlockPrefs($value) {
    try {
        $username = isset($_SESSION['loggedinfo'][0]['mail'][0]) ? $_SESSION['loggedinfo'][0]['mail'][0] : "guest";
        $conn = sqlconnect();
        $query = null;
        if (hasPrefs()) {
            $query = $conn->prepare("UPDATE users SET block = :block WHERE username = :username");
        } else {
            $query = $conn->prepare("INSERT INTO users (username, block) VALUES (:username, :block)");
        }
        $query->bindValue(':username', $username);
        $query->bindValue(':block', $value , PDO::PARAM_BOOL);
        $query->execute();
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}


function saveLangPrefs($value) {
    try {
        $username = isset($_SESSION['loggedinfo'][0]['mail'][0]) ? $_SESSION['loggedinfo'][0]['mail'][0] : "guest";
        $conn = sqlconnect();
        if (hasPrefs()) {
            $query = $conn->prepare("UPDATE users SET lang = :lang WHERE username = :username");
        } else {
            $query = $conn->prepare("INSERT INTO users (username, lang) VALUES (:username, :lang)");
        }
        $query->bindValue(':username', $username);
        $query->bindValue(':lang', $value);
        $query->execute();
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}

function hasPrefs() {
    try {
        $username = isset($_SESSION['loggedinfo'][0]['mail'][0]) ? $_SESSION['loggedinfo'][0]['mail'][0] : "guest";
        $conn = sqlconnect();
        $query = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE username = :username");
        $query->bindValue(':username', $username);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return ($row['count'] > 0);
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}

function getUserPrefs($username) {
    try {
        $conn = sqlconnect();
        $query = $conn->prepare("SELECT * FROM users WHERE username=:username");
        $query->bindValue(':username', $username);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row;
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}


function isEmailBlocked($user) {
    try {
        $user = $user == "" ? "guest" : $user;
        if ($row = getUserPrefs($user)) {
            if (!isset($row['block'])) {
                return false;
            }
            return $row['block'] == 1;
        }
        return false;
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}


function formatDate($date){
    $d = new DateTime($date);
    //               01/01/2001
    return $d->format('d/m/Y');
}

function formatName($name, $maxLength) {
    $parts = explode(' ', $name);
    $formattedName = '';
    $totalLength = 0;

    foreach ($parts as $index => $part) {
        if ($index === count($parts) - 1) { //last name
            if ($totalLength + strlen($part) > $maxLength) { // overflowing
                $formattedName .= strtoupper($part[0]) . '.';
            } else {                                         // overflowing
                $formattedName .= $part;
            }
        } else {
            if ($totalLength + strlen($part) > $maxLength) { // overflowing
                $formattedName .= strtoupper($part[0]) . '. '; 
                $totalLength += 2; // Char and .
            } else {                                         // overflowing
                $formattedName .= $part . ' ';
                $totalLength += strlen($part) + 1; //String and space
            }
        }
    }

    return trim($formattedName);
}


?>