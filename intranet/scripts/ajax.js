var isRefreshing = false;
function refreshSession() {
    if (isRefreshing) {
        return;
    }
    var xhttp = new XMLHttpRequest();
    var url = "scripts/proxy.php?action=refreshSession";
    xhttp.open("GET", url, true);
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            isRefreshing = false; 
        }
    };
    xhttp.send();
}
refreshSession();
setInterval(refreshSession, 5 * 60 * 1000); 
