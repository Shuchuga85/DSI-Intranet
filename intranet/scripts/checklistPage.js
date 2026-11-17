if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
var op = true;

document.addEventListener('DOMContentLoaded', function () {

    var checkboxes = document.querySelectorAll('.checkbox');

    checkboxes.forEach(function (checkbox) {
        if (checkbox.checked) {
            op = false;
        }
    });

   
}); 
function mark() {
    holder = document.querySelectorAll('.checkbox');
    holder.forEach((hold) => {
        hold.checked = op;
    });
    op = !op;
}
function clpopup() {
    var au = document.querySelector('#popup');
    au.style.display = 'none';
}