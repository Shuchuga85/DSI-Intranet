function changeScreen(id) {
    $('.screenpage').each(function () {
        $(this).css({
            display: 'none'
        });
    });
    $('#' + id).css('display', 'revert');
    window.location.hash = id;
}

function preloadScreen() {
    var hash = window.location.hash.slice(1); // Get the hash without the '#'
    if (hash) {
        changeScreen(hash);
    }
}

function drawNtf(id) {
    var ntf = document.getElementById(id);

    var title = ntf.querySelector('.ntfTitle').textContent;
    var body = ntf.querySelector('.ntfBody').innerHTML;
    var catg = ntf.querySelector('.ntfCatg').textContent;
    var img = ntf.querySelector('.ntfImg').src;
    var date = ntf.querySelector('.ntfDate').textContent;


    document.getElementById('msgTitle').textContent = title;
    document.getElementById('msgBody').innerHTML = body;
    document.getElementById('msgCatg').textContent = catg;
    document.getElementById('msgImg').src = img;
    document.getElementById('msgDate').textContent = date;

}

function pickCatg(value) {
    var ntfs = document.querySelectorAll('.ntfBox');
    ntfs.forEach(element => {
        var catg = element.querySelector('.ntfCatg').textContent;
        if (catg !== value) {
            element.style.display = "none";
        } else {
            element.style.display = "flex";
        }
    });
}

function unpickCatg() {
    var ntfs = document.querySelectorAll('.ntfBox');
    ntfs.forEach(element => {
        var catg = element.querySelector('.ntfCatg').textContent;
        element.style.display = "flex";
    });
}

function rqtdelete(e, id) {
    e.preventDefault();
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
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
    xhttp.open("GET", window.location.pathname + "?delete=" + id, true);
    xhttp.send();
}


window.onload = function () {
    preloadScreen();
};


function callPopupBtn(name, value) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
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
    xhttp.open("GET", window.location.pathname+"?"+name+"="+value, true);
    xhttp.send();
}


function callPhpFun(name, value) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.response);
        }
    };
    xhttp.open("GET", window.location.pathname+"?"+name+"="+value, true);
    xhttp.send();
}