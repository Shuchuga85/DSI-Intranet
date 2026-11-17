if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
function submitupdate(){
    document.querySelector('#submitbt').click();
}

function changeform(){
    const showbt = document.querySelector('#submitbt');
    const holder = document.querySelectorAll('.fielset');
    holder.forEach(hold => {
        if(hold.getAttribute('disabled') == null){
            hold.setAttribute('disabled',true);
        }else{
            hold.removeAttribute('disabled');
        }
    });
    if(showbt.style.display == "none"){
        showbt.style.display = "block";
    }else{
        showbt.style.display = "none";
    }
}
function clpopup() {
    var au =document.querySelector('#popup');
    au.style.display = 'none';
    window.location.reload();
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

