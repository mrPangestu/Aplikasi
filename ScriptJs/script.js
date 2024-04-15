// FUNGSI FILTER BERDASARKAN KATEGORI
    function filterData(kategori) {
        var cards = document.getElementsByClassName('card');

        // Tampilkan semua kartu
        for (var i = 0; i < cards.length; i++) {
            cards[i].style.display = 'block';
        }

        // Semua kartu yang bukan termasuk kategori yang dipilih dihilangkan
        if (kategori !== 'Semua') {
            for (var i = 0; i < cards.length; i++) {
                var cardKategori = cards[i].getElementsByClassName('item2')[0].innerText;
                if (cardKategori !== kategori) {
                    cards[i].style.display = 'none';
                }
            }
        }
    }
// ============================== //


// FUNGSI BUKA POPUP CREATE
    // Fungsi untuk membuka popup
        function openPopup() {
            document.getElementById('myPopup').style.display = 'block';
            document.getElementById("overlay1").style.display = "block";
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup popup
        function closePopup() {
            document.getElementById('myPopup').style.display = 'none';
            document.getElementById("overlay1").style.display = "none";
            document.body.style.overflow = 'auto';
        }
// ============================== //

        
// FUNGSI BUKA FORM POPUP UBAH
    // Fungsi untuk membuka popup
        function openPopupUbah(id_detail_tugas) {

            var id_tugas_card = document.getElementById('id_tugas_card_' + id_detail_tugas).value;

            // Mengisi nilai id_tugas pada popup1
            var ubahTugasPopup = document.getElementById('id_ubah_tugas_popup');
            if (ubahTugasPopup) {
                ubahTugasPopup.value = id_tugas_card;
            }

            document.getElementById('myPopupUbah').style.display = 'block';
            document.getElementById("overlay1").style.display = "block";
            document.body.style.overflow = 'hidden';
        }

    // Fungsi untuk menutup popup
        function closePopupUbah() {

            document.getElementById('myPopupUbah').style.display = 'none';
            document.getElementById("overlay1").style.display = "none";
            document.body.style.overflow = 'auto';
        }
// ============================== //


// FUNGSI BUKA POPUP KATEGORI
    // Fungsi untuk membuka popup
    function openPopupKatergori() {
        document.getElementById("my-tambah-kategori").style.display = "block";
        document.getElementById("overlay1").style.display = "block";
        document.body.style.overflow = 'hidden';
    }

    // Fungsi untuk menutup popup
        function closePopupKategori() {
            document.getElementById("my-tambah-kategori").style.display = "none";
            document.getElementById("overlay1").style.display = "none";
            document.body.style.overflow = 'auto';
        }
// ============================== //


// FUNGSI BUKA POPUP DETAIL TUGAS
    // Fungsi untuk membuka popup
    function openPopup1(id_detail_tugas) {
        // Mendapatkan nilai id_tugas dari card
        var id_tugas_card = document.getElementById('id_tugas_card_' + id_detail_tugas).value;
    
        // Mengisi nilai id_tugas pada popup1
        var inputIdTugasPopup1 = document.getElementById('id_tugas_popup1');
        if (inputIdTugasPopup1) {
            inputIdTugasPopup1.value = id_tugas_card;
        }
    
        var inputIdDetailTugas = document.getElementById('id_detail_tugas_popup');
        if (inputIdDetailTugas) {
            inputIdDetailTugas.value = id_tugas_card;
        }
    
        // Selanjutnya, lakukan logika atau tindakan lainnya untuk membuka popup1
        document.getElementById("popup-container").style.display = "block";
        document.getElementById("overlay1").style.display = "block";
        document.body.style.overflow = 'hidden';
    
        var id_tugas_popup1 = document.getElementById('id_tugas_popup1').value;
    
        // Mengirim data ke PHP menggunakan AJAX
        $.ajax({
            type: 'POST',
            url: 'function/functions_detail-tugas.php', // Ganti 'process.php' dengan nama file PHP yang akan menangani data
            data: { id_detail_tugas: id_tugas_popup1 },
            success: function(response) {
                // Handle respons dari server jika diperlukan
                console.log('Data terkirim: ' + response);
                document.getElementById('popup-content-result').innerHTML = response;
            },
            error: function(error) {
                // Handle kesalahan jika diperlukan
                console.error('Error:', error);
            }
        });
        $.ajax({
            type: 'POST',
            url: 'function/functions_judul-tugas.php', // Ganti 'process.php' dengan nama file PHP yang akan menangani data
            data: { id_detail_tugas: id_tugas_popup1 },
            success: function(response) {
                // Handle respons dari server jika diperlukan
                console.log('Data terkirim: ' + response);
                document.getElementById('popup-content-judulTugas-result').innerHTML = response;
            },
            error: function(error) {
                // Handle kesalahan jika diperlukan
                console.error('Error:', error);
            }
        });
    
    }

    // Fungsi untuk menutup popup
        function closePopup1() {
            document.getElementById("popup-container").style.display = "none";
            document.getElementById("overlay1").style.display = "none";
            document.body.style.overflow = 'auto';
        }
// ============================== //


// FUNGSI BUKA POPUP INPUT DETAIL TUGAS
    // Fungsi untuk membuka popup
    function openPopupInputDetail() {
        document.getElementById('myPopup-input-detail').style.display = 'block';
    }

    // Fungsi untuk menutup popup
    function closePopupInputDetail() {
        document.getElementById('myPopup-input-detail').style.display = 'none';
    }
// ============================== //


// FUNGSI BUKA POPUP INPUT UBAH DETAIL TUGAS
        // Fungsi untuk membuka popup
        function openPopupEditDetail(id) {
            document.getElementById('myPopup-edit-detail').style.display = 'block';

            // Populate the form with the current task name
            var currentTaskName = document.getElementById('tugas_' + id).innerText;
            document.getElementById('id_edit_detail_tugas_popup').value = id;
            document.getElementById('editText').value = currentTaskName;
        }

        // Fungsi untuk menutup popup
        function closePopupEditDetail() {
            document.getElementById('myPopup-edit-detail').style.display = 'none';
        }
// ============================== //


// FUNGSI SUBMIT INPUT TAMBAH DETAIL TUGAS
function submitFormInputDetail() {
    // Mendapatkan nilai input
    var id_tugas_popup1 = document.getElementById('id_detail_tugas_popup').value;
    var inputDetailTugas = document.getElementById('inputText').value;

    // Mengirim data ke PHP menggunakan AJAX
    $.ajax({
        type: 'POST',
        url: 'function/functions_input-detail-tugas.php', // Sesuaikan dengan nama file PHP yang akan menangani data
        data: { id_tugas: id_tugas_popup1, input_detail_tugas: inputDetailTugas },
        success: function(response) {
            // Handle respons dari server jika diperlukan
            console.log('Data terkirim: ' + response);

            // Contoh: Menampilkan pesan hasil operasi di popup-content-result
            document.getElementById('popup-content-result').innerHTML = response;
        },
        error: function(error) {
            // Handle kesalahan jika diperlukan
            console.error('Error:', error);
        }
        
    });



}
// ============================== //


// FUNGSI MENGHAPUS TEKS INPUT PADA INPUT DETAIL TUGAS
    function resetInputField() {
        // Reset the input field to an empty value
        document.getElementById('editText').value = '';
        document.getElementById('inputText').value = '';
        // You can add more lines to reset other input fields if needed
    }
// ============================== //


// FUNGSI TANDAI DETAIL TUGAS
    function tandaiTugas(id) {
        var baris = document.getElementById('baris_' + id);
        var namaTugas = document.getElementById('tugas_' + id);

        // Mengecek apakah nama tugas sudah ditandai (dicoret)
        var isDicoret = baris.classList.contains('dicoret');

        // Membuat objek AJAX
        var xmlhttp = new XMLHttpRequest();

        // Menyiapkan fungsi yang akan dipanggil ketika AJAX selesai
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // Memperbarui tampilan jika AJAX berhasil
                if (xmlhttp.responseText.trim() === 'success') {
                    // Toggles tanda coret dan kelas 'dicoret'
                    if (isDicoret) {
                        namaTugas.style.textDecoration = 'none';
                        baris.classList.remove('dicoret');
                    } else {
                        namaTugas.style.textDecoration = 'line-through';
                        baris.classList.add('dicoret');
                        namaTugas.style.textDecorationThickness = '3px';
                    }
                }
            }
        };

        // Mengirimkan permintaan AJAX ke server
        xmlhttp.open("POST", "function/functions_tandai-detail-tugas.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id_detail_tugas=" + id + "&is_dicoret=" + (isDicoret ? '0' : '1'));
    }
// ============================== //


// FUNGSI SUBMIT UBAH DETAIL TUGAS
    function submitUpdateForm() {
        var id = document.getElementById('id_edit_detail_tugas_popup').value;
        var newTaskName = document.getElementById('editText').value;

        // Make an AJAX request to update the task name in the database
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // Update the task name on the page if the update was successful
                if (xmlhttp.responseText.trim() === 'success') {
                    document.getElementById('tugas_' + id).innerText = newTaskName;
                    closeUpdateForm();
                }
            }
        };

        xmlhttp.open("POST", "function/functions_edit-detail-tugas.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id_detail_tugas=" + id + "&new_task_name=" + encodeURIComponent(newTaskName));

        return false; // Prevent the form from submitting in the traditional way
    }
// ============================== //


// FUNGSI HAPUS DETAIL TUGAS
    function hapusTugas(id) {
        // Confirm with the user before deleting the task
        var confirmation = confirm("Apa kamu yakin mau menghapus tugas ini?");

        if (confirmation) {
            // Make an AJAX request to delete the task from the database
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    // Remove the task from the page if the deletion was successful
                    if (xmlhttp.responseText.trim() === 'success') {
                        var baris = document.getElementById('baris_' + id);
                        baris.remove();
                    }
                }
            };

            xmlhttp.open("POST", "function/functions_detele-detail-tugas.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("id_detail_tugas=" + id);
        }
    }
// ============================== //




function openPopupskala() {
    document.getElementById("popup-container-skala").style.display = "block";
    document.getElementById("overlay1").style.display = "none";
    document.body.style.overflow = 'auto';
}

function closePopupskala() {
        document.getElementById("popup-container-skala").style.display = "none";
        document.getElementById("overlay1").style.display = "none";
        document.body.style.overflow = 'auto';
}
function openPopupskala2() {
    document.getElementById("popup-container-skala2").style.display = "block";
    document.getElementById("overlay1").style.display = "none";
    document.body.style.overflow = 'auto';
}

function closePopupskala2() {
        document.getElementById("popup-container-skala2").style.display = "none";
        document.getElementById("overlay1").style.display = "none";
        document.body.style.overflow = 'auto';
}

function openPopuplp() {
    document.getElementById("popup-container-skala2").style.display = "block";
    document.getElementById("popup-container-skala2").style.display = "block";
    document.getElementById("popup-container-skala2").style.display = "block";
    document.getElementById("overlay1").style.display = "none";
    document.body.style.overflow = 'auto';
}

function closePopuplp() {
        document.getElementById("popup-container-skala2").style.display = "none";
        document.getElementById("popup-container-skala2").style.display = "none";
        document.getElementById("popup-container-skala2").style.display = "none";
        document.getElementById("overlay1").style.display = "none";
        document.body.style.overflow = 'auto';
}



