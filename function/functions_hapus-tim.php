<?php
include "../database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari permintaan AJAX
    $idTim = $_POST['id_tim'];

    // Jalankan query untuk menghapus data dari tabel 'tim'
    $deleteQuery = "DELETE FROM tim WHERE id_tim = '$idTim'";
    $deleteResult = $kon->query($deleteQuery);

    if ($deleteResult) {
        echo "Data berhasil dihapus";
    } else {
        echo "Error: " . $kon->error;
    }
} else {
    echo "Invalid request method";
}

$kon->close();
?>
