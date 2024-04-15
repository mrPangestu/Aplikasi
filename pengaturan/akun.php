<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ecf0f1;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .back .button{
            display: flex;
            position: absolute;
            background-color: #99ccff;
            color: #336699;
            padding: 5px 10px;
            border-radius: 15px;
            border: none;
            font-size: 25px;
            cursor: pointer;
            top: 10px;
            left: 10px;
            
        }
        .back .button:hover {
            scale: 1.05;
            background-color: #336699;
            color: aliceblue;
        }

        form {
            background: #3498db;
            padding: 80px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            color: #fff;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 20px; 
        }

        h1 {
            text-align: center;
            width: 100%;
            margin-bottom: 30px;
            color: #fff;
        }

        label {
            display: block;
            margin-bottom: 10px;
            width: 100%;
            color: #fff;
        }

        input,
        select,
        textarea {
            width: calc(100% - 22px); 
            padding: 12px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 1px solid #2980b9;
            border-radius: 6px;
            background-color: #ecf0f1;
            color: #333;
            transition: border-color 0.3s, background-color 0.3s, color 0.3s;
            float: left;
            margin-right: 10px; 
        }

        input:last-child,
        select:last-child,
        textarea:last-child {
            margin-bottom: 0;
            margin-right: 0; 
        }

        .edit-button,
        .save-button {
            background-color: #fff;
            color: #3498db;
            padding: 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: calc(100% - 22px);
            transition: background-color 0.3s, color 0.3s;
            clear: both;
            display: block;
            margin-top: 20px;
        }

        .save-button {
            display: none;
        }

        @media screen and (max-width: 600px) {
            input,
            select,
            textarea {
                width: 100%;
                margin-right: 0;
            }
        }
    </style>
</head>

<body>
<div class="back">
            <button class="button" onclick="window.location.href='../pengaturan.php'"><i class="fa-solid fa-left-long"></i></button>
        </div>
    <?php
    session_start();
    $id_user = $_SESSION['user_id'];
    include "../database.php";

    $qury = "SELECT `username`, `nama_depan`, `nama_tengah`, `nama_belakang`, `gender`, `tempat_lahir`, `tanggal_lahir`, `alamat`
        FROM `login` 
        INNER JOIN user ON login.id_user = user.id_user
        WHERE user.id_user = '$id_user'";

    $result = $kon->query($qury);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $username = $row["username"];
                $n_depan = $row["nama_depan"];
                $n_tengah = $row["nama_tengah"];
                $n_belakang = $row["nama_belakang"];
                $gender = $row["gender"];
                $tempat_lahir = $row["tempat_lahir"];
                $tanggal_lahir = $row["tanggal_lahir"];
                $alamat = $row["alamat"];
            }
        } else {
            echo "Unknown.";
        }
    }
    $kon->close();
    ?>

    <form id="accountForm">
        <h1>AKUN</h1>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>

        <label for="firstName">Nama Depan:</label>
        <input type="text" id="firstName" name="firstName" value="<?php echo $n_depan; ?>" required>

        <label for="middleName">Nama Tengah:</label>
        <input type="text" id="middleName" name="middleName" value="<?php echo $n_tengah; ?>">

        <label for="lastName">Nama Belakang:</label>
        <input type="text" id="lastName" name="lastName" value="<?php echo $n_belakang; ?>" required>

        <label for="gender">Jenis Kelamin:</label>
        <input type="text" id="gender" name="gender" value="<?php echo $gender; ?>" required>

        <label for="placeOfBirth">Tempat Lahir:</label>
        <input type="text" id="placeOfBirth" name="placeOfBirth" value="<?php echo $tempat_lahir; ?>" required>

        <label for="dateOfBirth">Tanggal Lahir:</label>
        <input type="text" id="dateOfBirth" name="dateOfBirth" value="<?php echo $tanggal_lahir; ?>" required>

        <label for="address">Alamat:</label>
        <textarea id="address" name="address" rows="4" required><?php echo $alamat; ?></textarea>

        <button type="button" class="edit-button" onclick="enableEdit()">Ubah Data</button>
        <button type="button" class="save-button" onclick="updateProfile()">Simpan Perubahan</button>
    </form>
    <script>
        function enableEdit() {
            var inputs = document.querySelectorAll('input, select, textarea');
            var saveButton = document.querySelector('.save-button');
            var editButton = document.querySelector('.edit-button');

            inputs.forEach(function (input) {
                input.style.pointerEvents = 'auto';
            });

            saveButton.style.display = 'block';
            editButton.style.display = 'none';
        }

        function updateProfile() {
            var formData = new FormData();
            formData.append('username', document.getElementById('username').value);
            formData.append('firstName', document.getElementById('firstName').value);
            formData.append('middleName', document.getElementById('middleName').value);
            formData.append('lastName', document.getElementById('lastName').value);
            formData.append('gender', document.getElementById('gender').value);
            formData.append('placeOfBirth', document.getElementById('placeOfBirth').value);
            formData.append('dateOfBirth', document.getElementById('dateOfBirth').value);
            formData.append('address', document.getElementById('address').value);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_profile.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                } else {
                    console.error(xhr.responseText);
                }
            };
            xhr.send(formData);
        }
    </script>
</body>
<script src="https://kit.fontawesome.com/7b730c13ab.js" crossorigin="anonymous"></script>
</html>
