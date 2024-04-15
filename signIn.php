<?php
include "database.php";

if(isset($_POST['add'])) {
    // Ambil data dari formulir
    $namaDepan = $_POST['n_depan'];
    $namaTengah = $_POST['n_tengah'];
    $namaBelakang = $_POST['n_belakang'];
    $gender = isset($_POST['gender']) ? ($_POST['gender'] == 'pria' ? 'pria' : 'wanita') : 'wanita';
    $tempatLahir = $_POST['t_lahir'];
    $tanggalLahir = $_POST['tgl_lahir'];
    $alamat = $_POST['al'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Format tanggal lahir sesuai dengan format yang diterima MySQL
    $dob = date('Y-m-d', strtotime($tanggalLahir));

    // Validasi username
    $validasi = "SELECT username FROM login";
    $query_Validasi = mysqli_query($kon, $validasi);

    // Buat array untuk menyimpan semua username yang ada di database
    $existingUsernames = array();
    while ($row = mysqli_fetch_assoc($query_Validasi)) {
        $existingUsernames[] = $row['username'];
    }

    // Periksa apakah username sudah ada di database
    if (in_array($username, $existingUsernames)) {
        echo "<div id='customAlert' class='custom-alert'></div>";
        header('refresh:3');
    } else {
        // Query INSERT ke tabel user
        $q_insert_user = "INSERT INTO `user` (`nama_depan`, `nama_tengah`, `nama_belakang`, `gender`, `tempat_lahir`, `tanggal_lahir`, `alamat`) VALUES (
            '$namaDepan',
            '$namaTengah',
            '$namaBelakang',
            '$gender',
            '$tempatLahir',
            '$dob',
            '$alamat')";

        // Eksekusi query
        $run_q_insert_user = mysqli_query($kon, $q_insert_user);

        // Periksa hasil eksekusi query
        if ($run_q_insert_user) {
            $id_user = mysqli_insert_id($kon);
            $insert_login = "INSERT INTO login (id_user, username, password) VALUES (
                '$id_user',
                '$username',
                '$password')";
            $run_insert_login = mysqli_query($kon, $insert_login);
        
            if ($run_insert_login) {
                $default_categories = ['Harian', 'Kerja', 'Wishlist', 'Hari penting'];
        
                foreach ($default_categories as $category_name) {
                    $q_insert_default_category = "INSERT INTO kategori (id_user, nama_kategori) VALUES ($id_user, '$category_name')";
                    $result_insert_default_category = mysqli_query($kon, $q_insert_default_category);
        
                    if (!$result_insert_default_category) {
                        echo "Gagal menambahkan kategori default.";
                        die("Error executing query: " . $kon->error);
                    }
                }
        
                header('Refresh:0; url=login.php');
            } else {
                echo "Error: " . mysqli_error($kon) . " Query: " . $insert_login;
        
                // Hapus pengguna yang baru saja dimasukkan jika gagal masuk
                $user_delete = "DELETE FROM `user` WHERE id_user = '$id_user'";
                $query_user_delete = mysqli_query($kon, $user_delete);
                if (!$query_user_delete) {
                    echo "Gagal menghapus pengguna yang baru saja dimasukkan.";
                    die("Error executing query: " . $kon->error);
                }
            }
        } else {
            die("Error: " . mysqli_error($kon) . " Query: " . $q_insert_user);
        }
        
    }
}

$kon->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="StyleCss/style-signin.css">
    <style>
    /* Styling untuk pesan alert */
    .custom-alert {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: aliceblue;
        color: white;
        padding: 15px;
        border-radius: 5px;
        display: none;
        color: black;
    }
    </style>
</head>
<body>
    <div class="back">
            <button class="button" onclick="window.history.back();"><i class="fa-solid fa-left-long"></i></button>
        </div>
    <div class="container">
        <div class="title"><h4>Sign up</h4></div>
        <div class="content">
            <form action="#" method="post">
                <div class="angka">
                    <h1>1</h1>
                <div>
                <div class="user-details ">
                    <div class="input-inbox">
                        <span class="details">Nama Depan</span>
                        <input type="text" placeholder="Masukan Nama Depan" name="n_depan" required>
                    </div>
                    <div class="input-inbox">
                        <span class="details">Nama Tengah</span>
                        <input type="text" placeholder="Masukan Nama Tengah" name="n_tengah">
                    </div>
                    <div class="input-inbox">
                        <span class="details">Nama Belakang</span>
                        <input type="text" placeholder="Masukan Nama Belakang" name="n_belakang">
                    </div>
                    <div class="gender-details">
                        <span style="margin-left: 80px;">Gender</span>
                        <div class="category">
                            <input type="radio" name="gender" value="pria" checked>
                                <label for="pria" style="margin-right: 50px;">Pria</label>
                            <input type="radio" name="gender" value="wanita">
                                <label for="wanita">Wanita</label>
                        </div>

                    </div>
                </div>
                <div class="angka">
                    <h1>2</h1>
                <div>   
                <div class="user-details ">
                    <div class="input-inbox">
                        <span class="details">Tempat Lahir</span>
                        <input type="text" placeholder="Masukan Tempat Lahir" name="t_lahir" required>
                    </div>
                    <div class="input-inbox">
                        <span class="details">Tanggal Lahir</span>
                        <input type="date" placeholder="Masukan Tanggal Lahir" name="tgl_lahir" required>
                    </div>
                    <div class="input-inbox">
                        <span class="details">Alamat</span>
                        <textarea placeholder="Masukan Alamat" cols="30" rows="3" name="al"></textarea>
                    </div>
                </div>   
                <div class="angka">
                    <h1>3</h1>
                <div>
                <div class="user-details ">    
                    <div class="input-inbox">
                        <span class="details">Username</span>
                        <input type="text" placeholder="Masukan Username" name="username" minlength="5" maxlength="15" required>
                    </div>
                    <div class="input-inbox">
                        <span class="details">Password</span>
                        <input type="password" placeholder="Masukan Password" name="password" minlength="8" maxlength="15" required>
                    </div>
                    <div class="input-inbox">
                        <span class = "line"></span>
                    </div>
                </div>
                <div class="button">
                    <input type="submit" value="Daftar" name="add">
                </div>
            </form>
        </div>        
    </div> 
</body>
<script>
// Fungsi kustom untuk menampilkan pesan alert
function showCustomAlert(message, duration) {
  var alertDiv = document.getElementById("customAlert");
  alertDiv.innerHTML = message;
  alertDiv.style.display = "block";
  // Sembunyikan pesan setelah durasi tertentu
  setTimeout(function(){
    alertDiv.style.display = "none";
  }, duration);
}

// Contoh penggunaan fungsi
showCustomAlert("Username sudah ada.", 3000); // Menampilkan pesan selama 3 detik
</script>
<script src="https://kit.fontawesome.com/7b730c13ab.js" crossorigin="anonymous"></script>
</html>