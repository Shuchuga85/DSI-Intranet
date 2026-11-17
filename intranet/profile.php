<?php
require "protected/intranetlib.php";
require "protected/dbconnect.php";
require "protected/formprint.php";

if (logged() == false) {
    setcookie('backpage', geturl(), time() + 10 * 60);
    header("location: login.php");
    return;
}
if (isset($_POST['download'])) {
    $conn = sqlconnect();
    printe($conn, $_POST['download']);
    
}
if (isset($_GET['remember_me'])) {
    doRememeber();
    exit;
}
if (isset($_GET['saveprefs'])) {
    saveBlockPrefs($_GET['saveprefs']);
    exit;
}
if(isset($_GET['saveLangPrefs'])){
    saveLangPrefs($_GET['saveLangPrefs']);
    exit;
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    IframePopup::StaticDraw("editframe.php?id_pedido=$id",
    [
        IframePopup::MakeButton('Fechar',false,'','','','data-tr="close"')
    ]
    );
    exit;
}

if (isset($_GET['subdelete'])) {
    InputBox::StaticDraw('Escreva sobre como alterar este pedido para administradores','senddelete','...',
    [
        InputBox::MakeButton('Enviar',true,'deleteid',$_GET['subdelete'],'post','data-tr="send"'),
        InputBox::MakeButton('Sair',false,"","","","data-tr='exit'")
    ],
    "post",
    'data-tr-html="reason_del"'
    );
    exit;
}
if (isset($_GET['delete'])) {
    Popup::StaticDraw('Alterar um pedido, tem que ser feito pelos administradores. <br>Deseja alterar este pedido?', Popup::INFO_ICON, 
    [
        Popup::MakeButton('Sim',false,'subdelete',$_GET['delete'],'post','data-tr="yes"'),
        Popup::MakeButton('Não',false)
    ]
    , "data-tr-html='rqt_cancel_msg'");
    exit;
}

if (isset($_POST['senddelete'])&&isset($_POST['deleteid'])) {
    makeFlag(FLAG_DELETE,$_POST['deleteid'],$_POST['senddelete']);
}

$block = '';
$remember = '';
$user = "guest";
if (isset($_SESSION['loggedinfo'][0]['mail'][0])){
    $user  = $_SESSION['loggedinfo'][0]['mail'][0];
}
$block = isEmailBlocked($user) ? 'checked' : '';
$remember = isRemember() ? 'checked' : '';

$row = getUserPrefs($user);

function selectLang($value){
    global $row;
    if (!isset($row['lang'])) {
        return;
    }
    if ($row['lang']==$value)
        echo "selected";
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="scripts/profilePage.js"></script>
    <link rel="stylesheet" href="stylesheet/profile.css?random=999999">
    <title data-tr="cyber_title">CyberSculpt - Intranet</title>
</head>
<body>
    <div id="fullscreen">
        <?php include('protected/header.html');?>
        <div id="workspace">
            <d id="workpanel">
                <div id="sidebar">
                    <span data-tr="profile" id="sideTitle">Perfil</span>
                    <ul>
                        <li class="screenbtn" screen-id="preferences">&nbsp;<img class="screenbtnimg" src="design/settings_white.svg"><span data-tr="preferences">Preferências</span></li>
                        <li class="screenbtn" screen-id="notifications">&nbsp;<img class="screenbtnimg" src="design/ringing_white.svg"><span data-tr="notifications">Notificações</span></li>
                        <li class="screenbtn" screen-id="requests">&nbsp;<img class="screenbtnimg" src="design/quote-request_white.svg"><span data-tr="requestsbt">Pedidos</span></li>
                        <a href="settings.php"  <?php if (is_op()==false) {echo "style='display:none;'";} ?>>
                            <li class="screenbtn">&nbsp;<img class="screenbtnimg" src="design/settings_white.svg"><span data-tr="administrator">Administrador</span></li>
                        </a>
                        <a href="logout.php">
                            <li class="screenbtn">&nbsp;<img class="screenbtnimg" src="design/logout_white.svg"><span data-tr="logout">Terminar sessão</span></li>
                        </a>
                    </ul>
                </div>
                <div id="profilescreen">
                    <div class="screenpage" id="preferences">
                        <form action="">
                            <fieldset id="prefsfield">
                                <legend data-tr="preferences">Preferências</legend>
                                <table>
                                    <tr>
                                        <td>
                                            <label data-tr="stay_conn" for="remember_me">Manter-se conectado :</label>
                                        </td>
                                        <td>
                                            &nbsp;&#11166;<input type="checkbox"  class="checkprefs" <?php echo $remember;?> name="remember_me" id="remember_me">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label data-tr="block_email" for="notify">Bloquear notificações por correio eletrónico: </label>
                                        </td>
                                        <td>
                                            &nbsp;&#11166;<input type="checkbox" class="checkprefs" <?php echo $block;?> name="saveprefs"  id="notify">
                                        </td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td>
                                            <label data-tr="dark_theme" for="theme">Dark Theme: </label>
                                        </td>
                                        <td>
                                            <input type="hidden" name="theme" value="off">
                                            &nbsp;&#11166;<input type="checkbox" class="checkprefs" name="theme" id="theme" value="on">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label data-tr="preferred_lang" for="langs">Preferred Language: </label>
                                        </td>
                                        <td>
                                            &nbsp;&#11166;
                                            <select id="langselects" name="saveLangPrefs">
                                                <option selected value="">...</option> 
                                                <option <?php  selectLang('pt');?> data-tr="Portuguese" value="pt"></option>
                                                <option <?php  selectLang('en');?> data-tr="English" value="en"></option>
                                                <option <?php  selectLang('fr');?> data-tr="French" value="fr"></option>
                                                <option <?php  selectLang('de');?> data-tr="German" value="de"></option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </form>
                    </div>
                    <div class="screenpage" id="notifications">
                        <div id="ntfpanel" class="inline-panel">
                            <div id="ntfsetup" class="setup-flex-column">
                                <h3 data-tr="category">Category</h3>
                                <ul id="categoryfilter" class="round-ul-filter">
                                    <li data-tr="comments" class="catgbtn" catg-value="Comments" catg-on="0">
                                        Comentários
                                    </li>
                                    <li data-tr="warnings" class="catgbtn" catg-value="Warnings" catg-on="0">
                                        Avisos
                                    </li>
                                    <li data-tr="requests" class="catgbtn" catg-value="Requests" catg-on="0">
                                        Pedidos
                                    </li>
                                    <li data-tr="status" class="catgbtn" catg-value="Status" catg-on="0">
                                        Estado
                                    </li>
                                </ul>
                                <h3 data-tr="recent_ntf">Notificações recentes</h3>
                                <div id="ntfTable" class="table-overflow-y">
                                    <?php DrawNtf();?>
                                </div>
                            </div>
                            <div id="msgsetup" class="setup-flex-column">
                                <div class="msgBox">
                                <h3 data-tr="message">Mensagem</h3><br>
                                    <img src="" id="msgImg">
                                    <div class="msgBoxContent">
                                        <span style="display: none;" data-tr="categorylb" class="msgH1">Categoria:</span>
                                        <span style="display: none;"  class="label" id="msgCatg">&nbsp;</span>
                                        <span data-tr="datelb" class="msgH1">Data:</span>
                                        <span class="label" id="msgDate">&nbsp;</span><br>
                                        <span data-tr="titlelb" class="msgH1">Título:</span>
                                        <span class="label" id="msgTitle">&nbsp;</span><br>
                                        <span data-tr="bodylb" class="msgH1">Corpo:</span>
                                        <span class="label" id="msgBody">&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="screenpage" id="requests">
                        <div id="rqtpanel" class="inline-panel">
                            <div id="rqtsetup" class="setup-flex-column">
                                <h3 data-tr="filter" style="display: none;" >Filtro</h3>
                                <ul id="rqtfilter" class="round-ul-filter" style="display: none;">
                                    <li class="rqtbtn">
                                        Estado
                                    </li>
                                    <li>
                                        <input type="text" class="rqtbtn" name="test" id="test">
                                    </li>
                                </ul>
                                <h3 data-tr="recent_rqt">Pedidos recentes</h3>
                                <div id="rqtTable" class="table-overflow-y">
                                    <!--div class="rqtBox" id="rqt996">
                                        <div class="rqtBoxContent flex-column-center">
                                            <form class="fake-form" method="post">
                                                <button type="submit" method="post" name="download" value="1" class="download-btn btn-round-icon">PDF&nbsp;&nbsp;&#129139;</button>
                                            </form>
                                        </div>
                                        <div id="dateBox" class="rqtBoxContent flex-column-center">
                                            <span class="rqtBody">08/05/2024</span>
                                        </div>
                                        <div id="nameBox" class="rqtBoxContent flex-column-center">
                                            <span class="rqtBody">Complete Name</span>
                                        </div>
                                        <div id="satusBox" class="rqtBoxContent flex-column-center">
                                            <span class="rqtBody status-green" >Concluído</span>
                                        </div>
                                        <div id="toolsBox" class="rqtBoxContent flex-column-center ">
                                            <div class="flex-row-evenly">
                                                <form class="fake-form" method="post"><button type="submit" method="post" name="edit" rqt-id="1" class="btn-margin btn-round-icon btn-padding">Edit <img class="btn-img" src="design/pencil.svg"></button></form>
                                                <form class="fake-form" method="post"><button type="submit" method="post" name="delete" rqt-id="1" class="btn-rqt-delete btn-margin btn-round-icon btn-padding"><img class="btn-img" src="design/x-mark.svg"></button></form>
                                                <button type="submit" method="post" name="delete" rqt-id="1" class="btn-rqt-delete btn-margin btn-round-icon btn-padding"><img class="btn-img" src="design/x-mark.svg"></button>
                                            </div>
                                        </div>
                                    </div-->
                                    <?php DrawRqt();?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="screenpage" id="settings">

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>