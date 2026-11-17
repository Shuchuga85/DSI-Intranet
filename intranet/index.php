<?php 
    require "protected/intranetlib.php";
    if (logged()==false) {
        setcookie('backpage',geturl(),time() + 10 * 60);
        header("location: login.php");
        return;
    }
    /*echo "<pre>";
    print_r($_SESSION["loggedinfo"]);
    echo "</pre>";*/
    /*echo $_SESSION["loggedinfo"][0]["givenname"][0]."<br>";
    echo $_SESSION["loggedinfo"][0]["samaccountname"][0]."<br>";
    echo $_SESSION["loggedinfo"][0]["mail"][0]."<br>";*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="scripts/elementInjection.js"></script>
    <link rel="stylesheet" href="stylesheet/indexstyle.css?random=99839">
    <title data-tr="cyber_title">CyberSculpt - Intranet</title>
</head>
<body>
    <div id="fullscreen">
        <?php  include('protected/header.html')?>
        <div id="workspace">
            <h1 data-tr="welcome">Bem-vindo</h1>
            <p data-tr="page_intend">Esta é uma pagina destinada ao preenchimento de pedidos de novos utilizadores e computadores.</p>
            <a dynamic href="/intranet/form-pedido.php"><Button data-tr="make_request" id="mainbtn" id="btn4_link">Realizar pedido</Button></a><br><br>
        </div>
    </div>
</body>
</html>