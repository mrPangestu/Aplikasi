<?php
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
            header("Location: ../start.php");
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
                header("Location: ../login.php"); // Redirect ke halaman yang sama setelah logout
                exit();
            }
        } else {
            // Jika belum login, redirect ke halaman login
            header("Location: ../login.php");
            exit();
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="StyleCss/style-navbar.css">
    <link rel="stylesheet" href="StyleCss/pengaturan.css">
    <link rel="stylesheet" href="StyleCss/notif.css">
    <link rel="stylesheet" href="StyleCss/footer.css">
    <title>Pengaturan</title>
</head>
<body>

<!-- HEADER -->
<input type="checkbox" class="checklist" id="myCheck" onclick="myFunction()">
<!-- SIDEBAR -->
    <nav>
        <div class="sidebar">
            <div class="sidebar-bg">
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
                <a  class="" href="tugas_tim.php"><i class="fa-solid fa-people-group" style="margin: 0 16px 0 5px ;">  </i>Tim</a>
            </div>
            <div class="menu_list">
                <span>Menu</span>
                <a class="" href="kalender.php"><i class="fa-solid fa-calendar-days" style="margin: 0 16px 0 5px ;">  </i>Kalender</a>
                <a class="active" href="#"><i class="fa-solid fa-gear" style="margin: 0 16px 0 5px ;"> </i>Pengaturan</a>
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
 
<div class="navbar" style=''>
        <div class="navbar-content">
            <div class="navbar-left">
                <div class="navbar-icon">
                    <button >
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

<div class="container-setting">
    <h1>Pengaturan</h1>
    <div class="settings-wrapper">
        <ul class="settings-list">
            <li><a href="pengaturan/akun.php" data-content-id="account-settings-content">Pengaturan akun</a></li>
            <li><a href="pengaturan/panduan.php">Panduan pengguna</a></li>
            <li><a href="pengaturan/tentangapl.php">Tentang aplikasi</a></li>
            <li><a href="#" id="app-version-link" data-content-id="app-version-content">Versi aplikasi</a></li>
            <!-- <li><a href="#" data-content-id="logout-content">Logout</a></li> -->
        </ul>
        
        <div id="settings-content">
            <div class="settings-content-item" id="account-settings-content">
                <p>Account Settings content goes here.</p>
            </div>
            <div class="settings-content-item" id="user-guide-content">
                <p>User Guide content goes here.</p>
            </div>
            <div class="settings-content-item" id="tentang-aplikasi-content">
                <p>Informasi tentang aplikasi.</p>
            </div>
            <div class="settings-content-item" id="app-version-content">
                <p>Versi aplikasi: 1.0.0</p>
            </div>
            <!-- <div class="settings-content-item" id="logout-content">
                <p>Logout content goes here.</p>
            </div> -->
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/7b730c13ab.js" crossorigin="anonymous"></script>
<script src="pengaturan/pengaturan.js"></script>
<script src="ScriptJs/header.js"></script>
</body>
</html>
