<?php
    require "protected/intranetlib.php";
    if (logged()==false) {
        setcookie('backpage',geturl(),time() + 10 * 60);
        header("location: login.php");
        return;
    }
    if (isset($_POST['submit'])) {
        submit();
    }
?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <script src="scripts/elementInjection.js"></script>
    <script src="scripts/pedidoform.js"></script>
    <link rel="stylesheet" href="stylesheet/form-pedidostyle.css?random=999999">
    <title data-tr-lb="cyber_title">CyberSculpt - Intranet</title>
</head>
<body>
    <div id="fullscreen">
        <?php include('protected/header.html');?>
        <div id="workspace">
            <div id="mainpanel">
            <div id="loadingDiv" style="display: none;">
                <img src="design/spin.gif" alt="Loading...">
            </div>
            <form id="form-pedido" method="post">
                <fieldset>
                    <legend data-tr="new_user_request_label" class="fieldtitle">Solicitação de novo Utilizador * </legend>
                    <table>
                        <tr>
                            <td>
                                <label data-tr-lb="active_company_label" for="act_empresa">Empresa em que fica afecto <span data-kp>&nbsp;&#129146;</span></label>
                            </td>
                            <td>
                                <input required  type="text" size="30" id="act_empresa" name="act_empresa">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label data-tr-lb="full_name_label" for="comp_nome">Nome completo <span data-kp>&nbsp;&#129146;</span></label>
                            </td>
                            <td>
                                <input required  type="text" size="30" id="comp_nome" name="comp_nome">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label data-tr-lb="name_lastname_label" for="alt_nome">Nome e apelido que o identificam <span data-kp>&nbsp;&#129146;</span></label>
                            </td>
                            <td>
                                <input required  type="text" size="30" id="alt_nome" name="alt_nome">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label data-tr-lb="act_department_label" for="act_departamento">Departamento a que fica afecto <span data-kp>&nbsp;&#129146;</span></label>
                            </td>
                            <td>
                                <input  required type="text" size="30" id="act_departamento" name="act_departamento">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label data-tr-lb="superior_label" for="superior">Superior hierárquico <span data-kp>&nbsp;&#129146;</span></label>
                            </td>
                            <td>
                                <input required type="text" size="30" id="superior" name="superior">
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset>
                    <legend data-tr="computer_label" class="fieldtitle">Computador</legend>
                    <table>
                        <tr>
                            <td>
                                <label data-tr-lb="pc_notes_label" for="pc_note">Notas sobre o PC a configurar <span data-kp>&nbsp;&#129146;</span></label>
                                <textarea style="resize: none;" id="pc_note" name="pc_note" cols="40" rows="5"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label data-tr-lb="pen_kanguru_label" for="penkanguru">Se é um portátil, precisa de acesso à internet quando esta no
                                    exterior?(Pen Kanguru) <span data-kp>&nbsp;&#129146;</span></label>
                            </td>
                            <td>
                                <input type="radio" id="penkanguru1" required name="penkanguru" value="1"> <label for="penkanguru1" data-tr-lb="yes">Sim</label>
                                <input type="radio" id="penkanguru0" required name="penkanguru" value="0"> <label for="penkanguru0" data-tr-lb="no">Não</label>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset>
                    <legend data-tr="email_label" class="fieldtitle">E-mail</legend>
                    <label data-tr-lb="make_email_label" for="cr_email">Criar conta de E-mail <span data-kp>&nbsp;&#129146;</span></label>
                    <input type="radio"  id="cr_email1" required name="cr_email" value="1">  <label for="cr_email1" data-tr-lb="yes">Sim</label>
                    <input type="radio"  id="cr_email0" required name="cr_email" value="0"> <label for="cr_email0" data-tr-lb="no">Não</label>
                    <br>
                    <label data-tr-lb="email_sugestion_label" for="sug_email">Sugestão de conta de E-mail <span data-kp>&nbsp;&#129146;</span></label>
                    <input disabled class="sug_email" type="text" name="sug_email" id="sug_email">
                    <label data-tr-lb="email_label" for="type_mail">E-mail <span data-kp>&nbsp;&#129146;</span> </label>
                    <input disabled class="type_mail" type="checkbox" name="type_mail[]" id="type_mail1" value="outlook"> <label for="type_mail1">Outlook</label>
                    <input disabled class="type_mail" type="checkbox" name="type_mail[]" id="type_mail0" value="thunderbird"> <label for="type_mail0">Thunderbird</label>
                    <input disabled type="checkbox" name="type_mail[]" value="" checked style="visibility: hidden;">
                </fieldset>
                <fieldset>
                    <legend data-tr="phone_label" class="fieldtitle">Telefone</legend>
                    <table>
                        <tr>
                            <td>
                                <label data-tr-lb="phone_label" for="type_tele">Telefone <span data-kp>&nbsp;&#129146;</span></label>
                                <input type="checkbox" name="type_tele[]" id="type_tele1" value="fixo"> <label data-tr-lb="fixed_type_label" for="type_tele1">Fixo</label>
                                <input type="checkbox" name="type_tele[]" id="type_tele2" value="dect"> <label data-tr-lb="portable_type_label" for="type_tele2">Dect(Portátil) </label>
                                <input type="checkbox" name="type_tele[]" id="type_tele3" value="exte"> <label data-tr-lb="exterior_type_label" for="type_tele3">Faz chamadas para o exterior</label>
                                <input type="checkbox" name="type_tele[]" value="" checked style="visibility: hidden;">
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label data-tr-lb="card_label" for="card_tele">Cartão Telemóvel <span data-kp>&nbsp;&#129146;</span></label>
                                <input type="radio" id="card_tele1" required name="card_tele" value="1"><label for="card_tele1" data-tr-lb="yes">Sim</label>
                                <input type="radio" id="card_tele0" required name="card_tele" value="0"><label for="card_tele0" data-tr-lb="no">Não</label>
                            </td>
                            <td>
                                <label data-tr-lb="card_plan_label" for="dados_tele">Plano de dados igual a <span data-kp>&nbsp;&#129146;</span></label>
                                <input disabled type="text" class="dados_tele" name="dados_tele" id="dados_tele">
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset>
                    <legend data-tr="access_other_label" class="fieldtitle">Acessos/Outros</legend>
                    <label data-tr-lb="sap_user_label" for="cr_sap">Criar utilizador sap* <span data-kp>&nbsp;&#129146;</span></label>
                    <input type="radio" id="cr_sap1" required name="cr_sap" value="1"><label for="cr_sap1" data-tr-lb="yes">Sim</label>
                    <input type="radio" id="cr_sap0" required name="cr_sap" value="0"><label for="cr_sap0" data-tr-lb="no">Não</label>
                    <br>
                    <label data-tr-lb="easy_schedule_label" for="easy_schedule">Utiliza Easy Schedule (tratamento de tempos) <span data-kp>&nbsp;&#129146;</span></label>
                    <input type="radio" id="easy_schedule1" required name="easy_schedule" value="1"><label for="easy_schedule1" data-tr-lb="yes">Sim</label>
                    <input type="radio" id="easy_schedule0" required name="easy_schedule" value="0"><label for="easy_schedule0" data-tr-lb="no">Não</label>
                    <hr>
                    <table>
                        <tr>
                            <td>
                                <label data-tr-lb="file_server_label" for="server_user">Utilizador no <span>"Servidor de ficheiros"</span> com acessoequivalente ao utilizador <span data-kp>&nbsp;&#129146;</span></label>
                            </td>
                            <td>
                                <input type="text" name="server_user" id="server_user">
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <label data-tr-lb="vpn_label" for="vpn">Precisa de <span>VPN</span> (acesso a partir do exterior)<span data-kp>&nbsp;&#129146;</span></label>
                    <input type="radio" id="vpn1" required name="vpn" value="1"><Label for="vpn1" data-tr-lb="yes">Sim</Label>
                    <input type="radio" id="vpn0" required name="vpn" value="0"><Label for="vpn0" data-tr-lb="no">Não</Label>
                    <table>
                        <tr>
                            <td>
                                <label data-tr-lb="office_lang_label" for="office_lang">Office <span data-kp>&nbsp;&#129146;</span></label>
                                <input type="checkbox" id="office_lang1" name="office_lang[]" value="port"><label for="office_lang1">Port</label>
                                <input type="checkbox" id="office_lang2" name="office_lang[]" value="engl"><label for="office_lang2">Engl</label>
                                <input type="checkbox" name="office_lang[]" value="" checked style="visibility: hidden;">
                            </td>
                            <td style="padding-left: 15px;">
                                <label data-tr-lb="other_label" for="alt_office_lang">Outro <span data-kp>&nbsp;&#129146;</span></label>
                                <input type="text" name="alt_office_lang" id="alt_office_lang">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="services1" name="services[]" value="intouch"> <label data-tr-lb="intouch_label" for="services1">INTOUCH</label>
                                <input type="checkbox" id="services2" name="services[]" value="pda"><label data-tr-lb="pda_label" for="services2">PDA</label>
                                <input type="checkbox" id="services3" name="services[]" value="sage"><label data-tr-lb="sage_label" for="services3">SAGE</label>
                                <input type="checkbox" id="services4" name="services[]" value="" checked style="visibility: hidden;">
                            </td>
                            <td style="padding-left: 15px;">
                                <label data-tr-lb="others_label" for="alt_services">Outros <span data-kp>&nbsp;&#129146;</span></label>
                                <input type="text" name="alt_services" id="alt_services">
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <table>
                        <tr>
                            <td>
                                <label data-tr-lb="cyber_card_label" for="cyber_card">Utiliza cartão para acessos CyberSculpt <span data-kp>&nbsp;&#129146;</span></label>
                                <input type="radio"  id="cyber_card1" name="cyber_card" required value="1">
                                <label for="cyber_card1" data-tr-lb="yes">Sim</label>
                                <input type="radio"  id="cyber_card0" name="cyber_card" required value="0">
                                <label for="cyber_card0" data-tr-lb="no">Não</label>
                            </td>
                            <td style="padding-left: 50px;">
                                <label data-tr-lb="access_profile_label" for="cyber_profile">Perfil de acesso <span data-kp>&nbsp;&#129146;</span></label>
                                <input disabled class="cyber_profile" type="text" name="cyber_profile" id="cyber_profile">
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset>
                    <legend data-tr="obs_label" class="fieldtitle">Observações</legend>
                    <textarea name="obs" cols="40" rows="5" style="resize: none;" placeholder="obs..."></textarea>
                </fieldset>
                <div id="fieldfoot">
                    <input type="submit" id="submitbt" name="submit" value="Enviar">
                </div>
                <input id="refresh" type="refresh" style="display:none;">
            </form> 
            </div>
        </div>
    </div>
</body>

</html>