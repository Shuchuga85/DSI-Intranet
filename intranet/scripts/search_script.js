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
function sortTable_(n) { // disabled for performance issues remove: _
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
}
let sortDirection = {}; // Object to track sort direction for each column

function mergeSort(arr, n, direction) {
    if (arr.length <= 1) {
        return arr;
    }

    const mid = Math.floor(arr.length / 2);
    const left = arr.slice(0, mid);
    const right = arr.slice(mid);

    return merge(mergeSort(left, n, direction), mergeSort(right, n, direction), n, direction);
}

function merge(left, right, n, direction) {
    let result = [];
    let leftIndex = 0;
    let rightIndex = 0;

    while (leftIndex < left.length && rightIndex < right.length) {
        const leftValue = left[leftIndex].getElementsByTagName("TD")[n].innerHTML.toLowerCase();
        const rightValue = right[rightIndex].getElementsByTagName("TD")[n].innerHTML.toLowerCase();

        if ((direction === "asc" && leftValue < rightValue) || (direction === "desc" && leftValue > rightValue)) {
            result.push(left[leftIndex]);
            leftIndex++;
        } else {
            result.push(right[rightIndex]);
            rightIndex++;
        }
    }

    return result.concat(left.slice(leftIndex)).concat(right.slice(rightIndex));
}

function sortTabling(n) {
    const table = document.getElementById("rowtb");
    const rows = Array.from(table.rows).slice(1); // Exclude the header row

    // Toggle sort direction
    sortDirection[n] = sortDirection[n] === "asc" ? "desc" : "asc";

    const sortedRows = mergeSort(rows, n, sortDirection[n]);

    // Clear table
    while (table.rows.length > 1) {
        table.deleteRow(1);
    }

    // Reinsert sorted rows into the table
    sortedRows.forEach(row => {
        table.appendChild(row);
    });
}