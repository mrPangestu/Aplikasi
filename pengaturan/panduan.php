<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f0f0f0;
            color: #333;
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

        h1 {
            color: black;
            text-align: center;
            animation: blink 1s 1;
        }

        @keyframes blink {
            0%, 50%, 100% {
                opacity: 1;
            }
            25%, 75% {
                opacity: 0;
            }
        }

        h2 {
            color: black;
        }

        p {
            line-height: 1.6;
            color: #555;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 1130px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        /* Media Queries for Responsiveness */
        @media screen and (max-width: 600px) {
            .container {
                padding: 10px;
            }

            h1 {
                font-size: 24px;
            }

            h2 {
                font-size: 20px;
            }

            p {
                font-size: 16px;
            }

            img {
                margin-bottom: 10px;
            }
        }
    </style>
    <title>Panduan pengguna</title>
</head>
<body>
<div class="back">
            <button class="button" onclick="window.location.href='../pengaturan.php'"><i class="fa-solid fa-left-long"></i></button>
        </div>
    <div class="container">
        <h1>Panduan pengguna</h1>
        <br>
        <p>Berikut adalah panduan untuk menggunakan aplikasi TodoTune</p>

        <h2>1. Login/Daftar</h2>
        <p>Jika Anda sudah memiliki akun, anda bisa langsung mengisi username dan password pada halaman login ini.</p>
        <img src="img/login.png" alt="Login">

        <p>Namun, jika Anda belum memiliki akun, anda bisa mengklik registrasi. Pada laman registrasi, anda harus
            memasukan beberapa data seperti nama, tempat lahir, tanggal lahir, alamat, dan gender. Berikut adalah
            tampilannya:</p>
        <img src="img/daftar.jpg" alt="Registrasi">

        <h2>2. Tampilan awal</h2>
        <p>Setelah selesai login/daftar akan muncul tampilan awal seperti ini: </p>
        <img src="img/tampilan-awal.png" alt="Tampilan-awal">
        <p>Pada tampilan awal ini terdapat sidebar yang menampilkan foto profil, nama pengguna, menu tugas pribadi, menu
            tugas tim, menu kalender, menu pengaturan, dan logout. Di tampilan awal juga terdapat waktu dan tanggal saat
            ini, notifikasi, profil, kategori tugas, tombol untuk menambah kategori, dan tombol untuk menambah tugas.
        </p>
        <img src="img/tampilanawal2.png" alt="Tampilan-awal2">

        <h2>3. Menambahkan tugas (pribadi/tim)</h2>
        <p>Saat ingin menambahkan tugas pribadi anda bisa mengklik menu tugas pribadi pada sidebar. Saat ingin
            menambahkan tugas tim anda bisa mengklik menu tugas tim pada sidebar. Pada keduanya cara untuk menambahkan
            tugas sama, yaitu dengan mengklik tombol + yang terdapat di ujung kanan bawah. Setelah
            diklik, anda bisa memilih kategori, memasukan nama tugas, memasukan tenggat waktu untuk tugas, menentukan
            apakah akan mengaktifkan notifikasi untuk tugas tersebut/tidak, dan menentukan apakah tugas ini akan diulang
            perhari/minggu/bulan/tahun/tidak. Berikut adalah tampilannya:</p>
        <img src="img/tambahtugas.png" alt="Tambah-tugas">

        <h2>4. Tugas sudah ditambahkan</h2>
        <p>Setelah selesai menambahkan beberapa tugas, maka tampilannya akan menjadi seperti ini: </p>
        <img src="img/tampilan2.png" alt="Tampilan-2">

        <h2>5. Detail, hapus, edit, selesai</h2>
        <p>Pada setiap tugas yang telah ditambahkan akan terdapat pilihan untuk melihat detail tugas, menghapus tugas,
            mengedit tugas, dan mengubah status tugas menjadi selesai. Berikut adalah tampilannya:</p>
        <img src="img/edit.png" alt="Edit">
        <p>Untuk mengetahui detail selengkapnya, anda bisa langsung mencoba untuk menambahkan tugas pada aplikasi
            TodoTune ini.</p>

        <h2>6. Filter tugas berdasarkan kategori</h2>
        <p>Anda bisa memfilter tugas berdasarkan kategori hanya dengan mengklik kategori mana yang ingin ditampilkan.
            Berikut adalah tampilan saat menampilkan semua kategori tugas, kategori harian, dan kategori kerja:</p>
        <img src="img/semuakategori.png" alt="Semua-kategori">
        <img src="img/kategoriharian.png" alt="kategori-harian">
        <img src="img/kategorikerja.png" alt="kategori-kerja">
        <p>Pada aplikasi TodoTune, anda bisa menambahkan sendiri kategori tugas sesuai dengan keinginan anda!!</p>

        <h2>7. Kalender</h2>
        <p>Menu kalender ini dapat menampilkan tanggal dari bulan dan tahun yang anda inginkan, menampilkan
            tanggal merah yang ada pada setiap bulan beserta keterangannya, dan dapat menampilkan tugas yang ada
            pada suatu tanggal. Untuk tanggal yang memiliki tugas, akan terdapat tanda titik biru pada ujung kanan
            setiap tanggal, saat tanggal tersebut diklik, akan menampilkan tugas apa saja yang terdapat di tanggal
            tersebut. Berikut adalah tampilannya:</p>
        <img src="img/kalender1.png" alt="kalender1">
        <img src="img/kalender2.png" alt="kalender2">

        <h2>8. Pengaturan</h2>
        <p>Pada pengaturan, terdapat menu pengaturan akun, panduan pengguna, tentang aplikasi, dan versi aplikasi.
            Berikut adalah tampilannya: </p>
        <img src="img/pengaturan.png" alt="">

        <h2>9. Notifikasi</h2>
        <p>Pada notifikasi ini, akan menampilkan notifikasi/pengingat untuk tugas yang belum diselesaikan sementara
            deadline tugas tinggal 1 hari lagi. Apabila ada notifikasi, di ikon notifikasi akan menampilkan tanda merah
            beserta angka yang menunjukan ada berapa isi notifikasi di dalamnya. Berikut tampilan saat ikon notifikasi
            diklik:</p>
        <img src="img/notif.png" alt="notif">

        <h2>10. Profil</h2>
        <p>Di sebelah ikon notifikasi, terdapat ikon profil, pada saat diklik akan menampilkan laman yang berisi foto
            profil, nama pengguna, skala tugas selesai, dan skala tugas tidak selesai. Di sini anda bisa melihat progres
            anda dengan lebih jelas. Berikut adalah tampilannya, silahkan dicoba agar anda dapat mengetahui progres
            anda!!</p>
        <img src="" alt="">
        <img src="img/profil2.png" alt="">

        <h2>11. Logout</h2>
        <p>Setelah anda selesai menggunakan aplikasi TodoTune, anda bisa klik menu logout yang terdapat pada sidebar,
            dan data akan tersimpan otomatis.</p>
        <br>
        <h2>Sekian panduan penggunaan aplikasi TodoTune. Selamat mencoba!! Terima Kasih!!!</h2>
        <br>
        <br>
    </div>
</body>
<script src="https://kit.fontawesome.com/7b730c13ab.js" crossorigin="anonymous"></script>
</html>