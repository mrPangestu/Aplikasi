<?php
include "../database.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_detail_tugas = isset($_POST['id_detail_tugas']) ? $_POST['id_detail_tugas'] : null;
    $new_task_name = isset($_POST['new_task_name']) ? $_POST['new_task_name'] : null;

    $id_detail_tugas_tim = isset($_POST['id_detail_tugas_tim']) ? $_POST['id_detail_tugas_tim'] : null;
    $new_task_name_tim = isset($_POST['new_task_name_tim']) ? $_POST['new_task_name_tim'] : null;

    // Update the task name in the database
    $q_update_task_tim = "UPDATE `detail_tugas_tim` SET `nama_tugas_tim` = '$new_task_name_tim' WHERE `id_detail_tugas_tim` = '$id_detail_tugas_tim'";
    $result_update_task_tim = mysqli_query($kon, $q_update_task_tim);
    
    $q_update_task = "UPDATE `detail_tugas` SET `nama_tugas` = '$new_task_name' WHERE `id_detail_tugas` = '$id_detail_tugas'";
    $result_update_task = mysqli_query($kon, $q_update_task);

    if ($result_update_task or $result_update_task_tim) {
        // Include the additional file
        echo "success";
    } else {
        echo "Error: " . $q_update_task . "<br>" . mysqli_error($kon);
    }


    $kon->close();
}
?>