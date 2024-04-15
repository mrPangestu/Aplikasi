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


// FUNGSI BUKA POPUP DETAIL TUGAS
// Fungsi untuk membuka popup
    function openPopup1(id_detail_tugas) {
        // Mendapatkan nilai id_tugas dari card
        var id_tugas_card = document.getElementById('id_tugas_card_' + id_detail_tugas).value;

        // Mengisi nilai id_tugas pada popup1

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

        // FUNGSI MENAMPILKAN DATA TANPA REFRESH HALAMAN

            // Mengirim data ke PHP menggunakan AJAX
            $.ajax({
                type: 'POST',
                url: 'function/functions_detail-tugas.php', // Ganti 'process.php' dengan nama file PHP yang akan menangani data
                data: { id_detail_tugas_tim: id_tugas_popup1 },
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

            // Mengirim data ke PHP menggunakan AJAX
            $.ajax({
                type: 'POST',
                url: 'function/functions_judul-tugas.php', // Ganti 'process.php' dengan nama file PHP yang akan menangani data
                data: { id_detail_tugas_tim: id_tugas_popup1 },
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


// FUNGSI BUKA MENU TIM
// Fungsi untuk memuat data Tim secara asinkron
    function openPopupTim(id_detail_tugas) {
        // Mendapatkan nilai id_tugas dari card
        var id_tugas_card = document.getElementById('id_tugas_card_' + id_detail_tugas).value;

        // Mengisi nilai id_tugas pada popup1
        var inputIdDetailTugas = document.getElementById('id_tugas_popup_tim');
        if (inputIdDetailTugas) {
            inputIdDetailTugas.value = id_tugas_card;
        }

        // Selanjutnya, lakukan logika atau tindakan lainnya untuk membuka popup1
        document.getElementById("popup-tim").style.display = "flex";
        document.getElementById("overlay1").style.display = "block";
        document.body.style.overflow = 'hidden';

        // FUNGSI MENAMPILKAN DATA TANPA REFRESH HALAMAN
        // Mengirim data ke PHP menggunakan AJAX

        function loadTimData() {
            // Ambil nilai id_tugas_card di sini agar dapat diakses di dalam fungsi AJAX
            var id_tugas_card = document.getElementById('id_tugas_popup_tim').value;

            $.ajax({
                type: 'POST',
                url: 'function/functions_data-tim.php', // Ganti ini sesuai dengan nama file PHP yang mengambil data 'tim'
                data: { id_tugas_tim: id_tugas_card }, // Kirim id_tugas_tim ke server
                success: function (data) {
                    // Perbarui bagian tampilan tabel 'tim' dengan data baru
                    $('#timTable').html(data);
                },
                error: function (error) {
                    // Handle kesalahan
                    console.log(error);
                }
            });
        }

        // Panggil fungsi untuk memuat data Tim saat halaman dimuat
        $(document).ready(function () {
            loadTimData();
        });

        // Mengirim data ke PHP menggunakan AJAX untuk menambah data
        $('.tambah-link').on('click', function () {
            var idUser = $(this).data('iduser');

            // Kirim permintaan AJAX untuk menambah data ke tabel 'tim'
            $.ajax({
                type: 'POST',
                url: 'function/functions_tambah-tim.php',
                data: { id_user: idUser, id_tugas_tim: id_tugas_card },
                success: function (response) {
                    alert(response);

                    // Setelah sukses menambahkan data, perbarui tampilan data tanpa refresh
                    loadTimData();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        // Mengirim data ke PHP menggunakan AJAX untuk menghapus data
        $(document).on('click', '.hapus-link', function() {
            var idTim = $(this).data('idtim');

            // Kirim permintaan AJAX untuk menghapus data dari tabel 'tim'
            $.ajax({
                type: 'POST',
                url: 'function/functions_hapus-tim.php',
                data: { id_tim: idTim },
                success: function (response) {
                    alert(response);

                    // Setelah sukses menghapus data, perbarui tampilan data tanpa refresh
                    loadTimData();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    }


// Fungsi untuk menutup popup
    function closePopupTim() {
        document.getElementById("popup-tim").style.display = "none";
        document.getElementById("popup-daftar-tim").style.display = "none";
        document.getElementById("overlay1").style.display = "none";
        document.body.style.overflow = 'auto';

    // Melakukan refresh halaman
    location.reload(true); // true untuk melakukan refresh dari server
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


// FUNGSI BUKA POPUP DAFTAR TIM
    // Fungsi untuk membuka popup
    function openPopupDaftarTim() {
        document.getElementById('popup-daftar-tim').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

// Fungsi untuk menutup popup
function closePopupDaftarTim() {
    document.getElementById('popup-daftar-tim').style.display = 'none';
    document.body.style.overflow = 'auto';
}
// ============================== //


// FUNGSI SUBMIT INPUT TAMBAH DETAIL TUGAS
function submitFormInputDetail() {
    // Mendapatkan nilai input
    var id_tugas_tim_popup1 = document.getElementById('id_detail_tugas_popup').value;
    var inputDetailTugasTim = document.getElementById('inputTextTim').value;
    

    // FUNGSI TAMBAH DETAIL TUGAS TANPA REFRESH
        // Mengirim data ke PHP menggunakan AJAX
        $.ajax({
            type: 'POST',
            url: 'function/functions_input-detail-tugas.php', // Sesuaikan dengan nama file PHP yang akan menangani data
            data: { id_tugas_tim: id_tugas_tim_popup1, input_detail_tugas_tim: inputDetailTugasTim },
            success: function(response) {
                // Handle respons dari server jika diperlukan
                console.log('Data terkirim: ' + response);
                closePopupInputDetail()

                // Contoh: Menampilkan pesan hasil operasi di popup-content-result
                document.getElementById('popup-content-result').innerHTML = response;
                resetInputField()
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
    document.getElementById('inputTextTim').value = '';
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
    xmlhttp.send("id_detail_tugas_tim=" + id + "&is_dicoret_tim=" + (isDicoret ? '0' : '1'));
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
    xmlhttp.send("id_detail_tugas_tim=" + id + "&new_task_name_tim=" + encodeURIComponent(newTaskName));

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
        xmlhttp.send("id_detail_tugas_tim=" + id);
    }
}
// ============================== //










