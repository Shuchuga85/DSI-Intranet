<?php
use PhpOffice\PhpWord\Writer\Word2007\Style\Row;

require "protected/dbconnect.php";
require "protected/intranetlib.php";

if (logged()==false) {
    setcookie('backpage',geturl(),time() + 10 * 60);
    header("location: login.php");
    return;
}
if (is_op()==false) {
    deadpage('Forbbiden. #403',"You do not have permission to access this page.","permission");
    return;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <script src="scripts/elementInjection.js"></script>
    <script src="scripts/formlistPage.js"></script>
    <link rel="stylesheet" href="stylesheet/formsliststl.css?random=999999">
    <title data-tr="cyber_title">CyberSculpt - Intranet</title>
</head>
<body>
<?php include('protected/header.html')?>
        <div id="workspace">
            <div id="topnav">
                <div style="display: none;">
                    <form action="form-reader" method="get">
                        <input id="idholder" type="text" name="id_pedido">
                        <button id="openbt" type="submit"></button>
                    </form>
                </div>
                <h2 data-tr="form_list" style="position: absolute; top:1px; left:2%;">Lista de pedidos</h2><br>
                <form name="ffilter" method="get" id="desktop">
                    <table id="filtertb">
                        <tr>
                            <td style="color: black; font-weight:500;">
                                <input title="Quantidade :" disabled size="3" id="numrows" type="text" value="<?php echo getListCount();?>">
                            </td>
                            <td>
                                <input type="hidden" name="lang" value="<?php  echo isset($_GET['lang']) ? $_GET['lang'] : '';?>">
                                <div class="listfilter"  id="refreshbt" >
                                    <img src="design/refresh.png" height="22px">
                                </div>
                            </td>
                            <td>
                                <div class="listfilter" id="flagfilter" title="Pedidos com Flags">
                                    <img src="design/flag_white.svg" height="22px" alt="">
                                </div>
                            </td>
                            <td>
                                <select class="filterstat" id="filterstat1" name="filterstat">
                                    <option value="" data-tr="status" >-- Estado --</option>
                                    <?php
                                        drawStatusFilter();
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input class="searchInput" type="search" id="search1" size="15" form="fakeform" placeholder="Search..." data-tr-ph="search">
                            </td>
                        </tr>
                    </table>
                </form>
                <form name="ffilter" id="mobile" method="get">
                    <table id="filtertb">
                        <tr>
                            <td style="color: black; font-weight:500;">
                                <input disabled size="3" id="numrows" type="text" value="<?php echo getListCount();?>">
                            </td>
                            <td>
                                <input type="hidden" name="lang" value="<?php  echo isset($_GET['lang']) ? $_GET['lang'] : '';?>">
                                <button class="refreshbt" id="refreshbt" style="border:none; background-color:rgba(0,0,0,0);"><img src="design/refresh.png" height="22px"></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select class="filterstat" id="filterstat2" name="filterstat" >
                                    <option value="" data-tr="status" >-- Estado --</option>
                                    <?php
                                        drawStatusFilter(); 
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input class="searchInput" type="search" id="search" size="15" form="fakeform" placeholder="..." data-tr-ph="search">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div id="mainpanel">
                <table id="rowtb">
                    <thead>
                        <tr>
                            <th id="rhead0" class="rhead" data-value="0" data-tr="name">Nome</th>
                            <th id="rhead1" class="rhead" data-value="1" data-tr="company">Empresa</th>
                            <th id="rhead2" class="rhead" data-value="2" data-tr="machine">Maquina</th>
                            <th id="rhead3" class="rhead" data-value="3" data-tr="date">Data</th>
                            <th id="rhead4" class="rhead" data-value="4" data-tr="status">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        drawList();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
</body>
</html>