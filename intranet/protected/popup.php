<?php

class Popup {

    const defaultformat =
    "<div id='popup' style='display:block;'>
    <div class='blocker'></div>
    <div class='contents'>
    <img class='kopupimg' src='design/information.png' height='40px'>
    <p style='color:#2b86cb;'>[Popup]</p>
    <button id='close' class='close'>Close</button>
    </div></div>";

    //IMAGE
    const INFO_ICON = "/intranet/design/information.png";
    const SUCCESS_ICON = "/intranet/design/success.png";
    const EXCLAMATION_ICON = "/intranet/design/exclamation.png";
    //BUTTONS
    const BUTTON_CLOSE = "close";
    const BUTTON_OK = "ok";
    const BUTTON_YES_NO = "yes_no";
    const BUTTON_DELETE_EXIT = "delete_exit";
    //DIALOG RESULT
    const DIALOG_YES = 'yes';
    const DIALOG_NO = 'no';
    const DIALOG_OK = 'ok';
    const DIALOG_DELETE = 'delete';

    var string $line;
    var string $icon;
    var $button;
    var string $translations;

    function __construct(string $lines, string $iconpath = "", $buttons = "close", string $includeTranslations = "")
    {
        $this->line = $lines;
        $this->icon = $iconpath;
        $this->button = $buttons;
        $this->translations = $includeTranslations;
    }

    function Draw(){
        try {
            $l =  $this->line;
            $i =  $this->icon;
            $b =  $this->button;
            $t =  $this->translations;
            if (isset($l)&&$l!=""&&isset($i)&&$i!="") {
                echo "<div id='popup' style='display:block;'>";
                echo self::defaultStyle;
                echo self::defaultScript;
                echo "<div class='blocker'></div>
                <div class='contents'>
                <img class='kopupimg' src='$i' height='40px'>
                <p $t>$l</p>
                ".self::PickButton($b, $t)."
                </div></div>";
            }else{
                return false;
            }

        } catch (\Throwable $th) {
            return $th;
        }
        return true;
    }

    static function Call($name, $line, $icon = "", $btn = "", $trl = "")
    {
        setcookie($name, json_encode(['line' => $line , 'icon' => $icon, 'button' => $btn, 'translation' => $trl]), time() + 60);
    }

    static function Listen($cookiename){
        try {
            if (isset($_COOKIE[$cookiename])) {
                $arr = json_decode($_COOKIE[$cookiename]);
                $l = isset($arr->line)? $arr->line : "";
                $i = isset($arr->icon)? $arr->icon : "";
                $b = isset($arr->button)? $arr->button : "";
                $t = isset($arr->translation)? $arr->translation : "";
                Popup::StaticDraw($l, $i, $b, $t);
                setcookie($cookiename, "deleted", time() - 3600);
            }
        } catch (\Throwable $th) {
            die ($th);
        }
        return true;
    }

    static function StaticDraw($line = "", $icon = "", $button = "close", $translation = ""){
        try {
            if (isset($line)&&$line!=""&&isset($icon)&&$icon!="") {
                echo "<div id='popup' style='display:block;'>";
                echo self::defaultStyle;
                echo self::defaultScript;
                echo "<div class='blocker'></div>
                <div class='contents'>
                <img class='kopupimg' src='$icon' height='40px'>
                <p $translation>$line</p>
                ".Popup::PickButton($button, $translation)."
                </div></div>";

            }

        } catch (\Throwable $th) {
            die($th);
        }
        return true;
    }

    static function PickButton($btn, $t, $method = 'post') {
        $t = $t!=""&&$t!=null;
        $add = "";
        if (is_array($btn)) {
            return self::DrawButton($btn,$method);
        }
        switch ($btn) {
            case 'close':
                $add = $t? "data-tr='close'" : "";
                return "<button id='popup_close' type='button' $add class='popupbtn'>Close</button>";
                break;
            case 'ok':
                return"<form id='fakeform' method='$method'><button  form='fakeform' name='popup' value='ok' id='popup_ok' class='popupbtn'>Ok</button>
                </form>";
                break;
            case 'yes_no':
                $add = $t? ["data-tr='yes'", "data-tr='no'"] : ["",""];
                return "<form id='fakeform' method='$method'>
                <button form='fakeform' name='popup' ".$add[0]." value='yes' id='popup_yes' class='popupbtn'>Yes</button>
                <button form='fakeform' name='popup' ".$add[1]." value='no' id='popup_no' class='popupbtn'>No</button>
                </form>";
                break;
            case 'delete_exit':
                $add = $t? ["data-tr='delete'", "data-tr='exit'"] : ["",""];
                return "<form id='fakeform' method='$method'>
                <button form='fakeform' name='popup' ".$add[0]." value='delete' id='popup_yes' class='popupbtn'>Delete</button>
                <button ".$add[1]." id='popup_exit' type='button' class='popupbtn'>Exit</button>
                </form>";
                break;
            default:
                $add = $t? "data-tr='close'" : "";
                return "<button id='popup_close' type='button' $add class='popupbtn'>Close</button>";
                break;
        }
    }

    static function GetPopupResult(string $dialog = Popup::DIALOG_OK){
        try {
            if (isset($_POST['popup'])) {
                $result = $_POST['popup'];
                return ($dialog==$result);
            }
            return false;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    static function MakeButton($text, $submit = true, $fieldname = "popup", $value="", $method = 'post', $translations = "") {
        $button = [
        'text' => $text, 
        'submit' => $submit, 
        'field'=> $fieldname,
        'val' => $value,
        'method' => $method,
        'trl' => $translations
        ];
        return $button;
    }
    
    static function DrawButton($btn ,$method) {
        $button = "<form id='fakeform' method='$method'>";
            foreach ($btn as $value) {
                $name = $value['text'];
                $submit = $value['submit'] ? 'submit' : 'button';
                $field = $value['field']==""? 'popup' : $value['field'];
                $val = $value['val'];
                $method = $value['method'];
                $trl = $value['trl'];
                $button = $button."<button formmethod='$method' form='fakeform' type='$submit' name='$field' $trl value='$val' id='popup_$field' class='popupbtn'>$name</button>";
            }
        $button = $button."</form>";
        return $button;
    }
    const defaultScript = "
    <script>

    function clpopup() {
        var pop = document.querySelector('#popup');
        pop.remove();
    }

    $(document).ready(function() {
        $(document).on('click', '.popupbtn', function(e) {
            e.preventDefault();
            var form = $('#fakeform');
            if($(this).attr('type')=='button'){
                clpopup();
                return;
            }
            if (form.length === 0) {
                clpopup();
            } else {
                form.submit();
            }
           
        });
        $(document).on('click', '.blocker', function() {
            console.log('what');
           clpopup();
        });
    });
    </script>
    ";


    const defaultStyle = "
    <style>
    #popup .contents{
        width: 400px;
        background: #fff;
        position: fixed;
        border-radius: 8px;
        text-align: center;
        padding: 30px 40px 30px 40px;
        font-weight: 600;
        font-size:large;
        top: 50%;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        z-index: 200;
        color:black;
        transform: translateY(-50%);
        text-justify: auto;
        display: flex;
        flex-direction: column;
        align-items: center; 
        justify-content: center;
       
    }
    #popup iframe{
        width: 100%;
        flex-grow: 1;
    }
    .popupbtn{
        border: none;
        background: white;
        font-weight: 600;
        color: black;
        border-radius: 15px;
        font-size: 15px;
        box-shadow: 2px 2px 5px gray;
        scale: 1.05;
        z-index:300;
        margin:5px 10px;
        padding: 5px 10px;
        cursor:pointer;
        
    }
    .popupbtn:hover{
        background: rgb(244, 244, 244);
    }

    .popup-color-red{
        color:red;
    }

    .popup-color-green{
        color:green;
    }

    .blocker{
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        content: ' ';
        background: rgba(0,0,0,.5);
        z-index: 100;
    }

    .align_text_left{
        text-align: left !important;
    }
    
    #inputArea{
        resize: none;
        width: 100%;
        height: 3cm;
        font-size: large;
        margin-bottom: 15px;
    }
    #inputField{
        resize: none;
        width: 100%;
        font-size: large;
        margin-bottom: 15px;
        border : none;
        border-bottom: 2px solid #628DB6; 
        padding : 4px;
    }
    </style>
    ";
}

class InputBox extends Popup{
    var string $name;
    var string $method;
    var string $placeholder;

    function __construct(string $lines = "", string $name = "", string $placeholder = "",$buttons = "close", string $method = "post", string $includeTranslations = "") {
        parent::__construct($lines, "", $buttons, $includeTranslations);
        $this->name = $name;
        $this->method = $method;
        $this->placeholder = $placeholder;
    }

    function Draw(){
        try {
            $l = $this->line;
            $i = $this->icon;
            $b = $this->button;
            $t = $this->translations;
            $n = $this->name;
            $p = $this->placeholder;
            $m = $this->method;

            if (isset($l)&&$l!=""&&isset($i)&&$i!="") {
                echo "<div id='popup' style='display:block;'>";
                echo self::defaultStyle;
                echo self::defaultScript;
                echo "<div class='blocker'></div>
                <div class='contents'>
                <p $t >$l</p>
                <textarea name='$n' id='inputArea' form='fakeform' placeholder='$p'></textarea>
                ".self::PickButton($b, $t, $m)."
                </div></div>";
            }else{
                return false;
            }

        } catch (\Throwable $th) {
            return $th;
        }
        return true;
    }

    static function Call($name, $line, $fieldname = "", $placeholder = "", $btn = "", $method = "post", $trl = "")
    {
        setcookie($name, json_encode(['line' => $line , 'name'=> $fieldname, 'method'=> $method, 'placeholder'=> $placeholder, 'button' => $btn, 'translation' => $trl]), time() + 60);
    }

    static function Listen($cookiename){
        try {
            if (isset($_COOKIE[$cookiename])) {
                $arr = json_decode($_COOKIE[$cookiename]);
                $l = isset($arr->line)? $arr->line : "";
                $n = isset($arr->name)? $arr->name : "";
                $m = isset($arr->method)? $arr->method : "";
                $p = isset($arr->placeholder)? $arr->placeholder : "";
                $b = isset($arr->button)? $arr->button : "";
                $t = isset($arr->translation)? $arr->translation : "";
                self::StaticDraw($l, $n, $m, $p, $b, $t);
                setcookie($cookiename, "deleted", time() - 3600);
            }
        } catch (\Throwable $th) {
            die ($th);
        }
        return true;
    }


    static function StaticDraw($line = "", $name = "", $placeholder = "", $button = "ok", $method = "post", $translation = ""){
        try {
            if (isset($line)&&$line!="") {
                echo "<div id='popup' style='display:block;'>";
                echo self::defaultStyle;
                echo self::defaultScript;
                echo "<div class='blocker'></div>
                <div class='contents'>
                <p $translation>$line</p>
                <textarea name='$name' id='inputArea' form='fakeform' placeholder='$placeholder'></textarea>
                ".self::PickButton($button, $translation, $method)."
                </div></div>";
            }
        } catch (\Throwable $th) {
            die($th);
        }
        return true;
    }

}

class InputLine extends InputBox{

    var string $format;

    function __construct(string $lines = "", string $name = "", string $placeholder = "",$buttons = "close", string $method = "post",string $type = "type",string $includeTranslations = "") {
        parent::__construct($lines, $name, $placeholder, $buttons, $method, $includeTranslations);~
        $this->format = $type;
    }

    function Draw(){
        try {
            $l = $this->line;
            $i = $this->icon;
            $b = $this->button;
            $t = $this->translations;
            $n = $this->name;
            $p = $this->placeholder;
            $m = $this->method;
            $f = $this->format;

            if (isset($l)&&$l!=""&&isset($i)&&$i!="") {
                echo "<div id='popup' style='display:block;'>";
                echo self::defaultStyle;
                echo self::defaultScript;
                echo "<div class='blocker'></div>
                <div class='contents'>
                <div class='align_text_left'>
                <p $t>$l</p>
                <input type='$f' name='$n' id='inputField' form='fakeform' placeholder='$p'>
                </div>

                ".self::PickButton($b, $t, $m)."
                </div></div>";
            }else{
                return false;
            }

        } catch (\Throwable $th) {
            return $th;
        }
        return true;
    }

    static function StaticDraw($line = "", $name = "", $placeholder = "", $button = "ok", $method = "post", $type = "text", $translation = ""){
        try {
            if (isset($line)&&$line!="") {
                echo "<div id='popup' style='display:block;'>";
                echo self::defaultStyle;
                echo self::defaultScript;
                echo "<div class='blocker'></div>
                <div class='contents'>
                <div class='align_text_left'>
                <p $translation >$line</p>
                <input type='$type' name='$name' id='inputField' form='fakeform' placeholder='$placeholder'>
                </div>
                
                ".self::PickButton($button, $translation, $method)."
                </div></div>";
            }
        } catch (\Throwable $th) {
            die($th);
        }
        return true;
    }

}

class IframePopup extends Popup{
    
    var string $source;

    function __construct(string $src  ,$buttons = "close", string $includeTranslations = "",) {
        parent::__construct("", "", $buttons, $includeTranslations);
        $this->source = $src;
    }


    static function StaticDraw($src = "", $button = "close", $translation = "", $s = ""){
        try {
            echo "<div id='popup' style='display:block;'>";
            echo self::defaultStyle;
            echo self::defaultScript;
            echo "<div class='blocker'></div>
            <div class='contents'>
            <iframe src='$src' frameborder='0'></iframe>
            ".Popup::PickButton($button, $translation)."
            </div></div>
            <style>
        #popup .contents{
        width:70%;
        background: #fff;
        position: fixed;
        border-radius: 8px;
        text-align: center;
        padding: 30px 40px 10px 40px;
        font-weight: 600;
        font-size:large;
        top: 50%;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        z-index: 200;
        color:black;
        transform: translateY(-50%);
        text-justify: auto;
        height: 70%;
        display: flex;
        flex-direction: column;
    }
    </style>";

        } catch (\Throwable $th) {
            die($th);
        }
        return true;
    }


}

?>