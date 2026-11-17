<?php
try {
    //code...

    require "protected/dbconnect.php";
    require "protected/intranetlib.php";

    if (logged() == false) {
        setcookie('backpage', geturl(), time() + 10 * 60);
        header("location: login.php");
        return;
    }
    if (is_op() == false) {
        deadpage('Forbbiden. #403', "You do not have permission to access this page.", "permission");
        return;
    }
    if (isset($_GET['c_admin_pass'])) {
        InputLine::StaticDraw("Introduzir nova palavra-passe:", "admin_pass", "Password",
        [
            InputLine::MakeButton('Atualizar'),
            InputLine::MakeButton('Cancelar',false)
        ], 
        'post', 'password');
        exit;
    } 
    if (isset($_GET['c_user_pass'])) {
        InputLine::StaticDraw("Introduzir nova palavra-passe:", "user_pass", "Password",
        [
            InputLine::MakeButton('Atualizar'),
            InputLine::MakeButton('Cancelar',false)
        ], 
        'post', 'password');
        exit;
    }

    if (!(isset($_GET['id_pedido']))) {
        deadpage("Page not found. #002", "Get id failed", '404');
        return;
    }
    $id = $_GET['id_pedido'];

    $conn = sqlconnect();

    if (validID($conn, $id) == false) {
        return;
    }
    if (isset($_POST['user_pass'])){
        updatepass($conn, $id, $_POST);
    }
    if (isset($_POST['admin_pass'])){
        updatepass($conn, $id, $_POST);
    }

    if (isset($_POST['updatepc'])) {
        updatepc($conn, $id);
    }
    if (isset($_POST['makec'])) {
        makec($conn, $id);
    }

    if (existcomp($conn, $id) == false) {
        $lquery = "INSERT INTO computador (pedido_id) VALUES (" . $id . ")";
        $conn->query($lquery);
    }

    $add = (existcheck($conn, $id) == true) ? "view" : "make";

    /*$query = "SELECT * FROM form_values WHERE id_pedido = ".$id;
    if($result = $conn->query($query)){
    $rowu = $result->fetch(PDO::FETCH_ASSOC);
    $query = "SELECT * FROM computador WHERE pedido_id = ".$id;
    if($result = $conn->query($query)){
    $row = $result->fetch(PDO::FETCH_ASSOC);*/
    ob_start();
    $query = "SELECT * FROM maquinasview WHERE id_pedido = " . $id;
    if ($result = $conn->query($query)) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <script src="scripts/elementInjection.js"></script>
            <script src="scripts/asssingpcPage.js"></script>
            <link rel="stylesheet" href="stylesheet/assignpcstl.css?random=999999">
            <title data-tr="cyber_title">CyberSculpt - Intranet</title>
        </head>

        <body>
            <div id="fullscreen">
                <?php
                Popup::Listen('newcheck');
                Popup::Listen('pcupdated');
                ob_end_flush();
                ?>
                <?php include('protected/header.html') ?>
                <div id="workspace">
                    <div id="topnav">
                        <div id="tool-bar">
                            <button class="btn-round-icon toolbtn changebtn"><span data-tr="change">Change</span></button>
                            <button class="desktop btn-round-icon toolbtn" id="savebtn1"><span data-tr="save">Save</span></button>
                            <button class="mobile btn-round-icon toolbtn" id="savebtn2"><span data-tr="save">Save</span></button>
                            <button class="btn-round-icon toolbtn" form="toolform" type="submit" name="makec"><span data-tr="<?php  echo $add;?>_check">
                            <?php  echo $add;?> Checklist</span></button>
                            <a href="form-reader.php?id_pedido=<?php echo $id;?>"><button class="btn-round-icon toolbtn"><span data-tr="return" >Voltar</span></button></a>
                        </div>
                        <form id="toolform" action="" method="post"></form>
                    </div>
                    <div id="mainpanel">
                        <fieldset>
                            <legend class="fieldtitle" data-tr="informations">Informações</legend>
                            <table>
                                <tr>
                                    <td class="desktop">
                                        <label for="id_pedido" data-tr-lb="rqt_num_label">Numero do pedido<span data-kp>&nbsp;&#129146;</span></label>
                                        <input disabled value="<?php echo /* rowu */ mb_convert_encoding($row['id_pedido'], "UTF-8"); ?>" type="text" size="2" name="id_pedido" id="id_pedido">
                                    </td>
                                    <td>
                                        <label for="status_pedido" data-tr-lb="rqt_status_label">Estado do pedido<span data-kp>&nbsp;&#129146;</span></label>
                                        <input disabled value="<?php echo /* rowu */ mb_convert_encoding($row['status_pedido'], "UTF-8"); ?>" type="text" name="status_pedido" id="status_pedido">
                                    </td>
                                    <td>
                                        <label for="data_pedido" data-tr-lb="rqt_date_label">Data do pedido<span data-kp>&nbsp;&#129146;</span></label>
                                        <input disabled value="<?php echo /* rowu */ substr($row['data_pedido'], 0, -8); ?>" type="text" name="data_pedido" id="data_pedido">
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                        <form id="form1" name="form1" class="desktop" method="post">
                            <input type="text" style="display:none;" name="id_pedido" value="<?php echo $id; ?>">
                            <fieldset disabled class="fielset">
                                <legend class="fieldtitle" data-tr="Username">Utilizador</legend>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="comp_nome" data-tr-lb="full_name_label">Nome Completo<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input disabled value="<?php echo /* rowu */ mb_convert_encoding($row['comp_nome'], "UTF-8"); ?>" type="text" size="30" name="comp_nome" id="comp_nome">
                                        </td>
                                        <td>

                                            <label for="username" data-tr-lb="user_name_label">Nome de Utilizador<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo  mb_convert_encoding($row['username'], "UTF-8"); ?>" type="text" size="30" name="username" id="username">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="act_empresa" data-tr-lb="company">Empresa<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo /* rowu */ mb_convert_encoding($row['act_empresa'], "UTF-8"); ?>" disabled type="text" size="30" name="act_empresa" id="act_empresa">
                                        </td>
                                        <td>
                                            <label for="user_pass" data-tr-lb="user_pass_label">Password do Utilizador<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="password" value="<?php  echo substr(mb_convert_encoding($row['user_pass'], "UTF-8"),0,16); ?>" size="30" name="user_pass" id="user_pass" disabled><span  type="button" target="user_pass" class="toggle-password"><img src="design/eye_white.svg"></span><span type="button" class="passchangebtn" name="c_user_pass" value="1"><img src="design/key_white.svg"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="act_departamento" data-tr-lb="department">Departamento<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo /* rowu */ mb_convert_encoding($row['act_departamento'], "UTF-8"); ?>" disabled type="text" size="30" name="act_departamento" id="act_departamento">
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                            <fieldset disabled class="fielset">
                                <legend class="fieldtitle" data-tr="computer">Computador</legend>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="pc_name" data-tr-lb="name">Nome<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_name" id="pc_name" value="<?php echo mb_convert_encoding($row['pc_name'], "UTF-8"); ?>">
                                        </td>
                                        <td>
                                            <label for="pc_mac" data-tr-lb="mac_label">MAC address<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_mac" id="pc_mac" value="<?php echo mb_convert_encoding($row['pc_mac'], "UTF-8"); ?>">
                                        </td>
                                        <td>
                                            <label for="pc_so" data-tr-lb="os_label">Sistema Operativo<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_so" id="pc_so" value="<?php echo mb_convert_encoding($row['pc_so'], "UTF-8"); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="pc_cpu" data-tr-lb="cpu_label">Processador<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_cpu" id="pc_cpu" value="<?php echo mb_convert_encoding($row['pc_cpu'], "UTF-8"); ?>">
                                        </td>
                                        <td>
                                            <label for="pc_mem" data-tr-lb="ram_label">Memória RAM<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_mem" id="pc_mem" value="<?php echo mb_convert_encoding($row['pc_mem'], "UTF-8"); ?>">
                                        </td>
                                        <td>
                                            <label for="pc_drive" data-tr-lb="drive_label">Hard Drive<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="radio" <?php if ($row['pc_drive'] == "1") {echo "checked";} ?> name="pc_drive" id="pc_driver1" value="1"><label for="pc_drive1">SSD</label>
                                            <input type="radio" <?php if ($row['pc_drive'] == "0") {echo "checked";} ?> name="pc_drive" id="pc_driver2" value="0"><label for="pc_drive2">HDD</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="pc_serial" data-tr-lb="serial_label">Serial Number<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_serial" id="pc_serial" value="<?php echo mb_convert_encoding($row['pc_serial'], "UTF-8"); ?>">
                                        </td>
                                    </tr>
                                </table>
                                <hr>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="admin_user" data-tr-lb="admin_user_label">Nome de Admin<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" size="30" name="admin_user" id="admin_user" value="<?php echo mb_convert_encoding($row['admin_user'], "UTF-8"); ?>">
                                        </td>
                                        <td>
                                            <label for="admin_pass" data-tr-lb="admin_pass_label">Password de Admin<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="password" size="30" name="admin_pass" id="admin_pass" disabled value="<?php echo substr(mb_convert_encoding($row['admin_pass'], "UTF-8"),0,16);?>"><span type="button" target="admin_pass" class="toggle-password"><img src="design/eye_white.svg"></span><span type="button" class="passchangebtn" name="c_admin_pass" value="1"><img src="design/key_white.svg" height="12px"></span>
                                        </td>
                                    </tr>
                                </table>
                                <hr>
                                <label for="pc_malfunctions" data-tr-lb="defects_label">Defeitos<span data-kp>&nbsp;&#129146;</span></label>
                                <textarea style="resize: none;" name="pc_malfunctions" id="pc_malfunctions" cols="50" rows="5"><?php echo mb_convert_encoding($row['pc_malfunctions'], "UTF-8"); ?></textarea>
                            </fieldset>
                            <div id="fieldfoot">
                                <input style="display:none;" type="submit" value="Salvar" name="updatepc" id="submitbt1">
                            </div>
                        </form>
                        <form id="form2" name="form2" class="mobile" method="post">
                            <input type="text" style="display:none;" name="id_pedido" value="<?php echo $id; ?>">
                            <fieldset disabled class="fielset">
                                <legend class="fieldtitle">Utilizador</legend>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="comp_nome">Nome Completo<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input disabled value="<?php echo /* rowu */ mb_convert_encoding($row['comp_nome'], "UTF-8"); ?>" type="text" size="30" name="comp_nome" id="comp_nome">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="act_empresa">Empresa<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo /* rowu */ mb_convert_encoding($row['act_empresa'], "UTF-8"); ?>" disabled type="text" size="30" name="act_empresa" id="act_empresa">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="act_departamento">Departamento<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo /* rowu */ mb_convert_encoding($row['act_departamento'], "UTF-8"); ?>" disabled type="text" size="30" name="act_departamento" id="act_departamento">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="username">Nome de Utilizador<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo mb_convert_encoding($row['username'], "UTF-8"); ?>" type="text" size="30" name="username" id="username">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="user_pass">Password do Utilizador<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="password" value="<?php  echo substr(mb_convert_encoding($row['user_pass'], "UTF-8"),0,16); ?>" size="30" name="user_pass" id="user_pass2">
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                            <fieldset disabled class="fielset">
                                <legend class="fieldtitle">Computador</legend>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="pc_name">Nome<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_name" id="pc_name" value="<?php echo mb_convert_encoding($row['pc_name'], "UTF-8"); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="pc_mac">MAC address<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_mac" id="pc_mac" value="<?php echo mb_convert_encoding($row['pc_mac'], "UTF-8"); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="pc_so">Sistema Operativo<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_so" id="pc_so" value="<?php echo mb_convert_encoding($row['pc_so'], "UTF-8"); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="pc_cpu">Processador<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_cpu" id="pc_cpu" value="<?php echo mb_convert_encoding($row['pc_cpu'], "UTF-8"); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="pc_mem">Memória RAM<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_mem" id="pc_mem" value="<?php echo mb_convert_encoding($row['pc_mem'], "UTF-8"); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="pc_drive">Hard Drive<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="radio" <?php if ($row['pc_drive'] == "1") {
                                                                    echo "checked";
                                                                } ?> name="pc_drive" id="pc_driver1" value="1"><label for="pc_drive1">SSD</label>
                                            <input type="radio" <?php if ($row['pc_drive'] == "0") {
                                                                    echo "checked";
                                                                } ?> name="pc_drive" id="pc_driver2" value="0"><label for="pc_drive2">HDD</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="pc_serial">Serial Number<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" name="pc_serial" id="pc_serial" value="<?php echo mb_convert_encoding($row['pc_serial'], "UTF-8"); ?>">
                                        </td>
                                    </tr>
                                </table>
                                <hr>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="admin_user">Nome de Admin<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="text" size="30" name="admin_user" id="admin_user" value="<?php echo mb_convert_encoding($row['admin_user'], "UTF-8"); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="admin_pass">Password de Admin<span data-kp>&nbsp;&#129146;</span></label>
                                        </td>
                                        <td>
                                            <input type="password" size="30" name="admin_pass" id="admin_pass2" value="<?php  echo mb_convert_encoding($row['admin_pass'], "UTF-8"); ?>">
                                        </td>
                                    </tr>
                                </table>
                                <hr>
                                <label for="pc_malfunctions">Defeitos<span data-kp>&nbsp;&#129146;</span></label>
                                <textarea style="resize: none;" name="pc_malfunctions" id="pc_malfunctions" cols="30" rows="5"><?php echo mb_convert_encoding($row['pc_malfunctions'], "UTF-8"); ?></textarea>
                            </fieldset>
                            <div id="fieldfoot">
                                <input style="display:none;" name="updatepc" type="submit" value="Salvar" id="submitbt2">

                            </div>
                        </form>
                    </div>
            <?php }
    } /*}*/ catch (\Throwable $th) {
        deadpage("Page malfunction. #0053", $th->getMessage());
    } ?>
                </div>
            </div>
        </body>

        </html>