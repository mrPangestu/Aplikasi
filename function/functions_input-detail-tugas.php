<?php
    include "../database.php";

    if (isset($_POST['id_tugas']) && isset($_POST['input_detail_tugas'])) {
        $id_tugas = $_POST['id_tugas'];
        $input_detail_tugas = $_POST['input_detail_tugas'];

        // Perform the insert operation
        $q_insert_detail = "INSERT INTO `detail_tugas` (`id_tugas`, `nama_tugas`) VALUES ('$id_tugas', '$input_detail_tugas')";
        $run_q_insert_detail = mysqli_query($kon, $q_insert_detail);

        if ($run_q_insert_detail) {
        header('Refresh:0.5; url=index.php');

        // Reset the input field(s) after a successful insert
        echo "<script>resetInputField();</script>";

        // Fetch and display the details immediately after insert
        $q_select_detail = "SELECT `id_detail_tugas`, `nama_tugas`, `status_tandai` FROM `detail_tugas` WHERE `id_tugas` = '$id_tugas'";
        $result_select_detail = mysqli_query($kon, $q_select_detail);

        if ($result_select_detail) {
            // Cek apakah hasil query tidak kosong
            if (mysqli_num_rows($result_select_detail) > 0) {

                // Variabel untuk menyimpan nomer berurutan
                $nomor = 1;

                $newHTML = "";

                // Ambil data dari hasil query
                while ($row = mysqli_fetch_assoc($result_select_detail)) {
                    $id_detail_tugas = $row['id_detail_tugas'];
                    $nama_tugas = $row['nama_tugas'];
                    $dicoret = $row['status_tandai'];
                
                    // Tambahkan tanda coret berdasarkan status di database
                    $style = '' ;

                    if ($dicoret == "1") {
                        $style = 'text-decoration: line-through; text-decoration-thickness: 3px; ' ;
                        $style_bg = ' background-color: #b5d5ff;' ;
                    } else {
                        $style_bg = ' background-color:none;' ;
                        $style = 'text-decoration: none;' ;
                    }
                
                    // Display the details as needed
                    echo "<table class='tabel-detail-content'>
                            <!-- Content Table -->
                            <tr id='baris_$id_detail_tugas' style='$style_bg'>
                                <td style='width:5%; '>$nomor</td>
                                <td style='text-align: left; $style' id='tugas_$id_detail_tugas'>$nama_tugas</td>
                                <td style='width:20%; '>
                                    <a href='javascript:void(1);' onclick='openPopupEditDetail($id_detail_tugas),resetInputField()'>Edit</a>
                                    |<a href='javascript:void(2);' onclick='hapusTugas($id_detail_tugas)'> Hapus</a>
                                    |<a href='javascript:void(3);' onclick='tandaiTugas($id_detail_tugas, $dicoret)'> Tandai</a>
                                </td>
                            </tr>
                        </table>";
                
                    // Inkrementasikan nomor untuk baris berikutnya
                    $nomor++;
                }

            }
        } else {
            echo "Error: " . $q_select_detail . "<br>" . mysqli_error($kon);
        }
        } else {
            echo "Error saat menambahkan detail tugas: " . mysqli_error($kon);
        }
    } 


    if (isset($_POST['id_tugas_tim']) && isset($_POST['input_detail_tugas_tim'])) {
        $id_tugas = $_POST['id_tugas_tim'];
        $input_detail_tugas = $_POST['input_detail_tugas_tim'];

        // Perform the insert operation
        $q_insert_detail = "INSERT INTO `detail_tugas_tim` (`id_tugas_tim`, `nama_tugas_tim`) VALUES ('$id_tugas', '$input_detail_tugas')";
        $run_q_insert_detail = mysqli_query($kon, $q_insert_detail);

        if ($run_q_insert_detail) {
        header('Refresh:0.5; url=index.php');

        // Reset the input field(s) after a successful insert
        echo "<script>resetInputField();</script>";

        // Fetch and display the details immediately after insert
        $q_select_detail = "SELECT `id_detail_tugas_tim`, `nama_tugas_tim`, `status_tandai` FROM `detail_tugas_tim` WHERE `id_tugas_tim` = '$id_tugas'";
        $result_select_detail = mysqli_query($kon, $q_select_detail);

        if ($result_select_detail) {
            // Cek apakah hasil query tidak kosong
            if (mysqli_num_rows($result_select_detail) > 0) {

                // Variabel untuk menyimpan nomer berurutan
                $nomor = 1;

                $newHTML = "";

                // Ambil data dari hasil query
                while ($row = mysqli_fetch_assoc($result_select_detail)) {
                    $id_detail_tugas = $row['id_detail_tugas_tim'];
                    $nama_tugas = $row['nama_tugas_tim'];
                    $dicoret = $row['status_tandai'];
                
                    // Tambahkan tanda coret berdasarkan status di database
                    $style = '' ;

                    if ($dicoret == "1") {
                        $style = 'text-decoration: line-through; text-decoration-thickness: 3px; ' ;
                        $style_bg = ' background-color: #b5d5ff;' ;
                    } else {
                        $style_bg = ' background-color:none;' ;
                        $style = 'text-decoration: none;' ;
                    }
                
                    // Display the details as needed
                    echo "<table class='tabel-detail-content'>
                            <!-- Content Table -->
                            <tr id='baris_$id_detail_tugas' style='$style_bg'>
                                <td style='width:5%; '>$nomor</td>
                                <td style='text-align: left; $style' id='tugas_$id_detail_tugas'>$nama_tugas</td>
                                <td style='width:20%; '>
                                    <a href='javascript:void(1);' style='color: rgb(79, 78, 174);' onclick='openPopupEditDetail($id_detail_tugas),resetInputField()'>Edit</a>
                                    <span>|</span><a href='javascript:void(2);' style='color: rgb(79, 78, 174);' onclick='hapusTugas($id_detail_tugas)'> Hapus</a>
                                    <span>|</span><a href='javascript:void(3);' style='color: rgb(79, 78, 174);' onclick='tandaiTugas($id_detail_tugas, $dicoret)'> Tandai</a>
                                </td>
                            </tr>
                        </table>";
                
                    // Inkrementasikan nomor untuk baris berikutnya
                    $nomor++;
                }

            }
        } else {
            echo "Error: " . $q_select_detail . "<br>" . mysqli_error($kon);
        }
        } else {
            echo "Error saat menambahkan detail tugas: " . mysqli_error($kon);
        }
    }
?>