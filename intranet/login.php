<?php 
require "protected/intranetlib.php";

if (logged()==true) {
    header("location: index.php");
    return;
}

if (!isset($_SESSION)) {
    session_start();
}
$blocked = false;
$valid = true;

if (isset($_POST['submit'])){

    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        die("CSRF token validation failed!");
    }else {
        unsetSession($_SESSION['csrf_token']);
    }

    $ip = $_SERVER['REMOTE_ADDR']; // Get the user's IP address
    $threshold = 3; // Adjust as needed
    $timeout = 60; // Timeout for 1 minute after threshold is reached

    if (isTimeout($ip, $threshold, $timeout)) {
        $blocked = true;
        $valid = false;
    }else{
        if (isset($_COOKIE['backpage'])) {
            $valid = login($_COOKIE['backpage']);
            setcookie('backpage','',time() - 3600);
        }else {
            $valid = login();
        }
    if (!$valid) {
        trackFailedLoginAttempts($ip);
    }
    }
}

$_SESSION['csrf_token'] = generateCSRFToken();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="scripts/elementInjection.js"></script>
    <link rel="stylesheet" href="stylesheet/styleslogin.css?random=999999">
    <script src="../jquery-3.7.1.min.js"></script>
    <script src="scripts/ajax.js"></script>
    <script src="scripts/loginPage.js"></script>
    <script src="scripts/eventlistener.js"></script>
    <title data-tr="cyber_title">CyberSculpt - Intranet</title>
</head>
<body>
    <div class="container">
        <header>
            <a href="/" id="homebtn"><img src="design/home-icon-white.svg" ></a>
            <div id="languageSelectorContainer">
                <div id="languageSelector">
                    <select id="languageSelect" lang>
                        <option data-tr="Portuguese" value="pt">Portuguese</option>
                        <option data-tr="English" value="en">English</option>
                        <option data-tr="French" value="fr">French</option>
                        <option data-tr="German" value="de">German</option>
                    </select>
                </div>
            </div>
            <img id="logoimg" src="design/logotipo.png" style="filter: invert(100%);" height="60px">
        </header>
        <div class="menupanel">
            <div class="logbox">
                <form method="post" id=formlogin autocomplete="off">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="login" name="form1">
                        <h1 data-tr="LOGIN">LOGIN</h1>
                        <input class="inputfield <?php if ($valid == false) {echo 'failed" value="'.@$_POST['user'].'"';}?> id="user" type="text" name="user"  minlength="5" data-tr-ph="Username" required placeholder="Utilizador">
                        <label data-tr="win_username" class="subinfo" for="user">Nome de utilizador do Windows</label><br>
                        <input class="inputfield <?php if ($valid == false) {echo 'failed" value="'.@$_POST['pass'].'"';}?> id="pass" type="password" minlength="5" name="pass" data-tr-ph="Password" required placeholder="Palavra-Passe">
                        <br><span align="left"><label data-tr="remember_me" for="rememberme">Lembrar-me:</label>
                        <input type="checkbox" name="remember_me" id="rememberme"></span>
                        <input type="submit" data-tr="enter" value="Entrar" name="submit" id="btn">
                    </div>
                </form>
            </div>
        </div>
        <div id="popup" <?php if($valid == false){echo 'style="display: block;"';}else{/*echo 'style="display: none;"';*/}?>>
            <div class="blocker" ></div>
            <div class="contents">
                <img src="design/exclamation.png" height="20px">
                <p data-tr="<?php if($blocked) echo "too_many_attempts"; else echo "invalid_credentials"; ?>">Invalid Credentials. Please try again.</p>
                <button data-tr="close" id="close" >Close</button>
            </div>
        </div>
        <div id="footerTitle"><img src="design/computer-network.svg" height="20px"><span>Intranet</span></div>
    </div>
    <script src="translation/localization.js"></script>
</body>
</html>