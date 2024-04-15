<?php

  include "database.php";

  if ($kon->connect_error) {
    die("Koneksi Gagal: " . $kon->connect_error);
  }
  session_start();

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

  // Fungsi untuk menangani proses logout
  function logoutUser()
  {
    session_unset();
    session_destroy();
  }

  // Cek apakah pengguna sudah login
  if (isUserLoggedIn()) {
    // Jika sudah login, tampilkan nama pengguna
    $user_id = $_SESSION['user_id'];
    $username = getUserName($kon, $user_id);

    // Jika tombol logout ditekan
    if (isset($_POST['logout'])) {
      logoutUser();
      header("Location: " . $_SERVER['PHP_SELF']); // Redirect ke halaman yang sama setelah logout
      exit();
    }
  } else {
    // Jika belum login, tampilkan formulir login
    if (isset($_POST['buat'])) {
      // Jika formulir login disubmit
      $login_username = $_POST["username"];
      $login_password = $_POST["password"];

      $sql = "SELECT id_user FROM login WHERE username = '$login_username' AND password = '$login_password'";
      $result = $kon->query($sql);

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id_user'];
        header("Location: index.php"); // Redirect ke halaman yang sama setelah login
        exit();
      } else {
        echo "<div id='customAlert' class='custom-alert'></div>";
        header('refresh:3');
      }
    }
    // Tampilkan formulir login
  }
  if (isset($_POST['ganti-pw'])) {
    $username_pw = $_POST['pw-username'];
    $tanggal_lahir = date('Y-m-d', strtotime($_POST['tanggal_lahir']));
    $new_password = $_POST['new_password'];

    $q_ubah_password = "UPDATE `login` 
                        INNER JOIN `user` ON `user`.`id_user` = `login`.`id_user` 
                        SET `password` = '$new_password' 
                        WHERE `username` = '$username_pw' AND `tanggal_lahir` = '$tanggal_lahir'";
    $result_ubah_password = mysqli_query($kon, $q_ubah_password);
    
  
    if ($result_ubah_password) {
      header('refresh');
    } else {
      if (mysqli_errno($kon) == 0) {
          // Tidak ada kesalahan MySQL, tetapi tidak ada baris yang terpengaruh
          echo "Username tidak ditemukan atau tanggal lahir salah.";
      } else {
          echo "Error: " . $q_ubah_password . "<br>" . mysqli_error($kon);
      }
  }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="StyleCss/login.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: url('img/1.jpg');
    background-size: cover;
    background-position: center;
    }   


  </style>
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

  <?php
  if (isset($username)) {
      // Jika pengguna sudah login
      echo "<p>Selamat datang, $username! 
            <form method='post'>
              <button type='submit' name='logout'>Logout</button>
            </form></p>";
  }
  ?>
  
 <div class="wrapper">
    <div class="form-wrapper login">
      <form action="" method='post'>
        <h1>LOGIN</h1>

        <div class="input-box">
          <i class='bx bxs-user'></i>
          <input type="text" placeholder="Masukan Username" name='username' required>
        </div>

        <div class="input-box">
          <i class='bx bxs-lock-alt'></i>
          <input id="password" type="password" placeholder="Masukan Password" name='password' required>
          <i class="toggle-password fas fa-eye" id="toggle-password" onclick="togglePasswordVisibility()"></i>
        </div>

        <div class="remember-forgot">
          <label><input type="checkbox">Remember me</label>
          <a href="#" class="lp-btn">Lupa Password?</a>
        </div>

        <button type="submit" class="btn" name="buat">Login</button>

        <div class="register-link">
          <p>Tidak mempunyai akun? <a href="signIn.php">Registrasi</a></p>
        </div>
      </form>
    </div>

    <div class="form-wrapper lp">
      <form action="" method='post'>
        <h1>Ubah Password</h1>

        <div class="input-box">
          <i class='bx bxs-user'></i>
          <input type="text" placeholder="Masukan Username" name='pw-username' required>
        </div>

        <div class="input-box">
          <i class='bx bxs-user'></i>
          <input type="date" name='tanggal_lahir' required>
        </div>

        <div class="input-box">
          <i class='bx bxs-lock-alt'></i>
          <input id="new_password" type="text" placeholder="Masukan Password Baru" name='new_password' minlength="8" maxlength="15" required>
        </div>

        <div class="remember-forgot">
          <a href="#" class="login-btn" id="lg-btn">Login</a>
        </div>

        <button type="submit" class="btn" name="ganti-pw">Submit</button>
      </form>
    </div>
  </div>


  <script>
    // FUNGSI SHOW & HIDE PASSWORD
      function togglePasswordVisibility() {
          var passwordField = document.getElementById("password");
          var icon = document.querySelector(".toggle-password");

          // Ganti tipe input antara "password" dan "text"
          if (passwordField.type === "password") {
              passwordField.type = "text";
              icon.classList.remove("fa-eye");
              icon.classList.add("fa-eye-slash");
          } else {
              passwordField.type = "password";
              icon.classList.remove("fa-eye-slash");
              icon.classList.add("fa-eye");
          }
      }
    // ============================== //
    const loginbtn = document.querySelector('.lp-btn');
const loginlp = document.querySelector('.login-btn');
const wrapper = document.querySelector('.wrapper');

loginbtn.addEventListener('click', () => {
    wrapper.classList.toggle('active');
});

loginlp.addEventListener('click', () => {
    wrapper.classList.toggle('active');
});
    // ============================== //
  </script>
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
showCustomAlert("Login Gagal, Silahkan coba lagi.", 5000); // Menampilkan pesan selama 3 detik
</script>
<script src="https://kit.fontawesome.com/7b730c13ab.js" crossorigin="anonymous"></script>

</body>
</html>
<?php
$kon->close();
?>