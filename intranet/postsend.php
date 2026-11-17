<?php
    require "protected/formprint.php";
    require "protected/dbconnect.php";
    require "protected/intranetlib.php";
    $conn = sqlconnect();
    if (!isset($_GET['id'])) {
        header("location:404;");
    }
    if (isset($_POST['submit'])) {
        printe($conn,$_GET['id']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="scripts/elementInjection.js"></script>
    <link rel="stylesheet" href="stylesheet/errorstl.css?random=999999">
    <title data-tr="cyber_title">CyberSculpt - Intranet</title>
</head>
<body>
    <div id="fullscreen">
        <!--header>
            <table id="headerlog">
                <tr>
                    <td align="right">
                        <a href="/initial/">Início</a>
          span id="headertitle">CyberSculpt</span>
            <          </td>
                </tr>
            </table>
            <table id="headermenu" id="desktop">
                <tr>
                    <td>
                        <button class="menubtn" onclick="window.location.href = '/intranet/'">Home</button>
                    </td>
                    <td>
                        <button class="menubtn" onclick="window.location.href = '/intranet/form-pedido'">Solicitar Maquinas</button>
                    </td>
                </tr>
            </table>
        </header-->
        <?php include('protected/header.html');?>
        <div id="workspace">
            <div class="menupanel" centered> 
                <div class="logbox">
                    <img src="design/file.png" id="deadpng">
                    <h2 style="color: green;" data-tr="sent_success">Form sent successfully.</h2>
                    <p data-tr="copy_download">You can download a copy of the form here:</p>
                    <form  method="post"><button type="submit" method="post" data-tr="download" name="submit" value="1" id="mainbtn2">Download</button></form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>