<?php
session_start();
include "../database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_SESSION['user_id'];
    $username = $_POST['username'];
    $n_depan = $_POST['firstName'];
    $n_tengah = $_POST['middleName'];
    $n_belakang = $_POST['lastName'];
    $gender = $_POST['gender'];
    $tempat_lahir = $_POST['placeOfBirth'];
    $tanggal_lahir = $_POST['dateOfBirth'];
    $alamat = $_POST['address'];

    // Mengambil data lama untuk memeriksa perubahan
    $q_get_old_data = "SELECT `nama_depan`, `nama_tengah`, `nama_belakang`, `gender`, `tempat_lahir`, `tanggal_lahir`, `alamat`
        FROM `user` WHERE id_user = '$id_user'";
    $result_old_data = $kon->query($q_get_old_data);

    if ($result_old_data->num_rows > 0) {
        $row_old = $result_old_data->fetch_assoc();
        $old_n_depan = $row_old["nama_depan"];
        $old_n_tengah = $row_old["nama_tengah"];
        $old_n_belakang = $row_old["nama_belakang"];
        $old_gender = $row_old["gender"];
        $old_tempat_lahir = $row_old["tempat_lahir"];
        $old_tanggal_lahir = $row_old["tanggal_lahir"];
        $old_alamat = $row_old["alamat"];

        // Memeriksa perubahan data
        $changes = array();
        if ($n_depan !== $old_n_depan) {
            $changes[] = "Nama Depan";
        }
        if ($n_tengah !== $old_n_tengah) {
            $changes[] = "Nama Tengah";
        }
        if ($n_belakang !== $old_n_belakang) {
            $changes[] = "Nama Belakang";
        }
        if ($gender !== $old_gender) {
            $changes[] = "Jenis Kelamin";
        }
        if ($tempat_lahir !== $old_tempat_lahir) {
            $changes[] = "Tempat Lahir";
        }
        if ($tanggal_lahir !== $old_tanggal_lahir) {
            $changes[] = "Tanggal Lahir";
        }
        if ($alamat !== $old_alamat) {
            $changes[] = "Alamat";
        }

        // Memperbarui data jika ada perubahan
        if (!empty($changes)) {
            $q_update_user = "UPDATE `user` SET 
                `nama_depan` = '$n_depan',
                `nama_tengah` = '$n_tengah',
                `nama_belakang` = '$n_belakang',
                `gender` = '$gender',
                `tempat_lahir` = '$tempat_lahir',
                `tanggal_lahir` = '$tanggal_lahir',
                `alamat` = '$alamat'
                WHERE id_user = '$id_user'";

            if ($kon->query($q_update_user) === TRUE) {
                // Berhasil memperbarui data
                echo "Data anda berhasil diperbarui!!";
            } else {
                // Gagal memperbarui data
                echo "Error: " . $q_update_user . "<br>" . $kon->error;
            }
        } else {
            // Tidak ada perubahan data
            echo "Tidak ada perubahan data.";
        }
    } else {
        // Data lama tidak ditemukan
        echo "Data lama tidak ditemukan.";
    }

    $kon->close();
} else {
    // Jika akses langsung ke file ini tanpa melalui POST request
    echo "Akses tidak sah!";
}
?>
