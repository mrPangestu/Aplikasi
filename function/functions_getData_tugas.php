<?php
// getData.php

// Lakukan koneksi ke database atau sumber data lainnya
include "../database.php";

session_start();
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
    header("Location: ../login.php");
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
        header("Location: ".$_SERVER['PHP_SELF']); // Redirect ke halaman yang sama setelah logout
        exit();
    }
} else {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Lakukan query SELECT untuk mendapatkan data yang dibutuhkan
$id_user = $_SESSION['user_id'];
$query = "SELECT 
SUM(completed) as total_completed, 
SUM(incomplete) as total_incomplete
FROM (
    SELECT COUNT(*) as completed, 0 as incomplete 
    FROM tugas 
    WHERE status = 'selesai' AND id_user = '$id_user'
    UNION ALL
    SELECT 0 as completed, COUNT(*) as incomplete 
    FROM tugas 
    WHERE status = 'tidak selesai' AND id_user = '$id_user'
    UNION ALL
    SELECT COUNT(*) as completed, 0 as incomplete 
    FROM tugas_tim 
    WHERE status = 'selesai' AND id_user = '$id_user'
    UNION ALL
    SELECT 0 as completed, COUNT(*) as incomplete 
    FROM tugas_tim 
    WHERE status = 'tidak selesai' AND id_user = '$id_user'
) as subquery";
$result = mysqli_query($kon, $query);

// Periksa apakah query berhasil dijalankan
if ($result) {
    // Ambil hasil query
    $row = mysqli_fetch_assoc($result);

    // Keluarkan hasil dalam format JSON
    echo json_encode($row);
} else {
    echo "Gagal mendapatkan data: " . mysqli_error($kon);
}

// Tutup koneksi database
mysqli_close($kon);
?>
