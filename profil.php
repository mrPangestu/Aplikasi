<?php
    include "database.php";
    // START LOGIN
        session_start();

    // FUNGSI LOGOUT
        function logoutUser()
        {
            session_unset();
            session_destroy();
        }

    // Fungsi untuk mengecek apakah pengguna sudah login
        function isUserLoggedIn()
        {
            return isset($_SESSION['user_id']);
        }

    // Fungsi untuk mendapatkan nama pengguna berdasarkan ID
        function getUserName($kon, $user_id)
        {
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


        if (isset($_POST['hapus'])) {
            // Lakukan query delete
            $query_delete_tugas = "DELETE FROM tugas WHERE status IN ('selesai', 'tidak selesai')";
            $query_delete_tugas_tim = "DELETE FROM tugas_tim WHERE status IN ('selesai', 'tidak selesai')";

            // Execute the delete queries
            $result_delete_tugas = mysqli_query($kon, $query_delete_tugas);
            $result_delete_tugas_tim = mysqli_query($kon, $query_delete_tugas_tim);

            // Periksa apakah query berhasil dijalankan
            if ($result_delete_tugas && $result_delete_tugas_tim) {
                header('Refresh:0.5;');
            } else {
                echo "Gagal menghapus tugas: " . mysqli_error($kon);
            }
        }
    $kon->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="StyleCss/style-profil.css">
    <link rel="stylesheet" href="StyleCss/style-navbar.css">
    <link rel="stylesheet" href="StyleCss/footer.css">
    <link rel="stylesheet" href="StyleCss/style-popup.css">
    <link rel="stylesheet" href="StyleCss/notif.css">
    
</head>

<body>

<!-- Tulisan "Rincian Tugas SELESAI" -->
    <div class="skala-container" id="popup-container-skala">
        <span id="skala-close-btn" onclick="closePopupskala()">X</span>
        <div class="skala-tugas-content">
            <div class="tabel-skala-wrapper">
                <table class="tabel-skala-header">
                    <!-- Header Table -->
                    <tr>
                        <th style="width:1%;">No</th>
                        <th style="">Tugas Selesai</th>
                        <th style="width:37%;">Waktu selesai</th>
                    </tr>

                    <?php
                    include "database.php";

                    $id_user = $_SESSION['user_id'];

                    $query = "SELECT nama_tugas, tanggal_selesai FROM tugas WHERE status = 'selesai' AND id_user = '$id_user'
                    UNION
                    SELECT nama_tugas, tanggal_selesai FROM tugas_tim WHERE status = 'selesai' AND id_user = '$id_user'";

                    $result = mysqli_query($kon, $query);

                    if ($result) {
                        // Tampilkan hasil query
                        $no = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $no++;
                            echo "<tr>";
                            echo "<td>$no</td>";
                            echo "<td>{$row['nama_tugas']}</td>";
                            echo "<td>{$row['tanggal_selesai']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Gagal mengambil tugas: " . mysqli_error($kon) . "</td></tr>";
                    }

                    mysqli_close($kon);
                    ?>
                </table>
            </div>
        </div>
        </div>

<!-- Tulisan "Rincian Tugas TIDAK SELESAI" -->
    <div class="skala-container" id="popup-container-skala2">
        <span id="skala-close-btn" onclick="closePopupskala2()">X</span>
        <div class="skala-tugas-content">
            <div class="tabel-skala-wrapper">
                <table class="tabel-skala-header">
                    <!-- Header Table -->
                    <tr>
                        <th style="width:5.1%;">No</th>
                        <th style="">Tugas Tidak Selesai</th>
                        <th style="width:37%;">Waktu selesai</th>
                    </tr>

                    <?php
                    include "database.php";

                    $id_user = $_SESSION['user_id'];

                    $query = "SELECT nama_tugas, tanggal_selesai FROM tugas WHERE status = 'tidak selesai' AND id_user = '$id_user'
                    UNION
                    SELECT nama_tugas, tanggal_selesai FROM tugas_tim WHERE status = 'tidak selesai' AND id_user = '$id_user'";

                    $result = mysqli_query($kon, $query);

                    if ($result) {
                        // Tampilkan hasil query
                        $no = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $no++;
                            echo "<tr>";
                            echo "<td>$no</td>";
                            echo "<td>{$row['nama_tugas']}</td>";
                            echo "<td>{$row['tanggal_selesai']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Gagal mengambil tugas: " . mysqli_error($kon) . "</td></tr>";
                    }

                    mysqli_close($kon);
                    ?>
                </table>
            </div>
        </div>
    </div>
<div id="overlay1" class="overlay1" onclick="closePopup1()"></div>

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

                if ($result) {
                    // Tampilkan hasil filter
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $n_depan = $row["nama_depan"];
                            $n_tengah = $row["nama_tengah"];
                            $n_belakang = $row["nama_belakang"];
                            $gender = $row["gender"];

                            if ($gender == 'pria') {
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
                <span class="line"></span>
            </div>
        </div>
        <div class="bot">
            <div class="menu_list">
                <span>Tugas</span>
                <a class="" href="index.php"><i class="fa-solid fa-person"
                        style="margin: 0 25px 0 10px ;"></i>Pribadi</a>
                <a class="" href="tugas_tim.php"><i class="fa-solid fa-people-group" style="margin: 0 16px 0 5px ;">
                    </i>Tim</a>
            </div>
            <div class="menu_list">
                <span>Menu</span>
                <a class="" href="kalender.php"><i class="fa-solid fa-calendar-days" style="margin: 0 16px 0 5px ;">
                    </i>Kalender</a>
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
                        <a href="#" onclick="ToggleMenu()"><i class="fa-solid fa-user fa-lg"></i></a>
                </div>

            </div>
        </div>
    </div>

<!-- DETAIL PROFILE -->
    <div class="sub-menu-wrap" id="subMenu">
        <div class="sub-menu">
            <?php
            include "database.php"; // Pindahkan ini ke atas agar $kon dapat diakses dengan benar
            
            $id_user = $_SESSION['user_id'];

            $query = "SELECT * FROM `login` 
                INNER JOIN user ON login.id_user = user.id_user
                WHERE user.id_user = '$id_user'";

            $result = $kon->query($query);

            if ($result) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $nama_depan = $row["username"];
                        $gender = $row["gender"];
                        $tanggal_lahir = $row["tanggal_lahir"];
                        $tempat_lahir = $row["tempat_lahir"];
                        $alamat = $row["alamat"];
                    }
                }
            }

            if ($gender == 'pria') {
                $foto = 'img/male.png';
            } else {
                $foto = 'img/female.png';
            }

            echo "<div class='user-info'>
            <img src=$foto alt='foto-profil'>
            <h4>$nama_depan</h4>
        </div>
        <hr>
        <div class='sub-menu-link'>
            <p>$gender</p>
            <span></span>
        </div>
        <div class='sub-menu-link'>
            <p>$tanggal_lahir</p>
            <span></span>
        </div>
        <div class='sub-menu-link'>
            <p>$tempat_lahir</p>
            <span></span>
        </div>
        <div class='sub-menu-link'>
            <p>$alamat</p>
            <span></span>
        </div>";
            ?>
        </div>
    </div>
    <div class="nav-bg"></div>

<!-- UTAMA -->
    <div class="conten">
        <div class="profile-container">
            <div class="profile-info">
                <div class="profile-image-container">
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
                                    echo "<img src=$foto alt='Profile Picture' class='profile-picture'>";
                                }
                            } else {
                                echo "No results found.";
                            }
                        }
                        $kon->close();
                    ?>
                    
                </div>
                <div class="user-info">
                    <?php
                    $id_user = $_SESSION['user_id'];
                    include "database.php";

                    $qury = "SELECT `nama_depan`, `nama_tengah`, `nama_belakang`
                    FROM `login` 
                    INNER JOIN user ON login.id_user = user.id_user
                    WHERE user.id_user = '$id_user'";

                    $result = $kon->query($qury);

                    if ($result) {
                        // Tampilkan hasil filter
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $n_depan = $row["nama_depan"];
                                $n_tengah = $row["nama_tengah"];
                                $n_belakang = $row["nama_belakang"];

                                echo "<div ><h2>$n_depan $n_tengah $n_belakang</h2></div>";
                            }
                        } else {
                            echo "Unknow.";
                        }
                    }
                    $kon->close();
                    ?>
                </div>
            </div>

            <div class="task-details">
                
                <h2>Skala Tercapai</h2>
                <div class="container">
                    <div onclick="openPopupskala()" class="card">
                        <div class="card-content">
                            <?php
                            include "database.php";
                            $id_user = $_SESSION['user_id'];
                            // Lakukan query SELECT untuk menghitung total
                            $query_count = "SELECT SUM(completed) as total_completed
                                FROM (SELECT COUNT(*) as completed
                                        FROM tugas 
                                        WHERE status = 'selesai' AND id_user = '$id_user'
                                UNION ALL
                                    SELECT COUNT(*) as completed
                                    FROM tugas_tim 
                                    WHERE status = 'selesai' AND id_user = '$id_user'
                                ) as subquery";
                            $result_count = mysqli_query($kon, $query_count);

                            // Periksa apakah query berhasil dijalankan
                            if ($result_count) {
                                // Ambil hasil query
                                $row = mysqli_fetch_assoc($result_count);
                                $total_tugas = $row['total_completed'];

                                echo "<h2>$total_tugas</h2>";
                            } else {
                                echo "Gagal mengambil total tugas: " . mysqli_error($kon);
                            }

                            // Tutup koneksi database
                            mysqli_close($kon);
                            ?>
                            <p>Tugas Selesai</p>
                        </div>
                    </div>
                    
                    <div onclick="openPopupskala2()" class="card">
                        <div class="card-content">
                            <?php
                            include "database.php";
                            // Lakukan query SELECT untuk menghitung total
                            $id_user = $_SESSION['user_id'];
                            $query_count = "SELECT 
                            SUM(incomplete) as total_incomplete
                            FROM (SELECT COUNT(*) as incomplete 
                                    FROM tugas 
                                    WHERE status = 'tidak selesai' AND id_user = '$id_user'
                            UNION ALL
                                SELECT COUNT(*) as incomplete 
                                FROM tugas_tim 
                                WHERE status = 'tidak selesai' AND id_user = '$id_user'
                            ) as subquery";
                            $result_count = mysqli_query($kon, $query_count);

                            // Periksa apakah query berhasil dijalankan
                            if ($result_count) {
                                // Ambil hasil query
                                $row = mysqli_fetch_assoc($result_count);
                                $total_tugas = $row['total_incomplete'];

                                echo "<h2>$total_tugas</h2>";
                            } else {
                                echo "Gagal mengambil total tugas: " . mysqli_error($kon);
                            }

                            // Tutup koneksi database
                            mysqli_close($kon);
                            ?>

                            <p>Tugas tidak selesai</p>
                        </div>
                    </div>
                </div>

                <div class="chart-container" style="max-width: 1000px; max-height: 700px; margin: auto;">
                    <canvas id="combinedChart" width="200" height="120">
                    </canvas>
                </div>

            </div>
            <form method='post'>
                <button class="button" type='submit' name='hapus'>Reset</button>
            </form>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="ScriptJs/script_profil.js"></script>
<script src="https://kit.fontawesome.com/7b730c13ab.js" crossorigin="anonymous"></script>
<script src="ScriptJs/header.js"></script>
<script>
    let subMenu = document.getElementById("subMenu");

    function ToggleMenu() {
        subMenu.classList.toggle("open-menu");
    }
</script>
</body>

</html>