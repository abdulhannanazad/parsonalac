// buttion confirmation
var gbtn = document.querySelectorAll("button");
gbtn.forEach(function (button) {
    button.addEventListener('click', function () {
        var gtext = button.textContent;

        if (gtext.trim().toLowerCase() === "update") {
            if (confirm("Are you sure about this operation?")) {
                document.querySelector(".msg").innerText = "action done";
            } else {
                msgHandle();
            }
        }

        if (gtext.trim().toLowerCase() === "delete") {
            if (confirm("Are you sure about this operation?")) {
                document.querySelector(".msg").innerText = "action done";
            } else {
                msgHandle();
            }
        }

    });
});
// callback function for buttion confirmation
function msgHandle(event) {
    event.preventDefault();
    document.querySelector('.msg').style.display = "block";
    document.querySelector(".msg").innerText = "action canceled";

    setTimeout(function () {
        document.querySelector('.msg').style.display = "none";
    }, 5000);
}

// 
document.addEventListener('DOMContentLoaded', function (even) {
    document.querySelectorAll("table th").forEach(function (th) {
        if (th.textContent.trim() === 'edit') {
            th.style.width = "50px";
        }
        if (th.textContent.trim() === 'date') {
            th.style.width = "90px";
        }
    });
});


$(document).ready(function () {
    $("#myInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    var btnList = document.querySelectorAll("button");
    btnList.forEach(function (btntxt) {

        var text = btntxt.textContent;

        if (text.trim().toLowerCase() === 'save') {
            btntxt.setAttribute('title', 'alt + s');
            btntxt.setAttribute('accesskey', 's');
        }

        if (text.trim().toLowerCase() === 'update') {
            btntxt.setAttribute('title', 'alt + u');
            btntxt.setAttribute('accesskey', 'u');
        }

        if (text.trim().toLowerCase() === 'delete') {
            btntxt.setAttribute('title', 'alt + w');
            btntxt.setAttribute('accesskey', 'w');
        }

        if (text.trim().toLowerCase() === 'search') {
            btntxt.setAttribute('title', 'alt + r');
            btntxt.setAttribute('accesskey', 'r');
        }

        if (text.trim().toLowerCase() === 'all') {
            btntxt.setAttribute('title', 'alt + q');
            btntxt.setAttribute('accesskey', 'q');
        }

        if (text.trim().toLowerCase() === 'yes') {
            btntxt.setAttribute('title', 'alt + y');
            btntxt.setAttribute('accesskey', 'y');
        }

        if (text.trim().toLowerCase() === 'no') {
            btntxt.setAttribute('title', 'alt + n');
            btntxt.setAttribute('accesskey', 'n');
        }

    });
});
