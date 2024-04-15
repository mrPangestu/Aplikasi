// FUNGSI UNTUK MENAMPILKAN NOTIF
    document.addEventListener("DOMContentLoaded", function () {
        var bellIcon = document.getElementById("bellIcon");
        var popupContainer = document.getElementById("popupContainer");

        bellIcon.addEventListener("click", function (event) {
            event.preventDefault(); // Menghentikan navigasi default
            popupContainer.classList.toggle("active");
        });

        var closeNotif = document.querySelector(".close_notif");

        closeNotif.addEventListener("click", function () {
            popupContainer.classList.remove("active");
        });
    });
// ============================== //


// FUNGSI UNTUK MENAMPILKAN TANGGAL

    // Mendapatkan tanggal saat ini
    var currentDate = new Date();

    // Mendapatkan komponen tanggal, bulan, dan tahun
    var day = currentDate.getDate();
    var month = currentDate.getMonth() + 1; // Penambahan 1 karena bulan dimulai dari 0
    var year = currentDate.getFullYear();

    // Menampilkan tanggal dalam format "DD/MM/YYYY"
    var formattedDate = '|' + day + '/' + month + '/' + year + '|';

    // Menyisipkan tanggal ke dalam elemen HTML dengan id "tanggalSaatIni"
    document.getElementById('tanggalSaatIni').innerText = formattedDate;
// ============================== //


// FUNGSI UNTUK MENAMPILKAN JAM

    function updateClock() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();

    // Formatting to ensure two digits
    hours = (hours < 10) ? '0' + hours : hours;
    minutes = (minutes < 10) ? '0' + minutes : minutes;
    seconds = (seconds < 10) ? '0' + seconds : seconds;

    var timeString = '|' + hours + ':' + minutes + ':' + seconds + '|' ;

    // Update the clock element
    document.getElementById('clock').innerHTML = timeString;
    }

    // Update the clock every second
    setInterval(updateClock, 1000);

    // Initial update when the page loads
    updateClock();
// ============================== //