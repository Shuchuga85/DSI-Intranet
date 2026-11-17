if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
function openform(id) {
    var urlParams = new URLSearchParams(window.location.search);
    var deurl = window.location.href.split('/')[window.location.href.split('/').length-1]; 
    var url = window.location.href.replace(deurl, 'form-reader')+'?'+urlParams;
    var separator = (url.indexOf('?') !== -1) ? '&' : '?';
    window.location.href = url + separator + 'id_pedido=' + id;
}

function toggleFlagged() {
    const rows = document.querySelectorAll('tr[flagged="false"]');
    const btn = document.querySelector('#flagfilter');
    btn.classList.toggle('active');
    rows.forEach(row => {
        row.classList.toggle('hidden');
    });
}

/*
function searchTable(search) {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById(search);
    filter = input.value.toUpperCase();
    table = document.getElementById("rowtb");
    tr = table.getElementsByTagName("tr");

    //primeira coluna
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
                //segunda 
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                        //terceira
                        td = tr[i].getElementsByTagName("td")[2];
                        if (td) {
                            txtValue = td.textContent || td.innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                            } else {
                                tr[i].style.display = "none";
                            }
                        }
                    }
                }
            }
        }
    }
}
function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("rowtb");
    switching = true;

    dir = "asc";

    while (switching) {

        switching = false;
        rows = table.rows;

        for (i = 1; i < (rows.length - 1); i++) {

            shouldSwitch = false;

            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {

                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {

                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}*/