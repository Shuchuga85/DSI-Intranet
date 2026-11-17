function loadHead() {
    fetch('scripts/proxy.php?action=loadHead')
        .then(response => response.text())
        .then(data => {
            document.head.innerHTML += data;
        });
}
loadHead();