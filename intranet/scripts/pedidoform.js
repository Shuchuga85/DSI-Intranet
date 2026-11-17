if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

function disable(clas,d){
    var holder = document.querySelectorAll('input.'+clas);
    holder.forEach(hold => {
        if(d==0){
            hold.setAttribute('disabled',true);
        }else{
            hold.removeAttribute('disabled');
        }
    });
}