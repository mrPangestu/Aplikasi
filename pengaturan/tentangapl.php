<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Aplikasi - TodoTune</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f9fa; /* Background color */
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

        .container {
            background-color: #ffffff; /* Set background color for the content */
            padding: 60px;
            border-radius: 20px; /* Add rounded corners */
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2); /* Add a subtle box shadow */
            text-align: center;
            max-width: 1080px;
            width: 100%;
        }

        h1 {
            color: #336699;
            margin-bottom: 30px;
            font-size: 3em; /* Adjust the font size */
        }

        p {
            line-height: 1.8;
            color: #555; /* Adjust the text color */
            margin-bottom: 30px;
        }

        img {
            max-width: 50%; /* Perkecil ukuran gambar */
            height: auto;
            margin-top: 30px;
            border-radius: 50%; /* Make the image circular */
            transition: transform 0.3s ease-in-out;
        }

        img:hover {
            transform: scale(1.1); /* Add hover effect */
        }

        /* New styles for the profile section */
        .profile-section {
            margin-top: 50px;
            display: flex;
            align-items: center;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .profile-card {
            width: 200px;
            margin: 20px;
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            background-color: #fff; /* Card background color */
            overflow: hidden;
        }

        .profile-card:hover {
            transform: scale(1.05);
        }

        .profile-image {
            width: 100px;
            height: 100px;
            overflow: hidden;
            margin-bottom: 15px; /* Adjusted margin */
            margin: 0 auto;
            border-radius: 50%; /* Make the image circular */
            background: blue;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 1.2em;
            color: #336699;
            margin-bottom: 10px; /* Adjusted margin */
        }

        .profile-description {
            color: #555;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        /* New styles for the enhanced title */
        .enhanced-title {
            font-family: 'Arial', sans-serif; /* Ganti dengan font yang diinginkan */
            font-size: 2.5em;
            color: #336699;
            position: relative;
            display: inline-block;
            margin-top: 50px;
            margin-bottom: 20px;
        }

        .enhanced-title::before,
        .enhanced-title::after {
            content: '';
            position: absolute;
            width: 40%;
            height: 2px;
            background-color: #336699;
            top: 50%;
            transform: translateY(-50%);
        }

        .enhanced-title::before {
            right: 100%;
            margin-right: 10px;
        }

        .enhanced-title::after {
            left: 100%;
            margin-left: 10px;
        }

        .enhanced-title span {
            background-color: #ffffff;
            padding: 0 20px;
            z-index: 1;
        }

        /* New styles for the copyright */
        .copyright {
            margin-top: 50px;
            font-size: 0.8em;
            color: #555;
        }

        /* New styles for the "Pembuat Aplikasi" section */
        .pembuat-aplikasi {
            font-size: 1.5em;
            color: #336699;
            margin-top: 50px;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .pembuat-aplikasi::before,
        .pembuat-aplikasi::after {
            content: '';
            position: absolute;
            width: 40%;
            height: 2px;
            background-color: #336699;
            top: 50%;
            transform: translateY(-50%);
        }

        .pembuat-aplikasi::before {
            right: 100%;
            margin-right: 10px;
        }

        .pembuat-aplikasi::after {
            left: 100%;
            margin-left: 10px;
        }

        .pembuat-aplikasi span {
            background-color: #ffffff;
            padding: 0 20px;
            z-index: 1;
        }
    </style>
</head>

<body>
<div class="back">
            <button class="button" onclick="window.location.href='../pengaturan.php'"><i class="fa-solid fa-left-long"></i></button>
        </div>
    <div class="container">
        <h1>TodoTune</h1>
        <p>TodoTune adalah aplikasi yang membantu penggunanya mengelola tugas-tugas yang berkaitan dengan pekerjaan.
            Aplikasi ini dapat membantu penggunanya untuk mencatat semua tugas yang harus dikerjakan, mengatur kategori
            tugas, mengatur tenggat waktu tugas, mengingat tugas yang harus dikerjakan, dan berkolaborasi dengan rekan
            kerja.
            Aplikasi todo list pekerjaan dapat digunakan oleh siapapun, misalnya mahasiswa, siswa, dan berbagai jenis
            pekerja, mulai dari karyawan kantoran, freelancer, hingga pekerja lepas. Aplikasi ini dapat membantu
            penggunanya untuk tetap fokus pada tugas-tugas yang penting dan menghindari keterlambatan dalam
            menyelesaikan tugas.</p>
        <img src="img/list.png" alt="logo">
        <p>Tujuan pembuatan aplikasi TodoTune adalah untuk membantu pengguna
            dalam mengatur dan menyelesaikan tugas-tugas yang harus dikerjakan dalam satu hari,
            minggu, bulan, atau periode waktu tertentu, menghindari keterlambatan dalam
            menyelesaikan tugas, dan untuk meningkatkan produktivitas dan efektivitas pengguna
            dalam bekerja.</p>

        <!-- Enhanced Title: Pembuat Aplikasi -->
        <h4 class="pembuat-aplikasi"><span>Pembuat Aplikasi</span></h4>
        <p>Aplikasi TodoTune dibuat oleh Kelompok 4. Berikut anggotanya:</p>

        <!-- Profile Section -->
        <div class="profile-section">
            <!-- Profile 1 -->
            <div class="profile-card">
                <img src="img/foto3.jpg" alt="Profile Image 1">
                <div class="profile-name">Rifqi Rafif</div>
                <div class="profile-description">Anggota</div>
            </div>

            <!-- Profile 2 -->
            <div class="profile-card">
                <img src="img/foto4.jpg" alt="Profile Image 2">
                <div class="profile-name">Muhammad Fazriel</div>
                <div class="profile-description">Anggota</div>
            </div>

            <!-- Profile 3 -->
            <div class="profile-card">
                <img src="img/foto2.jpg" alt="Profile Image 3">
                <div class="profile-name">M Rifki Pangestu</div>
                <div class="profile-description">Ketua</div>
            </div>

            <!-- Profile 4 -->
            <div class="profile-card">
                <img src="img/foto1.jpg" alt="Profile Image 4">
                <div class="profile-name">Aulia Putri</div>
                <div class="profile-description">Anggota</div>
            </div>
        </div>

        <!-- Copyright Section -->
        <div class="copyright">
            &copy; 2024 TodoTune. All Rights Reserved.
        </div>
    </div>
</body>
<script src="https://kit.fontawesome.com/7b730c13ab.js" crossorigin="anonymous"></script>

</html>