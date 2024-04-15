<tr>
    <th >ID</th>
    <th >Nama User</th>
    <th></th>
</tr>
    <?php
        session_start();
        include "../database.php";
        $idTugasTim = $_POST['id_tugas_tim'];
        $user_id = $_SESSION['user_id'];

        $qury = "SELECT  user.id_user, nama_depan, nama_tengah, nama_belakang
        FROM user
        INNER JOIN tugas_tim ON user.id_user = tugas_tim.id_user
        WHERE tugas_tim.id_tugas_tim = '$idTugasTim'";

        $result1 = $kon->query($qury);

        if ($result1){
        // Tampilkan hasil filter
            if ($result1->num_rows > 0) {
                while ($row = $result1->fetch_assoc()) {
                    $idUser = $row["id_user"];
                    $n_depan = $row["nama_depan"];
                    $n_tengah = $row["nama_tengah"];
                    $n_belakang = $row["nama_belakang"];

                    echo "<tr>
                            <td>$idUser</td>
                            <td>$n_depan $n_tengah $n_belakang</td>
                            <td>Pembuat</td>
                        </tr>";
                }
            
            }
        }

    $query = "SELECT id_tim, user.id_user, nama_depan, nama_tengah, nama_belakang, username, `gender`
            FROM tim
            INNER JOIN user ON tim.id_user = user.id_user
            INNER JOIN login ON tim.id_user = login.id_user
            INNER JOIN tugas_tim ON tim.id_tugas_tim = tugas_tim.id_tugas_tim
            WHERE tugas_tim.id_tugas_tim = '$idTugasTim'";

    $result = $kon->query($query);

    if ($result) {

        if ($idUser == $user_id) {
            $hapusTim = "hapus";
        } else {
            $hapusTim = "";
        }

        // Tampilkan hasil filter
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id_tim = $row["id_tim"];
                $id_user1 = $row["id_user"];
                $n_depan = $row["nama_depan"];
                $n_tengah = $row["nama_tengah"];
                $n_belakang = $row["nama_belakang"];

                echo "<tr>
                        <td>$id_user1</td>
                        <td>$n_depan $n_tengah $n_belakang</td>
                        <td><a href='#' class='hapus-link' data-idtim='$id_tim'>$hapusTim</a></td>
                    </tr>";
            }
        } 
    } else {
        echo "<tr><td colspan='5'>Error: " . $kon->error . "</td></tr>";
    }

    $kon->close();
?>

