<?php
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
$param = getparam();
$valid = false;
if (isset($_POST['user'])&&isset($_POST['pass'])) {
    if ($_POST['user']==$param['settings_user']&&$_POST['pass']==$param['settings_pass']) {
        setcookie('valid','k45f#%6wg',time() + 5 * 60);
        $valid = true;
    }
}
if (isset($_COOKIE['valid'])&&$_COOKIE['valid']=='k45f#%6wg') {
    $valid = true;
}

if (isset($_POST['submit'])) {
    updparam();
}
if (isset($_POST['default'])) {
    defaultparam();
}


?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <script src="/scripts/"></script>
    <link rel="stylesheet" href="stylesheet/errorstl.css?random=999999">
    <link rel="icon" href="design/logo.png">
    <title data-tr="cyber_title">CyberSculpt - Intranet</title>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        function clpopup() {
            var au =document.querySelector('#popup');
            au.style.display = 'none';
            window.location.reload();
        }
    </script>
    <style>
        td{
            text-align: left;
        }
        textarea {
            vertical-align: top;
        }
        body{
            overflow-y: auto;
        }
        #btn{
            width: 200px;
            background-color: #3E5B76;
            border: none;
            border-radius: 15px;
            font-weight: 600;
            padding-top: 3px;
            padding-bottom: 3px;
            color:#fff;
            cursor: pointer;
        }
        #btn:hover{
            background-color: #f2f2f2;
            color:#3E5B76;
            scale: 1.05;
        }   
#popup .contents{
    width: 300px;
    background: #fff;
    position: absolute;
    border-radius: 8px;
    text-align: center;
    padding: 10px 10px 10px 10px;
    font-weight: 600;
    top: 40%;
    position: absolute;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    text-align: center; 
    z-index: 2;
}

#popup button{
    border: none;
    background: white;
    font-weight: 600;
    color: black;
    border-radius: 15px;
    font-size: medium;
    box-shadow: 2px 2px 5px gray;
    scale: 1.05;
}
#popup button:hover{
    background: rgb(221, 218, 218);
    color: white;
}

.blocker{
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    content: ' ';
    background: rgba(0,0,0,.5);
    z-index: 1;
}

#passbtn{
    cursor: pointer;
    font-size: large;
    padding: 0px;
    margin-left: 5px;
    user-select: none;
}
    </style>
</head>
<body>
    <?php
        setpopup('updparam','Settings updated successfully.','right');
    ?>
        <?php include('protected/header.html')?>
        <div id="workspace">
            <div id="mainpanel">
                <div class="menupanel" centered> 
                    <div class="logbox">
                        <form method="post" <?php if ($valid==true){echo "style='display:none;'";}?>>
                            <input type="text" name="user" placeholder="admin username"><br>
                            <input type="password" name="pass" placeholder="admin password"><br>
                            <input type="submit" value="submit">
                        </form>
                        <form method="post" <?php if ($valid==false){echo "style='display:none;'";}?>>
                            <fieldset>
                                <legend>LDAP settings</legend>
                                <table>
                                    <tr>
                                        <td align="left">
                                            <label for="ldap_host">LDAP host -></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo $param['ldap_host'];?>" type="text" name="ldap_host" id="ldap_host">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="ldap_port">LDAP port -></label>
                                            </td>
                                        <td>
                                            <input value="<?php echo $param['ldap_port'];?>" type="text" name="ldap_port" id="ldap_port">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="ldap_host">LDAP domain -></label>
                                            </td>
                                        <td>
                                            <input value="<?php echo $param['ldap_domain'];?>" type="text" name="ldap_domain" id="ldap_domain">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="ldap_host">LDAP base -></label>
                                            </td>
                                        <td>
                                            <input value="<?php echo $param['ldap_base'];?>" type="text" name="ldap_base" id="ldap_base">
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                            <fieldset>
                                <legend>Email general</legend>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="email_name">Email name -></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo $param['email_name'];?>" type="text" name="email_name" id="email_name">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="email_sender_user">Email sender user -></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo $param['email_sender_user'];?>" type="text" name="email_sender_user" id="email_sender_user">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="email_sender_pass">Email sender pass -></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo  bin2hex(random_bytes(8))?>" type="password" name="email_sender_pass" id="email_sender_pass" disabled><span id="passbtn">&#x2611;</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="email_smtp_host">Email Smtp host -></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo $param['email_smtp_host'];?>" type="text" name="email_smtp_host" id="email_smtp_host">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="email_smtp_port">Email Smtp port -></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo $param['email_smtp_port'];?>" type="text" name="email_smtp_port" id="email_smtp_port">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="email_smtp_encryption">Email Smtp Encryption (tls, ssl) -></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo $param['email_smtp_encryption'];?>" type="text" name="email_smtp_encryption" id="email_smtp_encryption">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="arf">Activate "receiver feedback" -></label>
                                        </td>
                                        <td>
                                            <input type="radio" id="arf1" <?php if($param['activate_receiver_feedback']=="1"){echo "checked";}?> name="activate_receiver_feedback" value="1"><Label for="arf1">Yes</Label>
                                            <input type="radio" id="arf0" <?php if($param['activate_receiver_feedback']=="0"){echo "checked";}?> name="activate_receiver_feedback" value="0"><Label for="arf0">No</Label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="arp">Activate "return message" -></label>
                                        </td>
                                        <td>
                                            <input type="radio" id="arp1" <?php if($param['activate_return_postform']=="1"){echo "checked";}?> name="activate_return_postform" value="1"><Label for="arp1">Yes</Label>
                                            <input type="radio" id="arp0" <?php if($param['activate_return_postform']=="0"){echo "checked";}?> name="activate_return_postform" value="0"><Label for="arp0">No</Label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="arp">Activate "prebuilt messages" -></label>
                                        </td>
                                        <td>
                                            <input type="radio" id="apm1" <?php if($param['activate_prebuilt_message']=="1"){echo "checked";}?> name="activate_prebuilt_message" value="1"><Label for="apm1">Yes</Label>
                                            <input type="radio" id="apm0" <?php if($param['activate_prebuilt_message']=="0"){echo "checked";}?> name="activate_prebuilt_message" value="0"><Label for="apm0">No</Label>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                            <fieldset>
                                <legend>Email to receive feedback</legend>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="email_receiver">Email receiver -></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo $param['email_receiver'];?>" type="text" name="email_receiver" id="email_receiver">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="email_cc">Email carbon copy -></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo $param['email_cc'];?>" type="text" name="email_cc" id="email_cc">
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="vertical-align:top;" >
                                            <label for="email_subject">Email subject -></label>
                                        </td>
                                        <td>
                                            <textarea name="email_subject" id="email_subject" style="resize: none;" cols="20" rows="5"><?php echo $param['email_subject'];?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="email_attachment_name">Email attachment name -></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo $param['email_attachment_name'];?>" type="text" name="email_attachment_name" id="email_attachment_name">
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                            <fieldset>
                                <legend>Prebuild message settings</legend>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="message_title">Message subject -></label>
                                        </td>
                                        <td>
                                            <textarea name="message_title" id="message_title" style="resize: none;" cols="20" rows="5"><?php echo $param['message_title'];?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="message_cc">Message carbon copy -></label>
                                        </td>
                                        <td>
                                            <input value="<?php echo $param['message_cc'];?>" type="text" name="message_cc" id="message_cc">
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                            <input id="btn" name="submit" type="submit" id="submit" value="Save">
                        </form>
                        <form <?php if ($valid == false) {echo "style='display:none;'";}?> method="post"><input id="btn" name="default" type="submit" value="Return to default"></form>
                        <span style=" opacity:0;">space</span>
                    </div>
                </div>
            </div>
        <?php
                        
                    } catch (\Throwable $th) {
                        deadpage("Page malfunction. #0051",$th->getMessage());
                    }
                        ?>
</body>
</html>