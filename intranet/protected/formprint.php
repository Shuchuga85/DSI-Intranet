<?php
require_once 'phpoffice/phpword/src/PhpWord/TemplateProcessor.php';
require_once 'phpoffice/phpword/src/PhpWord/PhpWord.php';
require_once 'phpoffice/phpword/src/PhpWord/Settings.php';
require_once 'phpoffice/phpword/src/PhpWord/Shared/ZipArchive.php';
require_once 'phpoffice/phpword/src/PhpWord/Shared/Text.php';



use PhpOffice\PhpWord\TemplateProcessor;

function printe($conn,$formid,$email = false):bool{
try {
    
    $phpword = new TemplateProcessor('./protected/Template.docx'); 

    /*$query = "SELECT * FROM form_values WHERE id_pedido=".$formid;
    $cquery = "SELECT * FROM computador WHERE pedido_id=".$formid;*/

    $query = "SELECT * FROM maquinasview WHERE id_pedido=".$formid;

    /*
    if ($result = $conn->query($query)) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        foreach ($row as $key => $val) {
            if ($key != "penkanguru") {
                if ($key != "cr_email") {
                    if ($key != "type_mail") {
                        if ($key != "type_tele") {
                            if ($key != "card_tele") {
                                if ($key != "cr_sap") {
                                    if ($key != "easy_schedule") {
                                        if ($key != "vpn") {
                                            if ($key != "office_lang") {
                                                if ($key != "services") {
                                                    if ($key != "cyber_card") {
                                                        $phpword->setValue($key, $val);
                                                    } else {
                                                        if ($val == 1) {
                                                            $phpword->setValue($key, "X");
                                                        } else {
                                                            $phpword->setValue($key, "");
                                                        }
                                                    }
                                                } else {
                                                    $aux = explode("-", $val);
                                                    foreach ($aux as $n) {
                                                        $phpword->setValue($n, "X");
                                                    }
                                                    $phpword->setValue("intouch", "");
                                                    $phpword->setValue("pda", "");
                                                    $phpword->setValue("sage", "");
                                                }
                                            } else {
                                                $aux = explode("-", $val);
                                                foreach ($aux as $n) {
                                                    $phpword->setValue($n, "X");
                                                }
                                                $phpword->setValue("port", "");
                                                $phpword->setValue("engl", "");
                                            }
                                        } else {
                                            if ($val == 1) {
                                                $phpword->setValue($key . "1", "X");
                                            } else {
                                                $phpword->setValue($key . "0", "X");
                                            }
                                            $phpword->setValue($key . "1", "");
                                            $phpword->setValue($key . "0", "");
                                        }
                                    } else {
                                        if ($val == 1) {
                                            $phpword->setValue($key . "1", "X");
                                        } else {
                                            $phpword->setValue($key . "0", "X");
                                        }
                                        $phpword->setValue($key . "1", "");
                                        $phpword->setValue($key . "0", "");
                                    }
                                } else {
                                    if ($val == 1) {
                                        $phpword->setValue($key . "1", "X");
                                    } else {
                                        $phpword->setValue($key . "0", "X");
                                    }
                                    $phpword->setValue($key . "1", "");
                                    $phpword->setValue($key . "0", "");
                                }
                            } else {
                                if ($val == 1) {
                                    $phpword->setValue($key . "1", "X");
                                } else {
                                    $phpword->setValue($key . "0", "X");
                                }
                                $phpword->setValue($key . "1", "");
                                $phpword->setValue($key . "0", "");
                            }
                        } else {
                            $aux = explode("-", $val);
                            foreach ($aux as $n) {
                                $phpword->setValue($n, "X");
                            }
                            $phpword->setValue("fixo", "");
                            $phpword->setValue("dect", "");
                            $phpword->setValue("exte", "");
                        }
                    } else {
                        $aux = explode("-", $val);
                        foreach ($aux as $n) {
                            $phpword->setValue($n, "X");
                        }
                        $phpword->setValue("outlook", "");
                        $phpword->setValue("thunderbird", "");
                    }
                } else {
                    if ($val == 1) {
                        $phpword->setValue($key . "1", "X");
                    } else {
                        $phpword->setValue($key . "0", "X");
                    }
                    $phpword->setValue($key . "1", "");
                    $phpword->setValue($key . "0", "");
                }
            } else {
                if ($val == 1) {
                    $phpword->setValue($key . "1", "X");
                } else {
                    $phpword->setValue($key . "0", "X");
                }
                $phpword->setValue($key . "1", "");
                $phpword->setValue($key . "0", "");
            }
            
        }
    }*/
    if ($result = $conn->query($query)) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
    }
    if ($row) {
        $config = [
            'penkanguru' => ['type' => 'boolean', 'true' => 'X', 'false' => ''],
            'cr_email' => ['type' => 'boolean', 'true' => 'X', 'false' => ''],
            'type_mail' => ['type' => 'multi', 'options' => ['outlook', 'thunderbird']],
            'type_tele' => ['type' => 'multi', 'options' => ['fixo', 'dect', 'exte']],
            'card_tele' => ['type' => 'boolean', 'true' => 'X', 'false' => ''],
            'cr_sap' => ['type' => 'boolean', 'true' => 'X', 'false' => ''],
            'easy_schedule' => ['type' => 'boolean', 'true' => 'X', 'false' => ''],
            'cyber_card' => ['type' => 'boolean', 'true' => 'X', 'false' => ''],
            'office_lang' => ['type' => 'multi', 'options' => ['port', 'engl']],
            'services' => ['type' => 'multi', 'options' => ['intouch', 'pda', 'sage']],
            'vpn' => ['type' => 'boolean', 'true' => 'X', 'false' => ''],

            // Add more fields as needed
        ];

        foreach ($row as $key => $val) {
            if (isset($config[$key])) {
                switch ($config[$key]['type']) {
                    case 'boolean':
                        $phpword->setValue($key . "1", $val == 1 ? $config[$key]['true'] : '');
                        $phpword->setValue($key . "0", $val == 0 ? $config[$key]['true'] : '');
                        break;
                    case 'multi':
                        $aux = explode("-", $val);
                        foreach ($config[$key]['options'] as $option) {
                            $phpword->setValue($option, in_array($option, $aux) ? 'X' : '');
                        }
                        break;
                    default:
                        $phpword->setValue($key, $val);
                        break;
                }
            } else {
                $phpword->setValue($key, $val);
            }
        }
    }

    
    $pathToSave = sys_get_temp_dir().'/result.docx'; 
    $phpword->saveAs($pathToSave);


    if ($email == true) { 
        sendemail($row);
        unlink($pathToSave);
    }else{                
        require_once "protected/pdfdownload.php";
    }
    return true;
}catch (\Throwable $th) {
    deadpage("Print failed. #006",$th->getMessage());
    return false;
}
}
?>