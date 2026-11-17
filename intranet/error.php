<?php 
    require "protected/intranetlib.php";
    if (!isset($_COOKIE['e_title'])&&!isset($_COOKIE['e_message'])&&!isset($_COOKIE['e_type'])) {
        echo "<script> window.location.href = '404'; </script>";
    }
    
    @$title = $_COOKIE['e_title'];
    @$message = $_COOKIE['e_message']; 
    @$type = $_COOKIE['e_type'];

    setcookie('e_title',"",time()-3600);
    setcookie('e_message',"",time()-3600);
    setcookie('e_type',"",time()-3600);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="scripts/elementInjection.js"></script>
    <link rel="stylesheet" href="stylesheet/errorstl.css?random=999999">
    <link rel="icon" href="logo.png">
    <title data-tr="cyber_title">CyberSculpt - Intranet</title>
</head>
<body>
    <div id="fullscreen">
    <?php include('protected/header.html')?>
        <div id="workspace">
            <div class="menupanel" centered> 
                <div class="logbox">
                    <img <?php switch ($type) {
                        case '404':
                            echo 'src="design/404page.png"';
                            break;
                        case 'dead':
                            echo 'src="design/deadpage.png"';
                            break;
                        case 'permission':
                            echo 'src="design/prohibition.png"';
                            break;
                        default:
                            echo 'src="design/deadpage.png"';
                            break;
                    }?> id="deadpng">
                    <h2><?php echo $title;?></h2>
                    <p><?php echo $message;?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>