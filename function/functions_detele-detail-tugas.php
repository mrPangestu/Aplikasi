<?php
include "../database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_detail_tugas = isset($_POST['id_detail_tugas']) ? $_POST['id_detail_tugas'] : null;
    $id_detail_tugas_tim = isset($_POST['id_detail_tugas_tim']) ? $_POST['id_detail_tugas_tim'] : null;

    // Delete the task from the database
    $q_delete_task = "DELETE FROM `detail_tugas` WHERE `id_detail_tugas` = '$id_detail_tugas'";
    $result_delete_task = mysqli_query($kon, $q_delete_task);

    $q_delete_task_tim = "DELETE FROM `detail_tugas_tim` WHERE `id_detail_tugas_tim` = '$id_detail_tugas_tim'";
    $result_delete_task_tim = mysqli_query($kon, $q_delete_task_tim);

    if ($result_delete_task or $result_delete_task_tim) {
        echo "success";
        
        // Output a success message
    } else {
        echo "Error: " . $q_delete_task . "<br>" . mysqli_error($kon);
    }

    $kon->close();
} else {
    echo 'Metode request tidak valid.';
}
?>