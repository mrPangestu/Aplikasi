<?php
include "../database.php";

// Mendapatkan data dari permintaan AJAX
$idUser = $kon->real_escape_string($_POST['id_user']);  // Melakukan perlindungan terhadap SQL injection
$idTugasTim = $kon->real_escape_string($_POST['id_tugas_tim']);

// Periksa apakah pengguna dengan id_user tertentu sudah ada dalam tabel tim
$checkDuplicateQuery = "SELECT COUNT(*) as count FROM tim WHERE id_user = '$idUser' AND id_tugas_tim = '$idTugasTim'";
$checkResult = $kon->query($checkDuplicateQuery);

$qury = "SELECT user.id_user
FROM user
INNER JOIN tugas_tim ON user.id_user = tugas_tim.id_user
WHERE tugas_tim.id_tugas_tim = '$idTugasTim'";

$result = $kon->query($qury);

// Inisialisasi variabel id_User
$id_User = null;

// Tampilkan hasil filter
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id_User = $row["id_user"];
        }
    }
} else {
    echo "Error: " . $kon->error;
}

if ($checkResult) {
    $row = $checkResult->fetch_assoc();
    $count = $row['count'];

    if ($count == 0 && $id_User != $idUser) {
        // Jika tidak ada duplikasi, lakukan penyisipan data baru
        $insertQuery = "INSERT INTO tim (id_user, id_tugas_tim) VALUES ('$idUser', '$idTugasTim')";
        $insertResult = $kon->query($insertQuery);

        if ($insertResult) {
            echo "Data berhasil ditambahkan";
        } else {
            echo "Error: " . $kon->error;
        }
    } else {
        echo "Pengguna sudah ada dalam tim untuk tugas ini.";
    }
} else {
    echo "Error: " . $kon->error;
}

$kon->close();
?>
