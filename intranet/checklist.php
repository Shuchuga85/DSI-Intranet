<?php
    use PhpOffice\PhpWord\Writer\Word2007\Part\Document;
try { 
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
    
    $n = 0;
    if(!(isset($_GET['id_pedido']))){
        deadpage("Page not found. #002","Get id failed",'404');
        return;
    }
    $id = $_GET['id_pedido'];
    
    $conn = sqlconnect();

    
    if (validID($conn,$id)==false) {
        return;
    }
    
    if (isset($_POST['submitbt'])) {
        updatec($conn,$id);
    }

    if(existcheck($conn,$id)==false){
        Popup::Call('newcheck','Lista de tarefas não criada. Atribua um computador.',popup::INFO_ICON, Popup::BUTTON_CLOSE, 'data-tr="newcheck"');
        header("location: assignpc?id_pedido=".$id);
    }
   
    //busca os fields preenchidos
    $query = "SELECT list_fields FROM checklist c, computador p WHERE p.pedido_id=".$id." AND c.id_maquina=p.pc_id";
    if(!($result = $conn->query($query))){
        echo "Request Failed, error #";
        return;
    }
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $fields = $row['list_fields'];

    //atribui valores a cada checkbox
    function assingvalue(){
        global $f, $n;
        echo "field".$n.".".$f;
        $f++;
    }

    //checked se o campo foi marcado anteriormente
    function check(){
        global $fields, $n, $f; 
        $quant = substr_count($fields,"field".$n.".".$f);
        if($quant >0)
            echo "checked";
    }
    
    function inum(){
        global $n;
        $n++;
        echo $n;    
    }
    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="scripts/elementInjection.js"></script>
    <script src="scripts/checklistPage.js"></script>
    <link rel="stylesheet" href="stylesheet/checkliststl.css?random=999999">
    <title data-tr="cyber_title">CyberSculpt - Intranet</title>
</head>
<body>
    <?php 
        Popup::Listen('cupdated');
        ob_end_flush();
    ?>
    <div id="fullscreen">
        <?php include('protected/header.html')?>
        <div id="workspace">
            <div id="topnav">
                    <table id="filtertb">
                        <tr>
                            <td>
                                <button id="checkall"  class="btn-round-icon">&#10004;&nbsp; <span data-tr="check_all">Check All</span></button>
                            </td>
                            <td>
                                <button id="updbt"  class="btn-round-icon btn" type="submit" name="submitbt" form="cform" value="Update"><span data-tr="save">Update</span> <img src="design/diskette_white.svg" alt=""></button>
                            </td>
                            <td>
                                <a href="form-reader.php?id_pedido=<?php echo $id;?>"><button class="btn-round-icon"><span data-tr="return">Voltar</span></button></a>
                            </td>
                        </tr>
                    </table>
            </div>
            <div id="mainpanel">
                <table id="rowtb">
                    <colgroup>
                        <col span="1">
                        <col span="1">
                        <col span="1">
                        <col span="1">
                    </colgroup>
                    <thead>
                        <tr>
                            <th id="rcell1"></th>
                            <th id="rcell2">Descrição</th>
                            <th id="rcell3">&#10004;</th>
                            <th id="rcell5">Observações</th>
                            <th id="rcell6"></th>
                        </tr>
                    </thead>
                    <form id="cform" method="post">
                    <input type="text" name="id_pedido" value="<?php echo $id;?>" style="display: none;">
                    <tbody id="table">
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Formatar disco C</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td rowspan="3" class="idtd"><?php $f=0; inum()?></td>
                            <td>Instalar Windows</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Criar Utilizador que terá direitos de administração</td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>-escolher p/não detectar impressora</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td >Win7 -> explorer - ferramentas - opções de pastas - ver <br> Win10 -> centro de rede e partilha - definições de partilha avançada - deteção de rede desativar deteção de rede</td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>-alterar nome do computador</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Instalar atualização de drives</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Alterar password de "administrador"</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Instalar placa de rede <br>-Desativar gestor de energia</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Nas propiedades da placa</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Instalar tcp/ip</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Promover ao dominio (demo.example.com)</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Em windows 7 precisa de executar primeiro o ficheiro <br>.../instal/WIN_UPDATES/win7/samba_win7.reg</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Instalar impressoras</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Nas opções de IE, desmarcar "Detectar definições aut..."(proxy) <br>Na aba "segurança", autorizar todos</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td rowspan="6" class="idtd"><?php $f=0; inum()?></td>
                            <td >Instalar Office</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>- Service Pack</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>- Proof</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Dicionario default para portugues</td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>- Propiedades de print no word</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Template word - G:\appwin95\template</td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>- Localização de ficheiros no word</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>- Template do excel</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>G:\appwin95\book.xlt</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Remover licenaç do Office do pc anterior</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>IzArc</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td rowspan="3" class="idtd"><?php $f=0; inum()?></td>
                            <td>Definições do Thunderbird/Outlook</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>-instalar extras(calendario,dicionario)<br>-configuração de conta-composição e endereçamento<br>-ferramentas-opções-avançadas: guardar 5000...</td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>Conta de email</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>-ferramentas-opções-composição-geral: reencaminhar em linha<br>-ferramentas-opções-composição-opções de envio: Enviar tanto em formato de texto como HTML</td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>WinMailOpener</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Pasta "scanner"</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Configurar sites seguros no IE<br>-Executar ..\install\SAPGUI\Doxis\Chromesso.reg</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Pesquisar "opções da internet" <br>file://server.example.com / file://server.example.com / file://server.example.com</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Dropbox</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Licensiar windows e office</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Leitor de ficheiros PDF</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>impressora PDF (PdfCreator)</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Chrome</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td rowspan="6" class="idtd"><?php $f=0; inum()?></td>
                            <td >Autocad + Expres Tools</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Versão de rede em: \\server.example.com\da3\AutodeskInstall</td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>- acrescentar ao "support files"</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>G:\appwin95\simbolos.cad</td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>- alterar localização dos templates</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>G:\appwin95\acad200.dwt</td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>- copiar "acad.pgp"</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>C:\Documents and Settings\USER\Application\Data\Autodesk\AutoCAD200_\R16.1\enu\Support</td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>- copiar "acad200.pgp"</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>C:\Programas\AutoCAD200_\Support</td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>- alterar a localização de "plotters e "plot styles"</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>No "config", alterar em "Printer support file path"</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Robot</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>\\server.example.com\cybersculpt\install\AutodeksInstall</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Inventor + Updates</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>\\server.example.com\cybersculpt\install\AutodeksInstall</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Autodesk VBA Enabler</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>\\server.example.com\cybersculpt\install\AutodeksInstall</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>SAP + ODBC</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Copiar "dll" da pasta ..\install\DSI</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>CPIS</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Copiar libraria para "SysWOW64"</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>odbc CPIS</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>UltraVNC</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Sem o VNC Client</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>FireWall</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td rowspan="2" class="idtd"><?php $f=0; inum()?></td>
                            <td >Acrescentar aos ficheiros de arranque:<br>- Atalho para programa de correio</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr>
                            <td>- Atalho para CPIS</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Peddimat (criar pasta C:\PEDDIMAT_2014)</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>atalho para "Peddimat.xls" no desktop "publico"</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Atalhos para o Dekstop</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>Telefones; SGI</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Instalar Anti-Virus + WebAdvisor</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Teams</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Autocad Viewer</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>SetupDWGTrueView</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Java</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Vpn</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td>testar server.example.com</td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Teamviewer</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Tekla 2019i</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Sage</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>InTouch (instalar nativeclient)</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>PDA</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                        <tr class="tlrow">
                            <td class="idtd"><?php $f=0; inum()?></td>
                            <td>Registrar no MANTIFOR</td>
                            <td class="checktd"><input class="checkbox" type="checkbox" <?php check();?> name="list_fields[]" value="<?php assingvalue()?>"></td>
                            <td></td>
                        <td class="colend"></td>
                        </tr>
                    </tbody>
                    </form>
                </table>
            </div>
        </div>
    </div>
</body>
</html><?php } catch (\Throwable $th) {
    deadpage("Page malfunction. #0054",$th->getMessage());
}?>