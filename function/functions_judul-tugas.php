<?php

include "../database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil nilai id_tugas dari data POST
    $id_tugas = isset($_POST['id_detail_tugas']) ? $_POST['id_detail_tugas'] : null;
    $id_tugas_tim = isset($_POST['id_detail_tugas_tim']) ? $_POST['id_detail_tugas_tim'] : null;

    $query = "SELECT `nama_tugas` FROM tugas WHERE `id_tugas` = '$id_tugas'";
        $result = $kon->query($query);

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nama_tugas = $row['nama_tugas'];

                    echo "<h1 style='margin:10px 0 0 50px; padding:0; color:white;'>$nama_tugas</h1>";
                }
            } 
        }
                
    $query_tim = "SELECT `nama_tugas` FROM tugas_tim WHERE `id_tugas_tim` = '$id_tugas_tim'";
        $result_tim = $kon->query($query_tim);

        if ($result_tim) {
            if ($result_tim->num_rows > 0) {
                while ($row = $result_tim->fetch_assoc()) {
                    $nama_tugas_tim = $row['nama_tugas'];

                    echo "<h1 style='margin:10px 0 0 50px; padding:0; color:white;'>$nama_tugas_tim</h1>";
                }
            } 
        } 

    $kon->close();
} 
?>
