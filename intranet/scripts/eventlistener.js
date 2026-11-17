
$(document).ready(function() {
    $('#languageSelect').change(function() {
        var lang = $(this).val();
        //var url = window.location.href;
        var url = new URL(window.location.href);
        var hash = window.location.hash;
        var base = window.location.pathname;
         // Set or update the lang parameter
    url.searchParams.set('lang', lang);

    // Construct the new URL
    var newUrl = url.origin + url.pathname + url.search + hash;

    // Redirect to the new URL
    window.location.href = newUrl;
        /*var langParam = 'lang=' + lang;
        if (url.indexOf('lang=') !== -1) {
            var regex = /lang=[^&]*//*;
            var newUrl = url.replace(regex, langParam);
            window.location.href = newUrl;
        } else {
            var separator = (url.indexOf('?') !== -1) ? '&' : '?';
            window.location.href = base + separator + langParam + hash;
        }*/
    });
    $('#close').click(function(){
        clpopup();
    });
    $('.close').click(function(){
        clpopup();
    });
    $('#form-pedido').on('submit', function(event) {
        var $form = $(this);
        $("#loadingDiv").css("display", "");
        $('#submitButton').prop('disabled', true);
        return true;
    });
    $('#user').change(function(){
        resetinput();
    });
    $('#pass').change(function(){
        resetinput();
    });
    $('.lrow').click(function(){
        openform($(this).data('value'));
    });
    $('.updatebtn').click(function(){
        submitupdate();
    });
    $('.changebtn').click(function(){
        changeform();
    });
    $('#savebtn1').click(function(){
        btclick('#submitbt1');
    });
    $('#savebtn2').click(function(){
        btclick('#submitbt2');
    });
    $('#checkall').click(function(){
        mark();
    });
    $('#cr_email1').click(function(){
        disable('type_mail',1);
        disable('sug_email',1);
    });
    $('#cr_email0').click(function(){
        disable('type_mail',0);
        disable('sug_email',0);
    });
    $('#card_tele1').click(function(){
        disable('dados_tele',1);
    });
    $('#card_tele0').click(function(){
        disable('dados_tele',0);
    });
    $('#cyber_card1').click(function(){
        disable('cyber_profile',1);
    });
    $('#cyber_card0').click(function(){
        disable('cyber_profile',0);
    });
    $('.refreshbt').click(function(){
        $(this).closest('form').submit();
    });
    $('#filterstat').change(function(){
        $(this).closest('form').submit();
    });
    $('#search1').keyup(function(){
        searchTable('search1');
    });
    $('#search').keyup(function(){
        searchTable('search');
    });
    $('.rhead').click(function(){
        sortTabling($(this).data('value'));
    });
    $('a[dynamic]').each(function(){
        var urlParams = new URLSearchParams(window.location.search);
        var currentLang = urlParams.get('lang');
        var url = currentLang ? '?lang=' + currentLang : '';
        
        var href = $(this).attr('href');
        $(this).attr('href', href + url);
    })
    $('.screenbtn').click(function(){
        var id = $(this).attr("screen-id");
        changeScreen(id);
    });
    $('.ntfBox').click(function(){
        var elmt = this.id;
        drawNtf(elmt);
    });
    $('.catgbtn').click(function(){
        var on = $(this).attr('catg-on');
        $('.catgbtn').each(function () {
            $(this).attr('catg-on','0');
        });
        if (on == true || on == '1') {
            unpickCatg();
            $(this).attr('catg-on', '0');
        }else{
            var catg = $(this).attr('catg-value');
            pickCatg(catg);
            $(this).attr('catg-on', '1');
        }
    });
    $('.btn-rqt-delete').click(function (e) { 
        var id = $(this).attr('rqt-id');
        rqtdelete(e, id);
    });
    $('.btn-rqt-edit').click(function (e) { 
        e.preventDefault();
        var id = $(this).attr('value');
        var name = $(this).attr('name');
        callPopupBtn(name, id);
    });
    $("#passbtn").click(function(){
        $("#email_sender_pass").prop("disabled", function(i, val) {
          return !val;
        });
    });
    function clpopup() {
        var pop = document.querySelector('#popup');
        pop.remove();
    }
    $('.hidetoolbar').click(function(){
        $(this).toggleClass('closed');
        var toolbar = $('#tool-bar');
        toolbar.toggleClass('closed');
        var buttons = toolbar.find('.toolbtn');
        buttons.toggleClass('closed');
        if (!toolbar.hasClass('closed')) {
            buttons.addClass('open');
            toolbar.addClass('open');
            $(this).addClass('open');
        }else{
            buttons.removeClass('open');
            toolbar.removeClass('open');
            $(this).removeClass('open');
        }

    });
    let consumable = true
    $("#workspace").scroll(function() {
        var scrollPosition = $("#workspace").scrollTop();
        var triggerOffsetplus = 100;
        var triggerOffsetminus = 90;
        var closed = $('#tool-bar').hasClass('closed');
        
        if (scrollPosition >=  triggerOffsetplus) {
            if(!closed){
                $(".hidetoolbar").click();
            }
        }else{
            if(closed){
                $(".hidetoolbar").click();
            }
        }
    });
    $('#sendemail').click(function (e) { 
        e.preventDefault();
        var value = $(this).attr('value');
        var name = $(this).attr('name');
        callPopupBtn( name,value);
    });
    $('#predelete').click(function (e) { 
        e.preventDefault();
        var value = $(this).attr('value');
        var name = $(this).attr('name');
        callPopupBtn( name, value);
    });
    $('.passchangebtn').click(function (e) { 
        e.preventDefault();
        var value = $(this).attr('value');
        var name = $(this).attr('name');
        callPopupBtn( name, value);
    });
    $(document).on('click', '#popup_makemessage', function() {
        var value = $(this).attr('value');
        var name = $(this).attr('name');
        callPopupBtn(name,value);
    });
    $(document).on('click', '#popup_subdelete', function() {
        var value = $(this).attr('value');
        var name = $(this).attr('name');
        callPopupBtn(name,value);
    });
    $(document).on('click', '.popupbtn', function(e) {
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
        clpopup();
    });
    $('.toggle-password').on('click', function() {
        var target = $(this).attr('target');
        var passwordField = $("#"+target);
        var passwordFieldType = passwordField.attr('type');
        
        if(passwordFieldType === 'password') {
          passwordField.attr('type', 'text');
        } else {
          passwordField.attr('type', 'password');
        }
    });
    $('#filterstat1').change(function(){
        $(this).closest('form').submit();
    });
    $('#filterstat2').change(function(){
        $(this).closest('form').submit();
    });
    $('#notify').change(function (){ 
        var isChecked = $(this).prop('checked');
        var value = isChecked ? 1 : 0;
        var name = $(this).attr('name');
        callPhpFun(name,value);
    });
    $('#remember_me').change(function (){ 
        var isChecked = $(this).prop('checked');
        var value = isChecked ? 1 : 0;
        var name = $(this).attr('name');
        callPhpFun(name,value);
    });
    $('#langselects').change(function (){ 
        var value = $(this).val();
        var name = $(this).attr('name');
        callPhpFun(name,value);
        window.location.reload();
    });
    $('#flagfilter').click(function (){
        toggleFlagged();
    });
    $('#refreshbt').click(function (){
        window.location.href = window.location.pathname;
    });
});


