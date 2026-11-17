<?php 
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function formataddress($string) {
    $ar = array();
    $ar = explode(',',$string);
    return $ar;
}
function sendemail($row): bool{
try {
    $param = getparam();
    if ($param['activate_receiver_feedback']==false||$param['activate_receiver_feedback']==0) {
        return false;   
    }

    $body = file_get_contents(__DIR__.'/email.html');
    $body = str_replace('$row[comp_nome]',$row['comp_nome'],$body);
    $body = str_replace('$row[act_empresa]',$row['act_empresa'],$body);
    $body = str_replace('$row[act_departamento]',$row['act_departamento'],$body);
    $body = str_replace('$row[contact_nome]',$row['contact_nome'],$body);
    $body = str_replace('$row[contact_email]',$row['contact_email'],$body);
    $body = str_replace('$row[data_pedido]',$row['data_pedido'],$body);
    $body = str_replace('$row[id_pedido]',$row['id_pedido'],$body);
     
    $filename = "result.docx"; //attachment file name
    $data = sys_get_temp_dir()."/".$filename; // attachment file path
    
    $name = $param['email_name'];//sender name
    $subject = $param['email_subject'] ;//email title
    $email = $param['email_sender_user'];   // sender email for login
    $pass = decryptPassword($param['email_sender_pass'],$param['secretkey']); // sender password 

    $destination = formataddress($param['email_receiver']);    // receiver email
    $cc = formataddress($param['email_cc']);
    $host = $param['email_smtp_host'];  
    $port = $param['email_smtp_port'];  
    $encr = $param['email_smtp_encryption'];
    $attachname = $param['email_attachment_name'];  

    $mail = new PHPMailer(true);
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;   //show debug
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = $host;                                 //smtp host severname
    $mail->SMTPSecure = $encr;   
    $mail->Port = $port;                                 //smtp host port
    $mail->Username = $email;
    $mail->Password = $pass;
    $mail->setFrom($email,$name);
    foreach ($destination as $val) {
        if ($val != "") {
            $mail->AddAddress($val);
        }
    }
    foreach ($cc as $va) {
        if ($va != "") {
            $mail->addCC($va);
        }
    }
    $mail->addAttachment($data,$attachname.".docx");      //nome do anexo para o leitor
    $mail->Subject = $subject;
    //$mail->Body = $body;
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->ContentType = 'text/html';
    $mail->Encoding = 'base64';
    $body = mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8');
    $mail->Body = $body;

        
    $mail->addEmbeddedImage(__DIR__.'/design/logotipo.png','logotipo.png');
    //$mail->addEmbeddedImage(__DIR__.'/design/home.jpg','home.jpg');
    $mail->addEmbeddedImage(__DIR__.'/design/facebook_white.png','facebook_white.png');
    $mail->addEmbeddedImage(__DIR__.'/design/linkedin_white.png','linkedin_white.png');
    $mail->addEmbeddedImage(__DIR__.'/design/social_white.png','social_white.png');    

    $mail-> send();
    
    return true;

} catch (\Throwable $th) {
    deadpage("Send email failed.",$th->getMessage().'---'.$th->getFile().'---'.$th->getLine().'this dir---'.__DIR__);
    return false;
}
}


function sendmessage($row, $type): bool{
    try {

        if (isEmailBlocked($row['contact_email'])) {
            return true;
        }

        $param = getparam();

        switch ($type) {
            case 'compl':
                $body = file_get_contents(__DIR__.'/email_complete.html');
                break;
            case 'proc':
                $body = file_get_contents(__DIR__.'/email_inprocess.html');
                break;
            case 'refus':
                $body = file_get_contents(__DIR__.'/email_canceled.html');
                break;
            case 'return':
                $body = file_get_contents(__DIR__.'/email_return.html');
                break;
            case 'comm':
                $body = file_get_contents(__DIR__.'/email_comment.html');
                break;
            default:
                throw new Exception("Error: no message type in SendMessage()", 1);
                //break;
        }

        $body = str_replace('$row[contact_nome]',$row['contact_nome'],$body);
        $body = str_replace('$param[email_receiver]',$param['email_receiver'],$body);
        $body = str_replace('$row[id_pedido]',$row['id_pedido'],$body);

        
        $name = $param['email_name'];//sender name
        $subject = $param['message_title'];//email title
        $email = $param['email_sender_user']; // sender email for login
        $pass = decryptPassword($param['email_sender_pass'],$param['secretkey']);     // sender password 
        $destination = $row['contact_email']; // receiver email
        $host = $param['email_smtp_host'];  
        $port = $param['email_smtp_port'];
        $encr = $param['email_smtp_encryption'];
        $cc = formataddress($param['message_cc']);  

        $mail = new PHPMailer(true);
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;   //show debugs
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $host;                                 //smtp host severname
        $mail->SMTPSecure = $encr;  // 'tls' or 'ssl'
        $mail->Port = $port;                                 //smtp host port
        $mail->Username = $email;
        $mail->Password = $pass;
        $mail->setFrom($email,$name);
        $mail->addAddress($destination);
        foreach ($cc as $va) {
            if ($va != "") {
                $mail->addCC($va);
            }
        }
        $mail->Subject = $subject;
        //$mail->Body = $body;
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->ContentType = 'text/html';
        $body = mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8');
        $mail->Body = $body;
    
        $mail->addEmbeddedImage(__DIR__.'/design/logotipo.png','logotipo.png');
        //$mail->addEmbeddedImage(__DIR__.'/design/home.jpg','home.jpg');
        $mail->addEmbeddedImage(__DIR__.'/design/facebook_white.png','facebook_white.png');
        $mail->addEmbeddedImage(__DIR__.'/design/linkedin_white.png','linkedin_white.png');
        $mail->addEmbeddedImage(__DIR__.'/design/social_white.png','social_white.png');

        $mail->send();

        return true;
    
    } catch (\Throwable $th) {
        deadpage("Send email failed.",$th);
        return false;
    }
}


