if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}

function val(){
    if(form1.user.value == ""){
    return false;
    }
    if(form1.pass.value == ""){
    return false;
    }
}

function clpopup() {
    var au =document.querySelector('#popup');
    au.style.display = 'none';
}

function resetinput() {
    document.getElementById("user").classList.remove("failed");
    document.getElementById("pass").classList.remove("failed");
}