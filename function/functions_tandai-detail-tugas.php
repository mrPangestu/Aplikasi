<?php
include "../database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_detail_tugas = isset($_POST['id_detail_tugas']) ? $_POST['id_detail_tugas'] : null;
    $is_dicoret = isset($_POST['is_dicoret']) && $_POST['is_dicoret'] === '1' ? true : false;
    
    $id_detail_tugas_tim = isset($_POST['id_detail_tugas_tim']) ? $_POST['id_detail_tugas_tim'] : null;
    $is_dicoret_tim = isset($_POST['is_dicoret_tim']) && $_POST['is_dicoret_tim'] === '1' ? true : false;

    // Update status_coret pada database berdasarkan nilai $is_dicoret
    $q_update_status = "UPDATE `detail_tugas` SET `status_tandai` = " . ($is_dicoret ? '1' : '0') . " WHERE `id_detail_tugas` = '$id_detail_tugas'";
    $result_update_status = mysqli_query($kon, $q_update_status);
    
    $q_update_status_tim = "UPDATE `detail_tugas_tim` SET `status_tandai` = " . ($is_dicoret_tim ? '1' : '0') . " WHERE `id_detail_tugas_tim` = '$id_detail_tugas_tim'";
    $result_update_status_tim = mysqli_query($kon, $q_update_status_tim);

    if ($result_update_status or $result_update_status_tim) {
        echo "success";
    } else {
        echo "Error: " . $q_update_status . "<br>" . mysqli_error($kon);
    }

    

    $kon->close();
} 
?>