if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
function changeform(){
    const showbt1 = document.querySelector('#submitbt1');
    const showbt2 = document.querySelector('#submitbt2');
    const holder = document.querySelectorAll('.fielset');
    holder.forEach(hold => {
        if(hold.getAttribute('disabled') == null){
            hold.setAttribute('disabled',true);
        }else{
            hold.removeAttribute('disabled');
        }
    });
    if(showbt1.style.display == "none"){
        showbt1.style.display = "block";
    }else{
        showbt1.style.display = "none";
    }
    if(showbt2.style.display == "none"){
        showbt2.style.display = "block";
    }else{
        showbt2.style.display = "none";
    }
}
function saveform() {
    document.querySelector('#submitbt').click();
}
function btclick(linha) {
    document.querySelector(linha).click();
}
function clpopup() {
    var au =document.querySelector('#popup');
    au.style.display = 'none';
}
function callPopupBtn(name, value) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.response);
            var existingref = document.querySelector('#ref');
            if (existingref) {
                existingref.remove(); 
            }
            var ref = document.createElement('div'); 
            ref.id = 'ref';
            ref.innerHTML = this.response;
            document.body.appendChild(ref);
            loadLanguage('popup');
        }
    };
    console.log(window.location.href+"&"+name+"="+value);
    xhttp.open("GET", window.location.href+"&"+name+"="+value, true);
    xhttp.send();
}
