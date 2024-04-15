<?php
    date_default_timezone_set('Asia/Jakarta');
    include "database.php";
    // START LOGIN
        session_start();
        
    // FUNGSI LOGOUT
        function logoutUser() {
            session_unset();
            session_destroy();
        }

    // Fungsi untuk mengecek apakah pengguna sudah login
        function isUserLoggedIn() {
            return isset($_SESSION['user_id']);
        }

    // Fungsi untuk mendapatkan nama pengguna berdasarkan ID
        function getUserName($kon, $user_id) {
            $sql = "SELECT username FROM login WHERE id_user = $user_id";
            $result = $kon->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['username'];
            }
            return null;
        }

    // Jika pengguna belum login, redirect ke halaman login
        if (!isUserLoggedIn()) {
            header("Location: start.php");
            exit();
        }

        if ($kon->connect_error) {
            die("Koneksi Gagal: " . $kon->connect_error);
        }

    // Cek apakah pengguna sudah login
        if (isUserLoggedIn()) {
            // Jika sudah login, tampilkan nama pengguna
            $user_id = $_SESSION['user_id'];
            $username = getUserName($kon, $user_id);

            // Jika tombol logout ditekan
            if (isset($_POST['logout'])) {
                logoutUser();
                header("Location: login.php"); // Redirect ke halaman yang sama setelah logout
                exit();
            }
        } else {
            // Jika belum login, redirect ke halaman login
            header("Location: login.php");
            exit();
        }

    // Tambah tugas 
        if(isset($_POST['add'])){

            // Periksa apakah pengguna sudah login
            if (!isset($_SESSION['user_id'])) {
                // Redirect atau tampilkan pesan error jika tidak login
                header("Location: login.php");
                exit();
            }
            
            $dob = date('Y-m-d', strtotime($_POST['tgl']));
            $id_user = $_SESSION['user_id'];

            $q_insert = "INSERT INTO `tugas_tim` (`id_user`, `nama_tugas`, `tanggal`, `waktu`, `notif`, `ulangi`, `status`) VALUES (
                '$id_user',
                '".$_POST['title']."',
                ".(empty($_POST['tgl']) ? "DATE_ADD(CURDATE(), INTERVAL 1 DAY)" : "'".$_POST['tgl']."'").",
                ".(empty($_POST['waktu']) ? 'DEFAULT' : "'".$_POST['waktu']."'").",
                ".(empty($_POST['notif']) ? 'DEFAULT' : "'".$_POST['notif']."'").",
                ".(empty($_POST['ulangi']) ? 'DEFAULT' : "'".$_POST['ulangi']."'").",
                'belum selesai'
            )";
            
            $run_q_insert = mysqli_query($kon, $q_insert);

            if($run_q_insert){
                header('Refresh:0.5; url=tugas_tim.php');
            } else {
                echo "Error: " . $q_insert . "<br>" . mysqli_error($kon);
            }
        }

    // UBAH TUGAS
        if (isset($_POST['ubah']) && isset($_POST['id_ubah_tugas'])) {
            $id_tugas = $_POST['id_ubah_tugas'];

            // Bangun query UPDATE
            $query_update = "UPDATE `tugas_tim` SET";
            $update_values = array();

            // Periksa dan tambahkan kolom-kolom yang diisi dalam formulir
            if (!empty($_POST['ubah-title'])) {
                $update_values[] = "`nama_tugas`='" . $_POST['ubah-title'] . "'";
            }

            if (!empty($_POST['ubah-tgl'])) {
                $update_values[] = "`tanggal`='" . $_POST['ubah-tgl'] . "'";
            }

            if (!empty($_POST['ubah-wak'])) {
                $update_values[] = "`waktu`='" . $_POST['ubah-wak'] . "'";
            }

            if (!empty($_POST['ubah-notif'])) {
                $update_values[] = "`notif`='" . $_POST['ubah-notif'] . "'";
            }

            if (!empty($_POST['ubah-ulangi'])) {
                $update_values[] = "`ulangi`='" . $_POST['ubah-ulangi'] . "'";
            }

            // Pastikan ada kolom yang akan diubah
            if (!empty($update_values)) {
                $query_update .= ' ' . implode(', ', $update_values);
                $query_update .= " WHERE id_tugas_tim = '$id_tugas'";

                // Jalankan query UPDATE
                $result_update = mysqli_query($kon, $query_update);

                // Periksa apakah query berhasil dijalankan
                if ($result_update) {
                    echo "Status tugas berhasil diperbarui.";
                    header('Refresh:0.5; url=tugas_tim.php');
                } else {
                    echo "Gagal memperbarui status tugas: " . mysqli_error($kon);
                }
            } else {
                // Tidak ada kolom yang diisi dalam formulir
                echo "Tidak ada perubahan pada data.";
            }
        }


    // SELESAIKAN TUGAS
        if (isset($_POST['done']) && isset($_POST['id_tugas'])) {
            $id_tugas = $_POST['id_tugas'];
            $currentDate = date('Y-m-d H:i:s');

            // PERBAHARUI TUGAS MENJADI SELESAI
            $q_update_tugas = "UPDATE tugas_tim SET status = 'selesai', tanggal_selesai = '$currentDate' WHERE id_tugas_tim = '$id_tugas'";
            $result_update_tugas = mysqli_query($kon, $q_update_tugas);

            // ULANGI TUGAS
            if ($result_update_tugas) {
                $q_select_tugas = "SELECT * FROM tugas_tim WHERE id_tugas_tim = '$id_tugas'";
                $result_select_tugas = mysqli_query($kon, $q_select_tugas);

                if ($result_select_tugas) {
                    $row_tugas = mysqli_fetch_assoc($result_select_tugas);

                    // TANGANI PENGULANGAN
                    handleRepetition($kon, $row_tugas);
                } else {
                    echo "Error: " . $q_select_tugas . "<br>" . mysqli_error($kon);
                }
            } else {
                echo "Error: " . $q_update_tugas . "<br>" . mysqli_error($kon);
            }
        }

    // FUNGSI PENGULANGAN
        function handleRepetition($kon, $tugas)
        {
            $pola_pengulangan = $tugas['ulangi'];
            $id_user = $tugas['id_user'];
        
            // Periksa apakah tugas diulang atau tidak
            if ($pola_pengulangan !== 'tidak') {
                createRepeatedTask($kon, $tugas);
            }
        }
        
        function createRepeatedTask($kon, $tugas)
        {
            // Hitung tanggal baru berdasarkan interval
            $interval = '';
        
            switch ($tugas['ulangi']) {
                case 'hari':
                    $interval = '+1 day';
                    break;
                case 'minggu':
                    $interval = '+1 week';
                    break;
                case 'bulan':
                    $interval = '+1 month';
                    break;
                case 'tahun':
                    $interval = '+1 year';
                    break;
                // Tambahkan pola pengulangan lainnya sesuai kebutuhan
            }
        
            $tanggal_selesai = date('Y-m-d H:i:s', strtotime($interval));
        
            // Buat salinan tugas baru
            $q_insert_tugas_baru = "INSERT INTO `tugas_tim` (`id_user`, `nama_tugas`, `tanggal`, `waktu`, `notif`, `ulangi`, `status`)
                                    VALUES (
                                        '".$tugas['id_user']."',
                                        '".$tugas['nama_tugas']."',
                                        '".$tanggal_selesai."',
                                        '".$tugas['waktu']."',
                                        '".$tugas['notif']."',
                                        '".$tugas['ulangi']."',
                                        'belum selesai'
                                    )";
            $result_insert_tugas_baru = mysqli_query($kon, $q_insert_tugas_baru);
        
            if (!$result_insert_tugas_baru) {
                echo "Error: " . $q_insert_tugas_baru . "<br>" . mysqli_error($kon);
            }
        }
        

    // HAPUS TUGAS
        if (isset($_POST['hapus']) && isset($_POST['id_tugas'])) {
            $id_tugas = $_POST['id_tugas'];

            // Lakukan query delete
            $query_delete = "DELETE FROM tugas_tim WHERE id_tugas_tim = '$id_tugas'";
            $result_delete = mysqli_query($kon, $query_delete);

            // Periksa apakah query berhasil dijalankan
            if ($result_delete) {
                echo "Status tugas berhasil diperbarui.";
                header('Refresh:0.5; url=tugas_tim.php');
            } else {
                echo "Gagal memperbarui status tugas: " . mysqli_error($kon);
            }
        }

            
    $kon->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoTune</title>
    <link rel="stylesheet" href="StyleCss/card-tugas.css">
    <link rel="stylesheet" href="StyleCss/style-navbar.css">
    <link rel="stylesheet" href="StyleCss/style-popup.css">
    <link rel="stylesheet" href="StyleCss/footer.css">
    <link rel="stylesheet" href="StyleCss/notif.css">
    <style>
        body {
            background-image: url(img/bg8.jpg);
            background-size: cover;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<!-- POP UP -->
<div>

<!-- POP UP CREATE -->
    <div class="popup" id="myPopup">
        <div class="popup-content">
            <div class="container-create">
                <div class="tasks-container" id="tasksContainer">
                    <div class="container-box">
                        <div class="left">
                            <div id="calendar-container">
                                <div class="label-calender">
                                    <label for="year-selector">Tahun:</label>
                                    <select id="year-selector"></select>
                                    <button id="update-calendar" style='background-color: rgb(116, 113, 255);'>Perbarui Kalender</button>
                                </div>
                                <div class="calendar-header" id="calendar-header">
                                    <button id="prev-month" style='background-color: rgb(116, 113, 255)'>Sebelumnya</button>
                                    <span id="current-month-year"></span>
                                    <button id="next-month" style='background-color: rgb(116, 113, 255);'>Berikutnya</button>
                                </div>
                            </div>
                            <table id="calendar"></table>
                            
                            <div id="event-form">
                                <input type="date" required>
                                <button id="add-event">Tambah Acara</button>
                            </div>
                        </div>
                        <div class="right" style="background: linear-gradient(50deg,rgb(184, 125, 255), rgb(81, 78, 233));">
                        <p class="close" onclick="closePopup()">&times;</p>
                            <h2>Tugas apa Hari Ini</h2>
                            <form class="popup-form" action="" method="post">
                                <input class="field" id="crt_btn" type="text" name="title" placeholder="Tugas apa?" maxlength="20" required><br />
                                <div class="tgl-waktu">
                                    <input class="field1" id="event-date" type="date" name="tgl"><br />
                                    <input class="field1" id="crt_btn" type="time" name="waktu"><br />
                                </div>
                                <div class="pilihan">
                                    <select class="field1" name="notif">
                                        <option value="" disabled selected>--Notifikasi--</option>
                                        <option value="yes" >Nyala</option>
                                        <option value="no" >Tidak</option>
                                    </select><br />
                                    <select class="field1" name="ulangi">
                                        <option value="" disabled selected>--Ulangi--</option>
                                        <option value="tidak" >Tidak</option>
                                        <option value="hari" >Hari</option>
                                        <option value="minggu" >Minggu</option>
                                        <option value="bulan" >Bulan</option>
                                        <option value="tahun" >Tahun</option>
                                    </select><br />
                                </div>
                                <button class="btn-submit-create" type="submit" value="simpan" name="add" ><b>Tambah</b></button><br />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- POP-UP DETAIL TUGAS -->


    <div class="detail-container" id="popup-container" style='background-image: url(StyleCss/img/ungu1.jpg);'>
        <span id="detail-close-btn" onclick="closePopup1()">&times;</span>
        <div class="head">
        <input type='hidden' name='id_detail_tugas' id='id_tugas_popup1'>
            <div id="popup-content-judulTugas-result"></div>
            <div>
                <button class="btn_c_d" style="margin: 15px; background-color: rgb(81, 78, 233);" onclick=" openPopupInputDetail()">Tambah</button>
                <a class="btn_c_d1" style="background-color: rgb(81, 78, 233);" onclick=" openPopupInputDetail()"><i class="fas fa-plus"></i></a>
            </div>
            
        </div>
        <div class="detail-tugas-content">
            <div class="tabel-detail-wrapper">
              <table class="tabel-detail-header">
                <!-- Header Table -->
                <tr>
                  <th style="width:5.1%; ">No</th>
                  <th>Detail tugas</th>
                  <th style="width:20%; ">Menu</th>
                </tr>
              </table>
            </div>
            <div class="tabel-detail-wrapper">
                <table class="tabel-detail-content">
                    <!-- Content Table -->
                    <div id="popup-content-result"></div>
                    <div id="result-container"></div>
                </table>
            </div>
        </div>
    </div>
    
<!-- POP-UP INPUT DETAIL TUGAS -->
    <div class="popup-input-detail" id="myPopup-input-detail">
        <div class="popup-input-detail-content">
            <span class="close-input" onclick="closePopupInputDetail()">&times;</span>
            <form method="post">

                <input type='hidden' name='id_detail_input_tugas' id='id_detail_tugas_popup'>
                <input type="text" id="inputTextTim" name="inputdetailtugas" placeholder="Tambah Detail Tugas" required>
                <button type="button" style="background-color: rgb(81, 78, 233);" name="tambah-detail" onclick="submitFormInputDetail()">Submit</button>
            </form>

        </div>
    </div>

<!-- POP-UP EDIT DETAIL TUGAS -->
    <div class="popup-input-detail" id="myPopup-edit-detail">
        <div class="popup-input-detail-content">
            <span class="close-input" onclick="closePopupEditDetail()">&times;</span>
            <form method="post" onsubmit="return submitUpdateForm()">
                <!-- Hidden input to store id_detail_tugas -->
                <input type='hidden' name='id_detail_edit_tugas' id='id_edit_detail_tugas_popup'>

                <!-- Other form elements -->
                <input type="text" id="editText" name="editdetailtugas" placeholder="Edit Detail Tugas" required>
                <button class="btn_update_detail_tugas" type="submit" style="background-color: rgb(81, 78, 233);" value="Update" onclick="closePopupEditDetail()">Ubah</button>
            </form>
        </div>
    </div>

<!-- POP-UP UBAH -->
    <div class="popup-ubah" id="myPopupUbah">
        <div class="popup-ubah-content">
            <div class="container-create">
                <div class="tasks-container" id="tasksContainer">
                    <div class="container-box-ubah">
                        <div class="right" style="background: linear-gradient(50deg,rgb(184, 125, 255), rgb(81, 78, 233));">
                        <p class="close" onclick="closePopupUbah()">&times;</p>
                            <h2>Form Update</h2>
                            <form class="popup-form" action="" method="post">
                            <input type='hidden' name='id_ubah_tugas' id='id_ubah_tugas_popup'>
                                <input class="field" id="crt_btn" type="text" name="ubah-title" placeholder="Tugas apa?" maxlength="20"><br />
                                <div class="tgl-waktu">
                                    <input class="field1" id="event-date" type="date" name="ubah-tgl"><br />
                                    <input class="field1" id="crt_btn" type="time" name="ubah-wak"><br />
                                </div>
                                <div class="pilihan">
                                    <select class="field1" name="ubah-notif">
                                        <option value="" disabled selected>--Notifikasi--</option>
                                        <option value="yes" >Nyala</option>
                                        <option value="no" >Tidak</option>
                                    </select><br />
                                    <select class="field1" name="ubah-ulangi">
                                        <option value="" disabled selected>--Ulangi--</option>
                                        <option value="tidak" >Tidak</option>
                                        <option value="hari" >Hari</option>
                                        <option value="minggu" >Minggu</option>
                                        <option value="bulan" >Bulan</option>
                                        <option value="tahun" >Tahun</option>
                                    </select><br />
                                </div>
                                <button class="btn-submit-create" type="submit" value="simpan" name="ubah" ><b>Ubah</b></button><br />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>








<!-- POP-UP TIM -->
    <div class="popup-tugas-tim">
        
        <div class="daftar-tim-popup" id="popup-daftar-tim">
            <div class="dafar-tim-container" >
                <div class="head-tim">
                    <h4 style='color: white; '>Daftar User</h4>
                    <span id="daftar-tim-close-btn" onclick="closePopupDaftarTim()">X</span>
                </div>
                <div class="detail-tim-content">
                    <div class="tabel-detail-wrapper">
                        <table class="tabel-detail-header">
                            <!-- Header Table -->
                            <tr>
                                <th >ID</th>
                                <th >Nama User</th>
                                <th ></th>
                            </tr>
                            <?php
                                $id_user = $_SESSION['user_id'];
                                include "database.php";

                                $query = "SELECT `user`.`id_user`, `nama_depan`, `nama_tengah`, `nama_belakang`, `username`, `gender`
                                        FROM `login` 
                                        INNER JOIN `user` ON `login`.`id_user` = `user`.`id_user`
                                        WHERE user.id_user != '$id_user'";

                                $result = $kon->query($query);

                                if ($result) {
                                    // Tampilkan hasil filter
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $id_user1 = $row["id_user"];
                                            $n_depan = $row["nama_depan"];
                                            $n_tengah = $row["nama_tengah"];
                                            $n_belakang = $row["nama_belakang"];
                                            $username = $row["username"];
                                            $gender = $row["gender"];

                                            echo "<tr>
                                                    <td>$id_user1</td>
                                                    <td>$n_depan $n_tengah $n_belakang</td>
                                                    <td><a href='#' class='tambah-link' data-iduser='$id_user1'>Tambah</a></td>
                                                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No results found.</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>Error: " . $kon->error . "</td></tr>";
                                }

                                $kon->close();
                                ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tim-popup" id="popup-tim">
            <div class="tim-container">
                <a id="detail-close-btn" onclick="closePopupTim()">&times;</a>
                <div class="head-tim">
                    <input type='hidden' name='id_detail_tugas_tim' id='id_tugas_popup_tim'>
                    <h4 style='color: white; '>Daftar Tim</h4>
                    <div>
                        <button style="margin: 15px; background: rgb(79, 78, 174);" onclick="openPopupDaftarTim()">Tambah</button>
                    </div>
                    
                </div>
                <div class="detail-tim-content">
                    <div class="tabel-detail-wrapper">
                    <table class="tabel-detail-header" id="timTable">
                        <tr>
                            <th >ID</th>
                            <th >Nama User</th>
                            <th></th>
                        </tr>
                        
                    </table>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>

</div>
<div id="overlay2" class="overlay2"></div>
<div id="overlay1" class="overlay1"></div>

<!-- HEADER -->
<input type="checkbox" class="checklist" id="myCheck" onclick="myFunction()">

<!-- SIDEBAR -->
    <nav style="background: linear-gradient(rgb(153, 0, 255),  rgb(153, 158, 248)); ">
        <div class="sidebar" style="background-image: url(StyleCss/img/4014236.jpg);">
            <div class="sidebar-bg" style="background: rgb(119, 117, 245, 0.5);">
                <?php
                    $id_user = $_SESSION['user_id'];
                    include "database.php";
                    
                    $qury = "SELECT `nama_depan`, `nama_tengah`, `nama_belakang`, `gender`
                    FROM `login` 
                    INNER JOIN user ON login.id_user = user.id_user
                    WHERE user.id_user = '$id_user'";

                    $result = $kon->query($qury);

                    if ($result){
                    // Tampilkan hasil filter
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $n_depan = $row["nama_depan"];
                                $n_tengah = $row["nama_tengah"];
                                $n_belakang = $row["nama_belakang"];
                                $gender = $row["gender"];

                                if ($gender == 'pria'){
                                    $foto = 'img/male.png';
                                } else {
                                    $foto = 'img/female.png';
                                }
                                echo "<img src=$foto alt='foto-profil'>";
                                echo "<div style='color: white; text-align: center;'><h3>$n_depan $n_tengah $n_belakang</h3></div>";
                            }
                        } else {
                            echo "No results found.";
                        }
                    }
                    $kon->close();
                ?>
                <span class = "line"></span>
            </div>
        </div>
        <div class="bot">
            <div class="menu_list">
                <span>Tugas</span>
                <a  class="" href="index.php"><i class="fa-solid fa-person" style="margin: 0 25px 0 10px ;"></i>Pribadi</a>
                <a  class="active" href="#"><i class="fa-solid fa-people-group" style="margin: 0 16px 0 5px ;">  </i>Tim</a>
            </div>
            <div class="menu_list">
                <span>Menu</span>
                <a class="" href="kalender.php"><i class="fa-solid fa-calendar-days" style="margin: 0 16px 0 5px ;">  </i>Kalender</a>
                <a class="" href="pengaturan.php"><i class="fa-solid fa-gear" style="margin: 0 16px 0 5px ;"> </i>Pengaturan</a>
            </div>
            <div class="menu_list">
                <?php
                    if (isset($username)) {
                        // Jika pengguna sudah login
                        echo "<form method='post' class='form_logout' onsubmit='return confirm(\"Apakah Anda yakin ingin logout?\")'>
                                <button class='btn_logout' type='submit' name='logout'><i class='fa-solid fa-right-from-bracket'></i>Logout</button>
                            </form>";
                    }
                    ?>
            </div>
        </div>
    </nav>

<!-- NAVBAR -->
    <div class="navbar" style='background-image: url(StyleCss/img/1.jpg);'>
        <div class="navbar-content" style='background-color: rgb(134, 117, 245, 0.5);'>
            <div class="navbar-left">
                <div class="navbar-icon">
                    <button style="background-color: rgb(79, 78, 174);">
                        <i class="fa-solid fa-bars fa-xl"></i>
                    </button>
                </div>
                <div class="navbar-title"><a href='index.php'><h1 style='color:white;'>TodoTune</h1></a></div>
                <img src="img/list.png" alt="Logo" class="navbar-logo">
            </div>
            <div class="navbar-right">
                <div class="navbar-mid">
                    <h4 id="clock"></h4>
                    <h4 id="tanggalSaatIni"></h4>
                </div>
                <div class="navbar-menu">
                    <div class="navbar-menu_n" id="popupContainer">
                        <a href="#" id="bellIcon">
                            <i class="fa-regular fa-bell fa-lg"></i>
                            <?php
                            include "database.php";

                            $id_user = $_SESSION['user_id'];

                            $sql_union = "SELECT 'tugas' AS sumber, kategori.nama_kategori, tugas.nama_tugas, tugas.tanggal
                                        FROM tugas
                                        LEFT JOIN kategori ON kategori.id_kategori = tugas.id_kategori 
                                        WHERE tugas.id_user = $id_user 
                                            AND DATEDIFF(tugas.tanggal, CURDATE()) < 2
                                            AND tugas.status = 'belum selesai' 
                                            AND tugas.notif = 'yes'
                                        UNION ALL
                                            SELECT 'tugas_tim' AS sumber, NULL AS nama_kategori, tugas_tim.nama_tugas, tugas_tim.tanggal
                                            FROM tugas_tim
                                            WHERE tugas_tim.id_user = $id_user
                                                AND DATEDIFF(tugas_tim.tanggal, CURDATE()) < 2
                                                AND tugas_tim.status = 'belum selesai' 
                                                AND tugas_tim.notif = 'yes'
                                            ORDER BY tanggal ASC";

                            $result_union = $kon->query($sql_union);

                            if ($result_union && $result_union->num_rows > 0) {
                                $count = $result_union->num_rows;
                                echo '<span id="notificationIndicator">' . $count . '</span>'; // buat ngitung angka sesuai jumlah data
                            }
                            ?>
                        </a>
                        <div class="popup_notif">
                            <!-- Isi popup di sini -->
                            <?php
                            if ($result_union) {
                                if ($result_union->num_rows > 0) {
                                    echo "<h4 style='color:black; margin-bottom: 10px; margin-top: 0;'>Notifikasi</h4>";
                                    while ($row_union = $result_union->fetch_assoc()) {
                                        if ($row_union['sumber'] == 'tugas') {
                                            echo "<p>" . $row_union["nama_kategori"] . "|" . " ". "Deadline tugas " . $row_union["nama_tugas"] . " " . "sebentar lagi." . " " . "Ayo segera selesaikan!" . "</p>";
                                            // echo "<p>" . $row_union["nama_kategori"] . " " . "|" . "Deadline tugas " . $row_union["nama_tugas"] . " " . "|" . " " . $row_union["tanggal"] . "</p>";
                                        } elseif ($row_union['sumber'] == 'tugas_tim') {
                                            echo "<p>" . "Tim" . "|" . " " ."Deadline tugas ". $row_union["nama_tugas"] . " " . "sebentar lagi." . " " . "Ayo segera selesaikan!".  "</p>";
                                            // echo "<p>" . "Tim" . " " . "|" . " " . $row_union["nama_tugas"] . " " . "|" . " " . $row_union["tanggal"] .  "</p>";
                                        }
                                    }
                                } else {
                                    echo "<p style='color:black;'>Tidak ada Notifikasi</p>";
                                }
                            } else {
                                echo "Error: " . $sql_union . "<br>" . $kon->error;
                            }

                            $kon->close();
                            ?>
                            </div>
                            <!-- <span class="close_notif">&times;</span> -->
                        </div>
                    <a href="profil.php"><i class="fa-regular fa-user fa-lg"></i></a>
                </div>

            </div>
        </div>
    </div>
<div class="nav-bg"></div>

<!-- UTAMA -->
    <div class="content" id="content">
        <!-- ISI TUGAS --> 
        <div class="card-container" id="c-container">
            <!-- TUGAS -->
            <?php
                $id_user = $_SESSION['user_id'];
                include "database.php";

                $sql = "SELECT `id_tugas_tim`, `nama_tugas`, `tanggal`, `waktu`
                FROM `tugas_tim` 
                WHERE status ='belum selesai' and id_user = '$id_user'";

                $result = $kon->query($sql);

                if (!$result) {
                    die("Error executing query: " . $kon->error);
                }

                // Inisialisasi array untuk menyimpan data
                $tugasArray = array();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $tugasArray[] = $row;
                    }

                    // Reverse array agar data baru muncul di awal
                    $tugasArray = array_reverse($tugasArray);

                    foreach ($tugasArray as $row) {
                        $id_tugas = $row["id_tugas_tim"];
                        $nama = $row["nama_tugas"];
                        $tgl = $row["tanggal"];
                        $waktu = $row["waktu"];
                        $Time = date('H:i', strtotime($waktu));
                        $date = date('d-m-Y', strtotime($tgl));

                        $currentDateTime = new DateTime();
                        $currentDate = date('Y-m-d H:i:s');
                        $taskDateTime = DateTime::createFromFormat('Y-m-d H:i:s', "$tgl $waktu");

                        // Memeriksa apakah waktu tugas telah lewat
                        if ($currentDateTime > $taskDateTime) {
                            // Jika ya, ubah status menjadi "selesai"
                            $updateQuery = "UPDATE tugas_tim SET status = 'tidak selesai', tanggal_selesai = '$currentDate' WHERE id_tugas_tim = $id_tugas";
                            $result_update_tugas_tds = mysqli_query($kon, $updateQuery);

                            if ($result_update_tugas_tds) {
                                // Tugas berhasil ditandai sebagai selesai, sekarang kita akan menangani pengulangan
                                $q_select_tugas = "SELECT * FROM tugas_tim WHERE id_tugas_tim = '$id_tugas'";
                                $result_select_tugas = mysqli_query($kon, $q_select_tugas);
                        
                                if ($result_select_tugas) {
                                    $row_tugas = mysqli_fetch_assoc($result_select_tugas);
                        
                                    // Tangani pengulangan
                                    handleRepetition($kon, $row_tugas);
                                } else {
                                    echo "Error: " . $q_select_tugas . "<br>" . mysqli_error($kon);
                                }
                            } else {
                                echo "Error: " . $q_update_tugas . "<br>" . mysqli_error($kon);
                            }
                        }


                        echo "<div class='card'>
                                <div class='menu'>
                                    <form class='atas' method='post' style='justify-content: space-between;'>
                                        <button type='button' style='border: 1px solid rgb(79, 78, 174);' onclick='openPopupTim($id_tugas)'>Tim</button>
                                        <input type='hidden' name='id_detail_tugas_tim' id='id_tugas_card_$id_tugas' value='$id_tugas'>
                                        <button type='button' style='border: 1px solid rgb(79, 78, 174);' onclick='openPopup1($id_tugas)'>Detail</button>
                                    </form>
                                    <form class='bawah' method='post'>
                                        <input type='hidden' name='id_tugas' value='$id_tugas'>
                                        <button type='submit' style='border: 1px solid rgb(79, 78, 174);' name='hapus'>Hapus</button>
                                        <button type='submit' style='border: 1px solid rgb(79, 78, 174);' name='done'>Selesai</button>
                                        <button type='button' style='border: 1px solid rgb(79, 78, 174);' name='update' onclick='openPopupUbah($id_tugas)'>Ubah</button>
                                    </form>
                                </div>
                                <div class='card1'>
                                    <div class='card-c'>
                                        <h1>Lihat</h1>
                                    </div>
                                </div>
                                <div class='card1' style='background: linear-gradient(rgb(81, 78, 233), rgb(186, 198, 240),white);'></div>
                                <div class='card-content'>
                                    <div class='grid-container' style='background-color: rgb(79, 78, 174);'>
                                        <div class='item5'>
                                            <h3><i class='fa-solid fa-people-group fa-2xl'></i></i></h3>
                                        </div>
                                        <div class='item2'>
                                            <h3>TIM</h3>
                                        </div>
                                        <div class='item3'>
                                            <p>$date</p>
                                        </div>
                                        <div class='item1'>
                                            <h2>$nama</h2>
                                        </div>
                                        <div class='item4'>
                                            <p>$Time</p>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                    }
                }
            $kon->close();
            ?>
            <!-- TIM -->
            <?php
                $id_user = $_SESSION['user_id'];
                include "database.php";

                $sql_tim = "SELECT tim.id_tugas_tim, tugas_tim.nama_tugas, tugas_tim.tanggal, tugas_tim.waktu
                            FROM tim 
                            INNER JOIN tugas_tim ON tim.id_tugas_tim = tugas_tim.id_tugas_tim
                            WHERE tugas_tim.status ='belum selesai' AND tim.id_user = '$id_user'";
                            $result_tim = $kon->query($sql_tim);

                            if (!$result_tim) {
                                die("Error executing query: " . $kon->error);
                            }

                // Inisialisasi array untuk menyimpan data
                $tugasArray = array();

                if ($result_tim->num_rows > 0) {
                    while ($row = $result_tim->fetch_assoc()) {
                        $tugasArray[] = $row;
                    }

                    // Reverse array agar data baru muncul di awal
                    $tugasArray = array_reverse($tugasArray);

                    foreach ($tugasArray as $row) {
                        $id_tugas = $row["id_tugas_tim"];
                        $nama = $row["nama_tugas"];
                        $tgl = $row["tanggal"];
                        $waktu = $row["waktu"];
                        $Time = date('H:i', strtotime($waktu));
                        $date = date('d-m-Y', strtotime($tgl));

                        echo "<div class='card'>
                                <div class='menu'>
                                    <form class='atas' method='post' style='justify-content: space-between;'>
                                        <button type='button' style='border: 1px solid #999999;' onclick='openPopupTim($id_tugas)'>Tim</button>
                                        <input type='hidden' name='id_detail_tugas_tim' id='id_tugas_card_$id_tugas' value='$id_tugas'>
                                        <button type='button' style='border: 1px solid #999999;' onclick='openPopup1($id_tugas)'>Detail</button>
                                    </form>
                                    <form class='bawah' method='post'>
                                        <input type='hidden' name='id_tugas' value='$id_tugas'>
                                    </form>
                                </div>
                                <div class='card1'>
                                    <div class='card-c'>
                                        <h1>Lihat</h1>
                                    </div>
                                </div>
                                <div class='card1' style='background: linear-gradient(#999999, #cccccc, white);'></div>
                                <div class='card-content'>
                                    <div class='grid-container' style='background-color: #999999;'>
                                        <div class='item5'>
                                            <h3><i class='fa-solid fa-people-group fa-2xl'></i></i></h3>
                                        </div>
                                        <div class='item2'>
                                            <h3>TIM</h3>
                                        </div>
                                        <div class='item3'>
                                            <p>$date</p>
                                        </div>
                                        <div class='item1'>
                                            <h2>$nama</h2>
                                        </div>
                                        <div class='item4'>
                                            <p>$Time</p>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                    }
                }
            $kon->close();
            ?>
        </div>  
    </div>    
<!-- Footer -->
    <div class='btn_create'>
        
        <button class='btn_create1' id="openPopupBtn" style="background-color: rgb(81, 78, 233);" onclick="openPopup()"><i class="fa-solid fa-plus fa-xl"></i></button>
    </div>
    
<!-- Javascript -->
    <script src="https://kit.fontawesome.com/7b730c13ab.js" crossorigin="anonymous"></script>
    <script src="ScriptJs/kalendar.js"></script>
    <script src="ScriptJs/script-tim.js"></script>
    <script src="ScriptJs/header.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</body>
</html>

