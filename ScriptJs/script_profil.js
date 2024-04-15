document.addEventListener('DOMContentLoaded', function () {
    // Get the context of the canvas element
    const ctx = document.getElementById('combinedChart').getContext('2d');

    // Create a combined bar chart with initial data
    const combinedChart = new Chart(ctx, {
        type: 'bar', // Ganti tipe chart menjadi 'bar'
        data: {
            labels: ['Selesai', 'Tidak Selesai'],
            datasets: [{
                label: 'Tugas Selesai',
                data: [],
                backgroundColor: "#4CAF50",
                borderColor: "#4CAF50",
                borderWidth: 1
            }, {
                label: 'Tugas Tidak Selesai',
                data: [],
                backgroundColor: "#FF5733",
                borderColor: "#FF5733",
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    barPercentage: 0.5, // Menyesuaikan lebar bar
                    categoryPercentage: 0.8, // Menyesuaikan ruang antar bar
                    position: 'right', // Menyesuaikan posisi label pada sumbu x
                },
                y: {
                    beginAtZero: true,
                    max: 60, // Menyesuaikan skala maksimum pada sumbu y
                }
            },
            layout: {
                padding: {
                    left: 25, // Menyesuaikan jarak antara bagian kiri chart dan edge canvas
                    right: 25, // Menyesuaikan jarak antara bagian kanan chart dan edge canvas
                    top: 10, // Menyesuaikan jarak antara bagian atas chart dan edge canvas
                    bottom: 10, // Menyesuaikan jarak antara bagian bawah chart dan edge canvas
                }
            },
            
            responsive: true, // Membuat chart responsif
            plugins: {
                legend: {
                    labels: {
                        fontSize: 10, // Ubah ukuran font untuk label legenda
                    }
                }
            },
        }
    });

    // Function to update the chart based on the selected option
    function updateChart() {
        // Lakukan request AJAX ke file PHP untuk mendapatkan data dari database
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Parse data JSON yang diterima
                const data = JSON.parse(this.responseText);

                // Update data chart
                combinedChart.data.datasets[0].data = [data.total_completed, 0];
                combinedChart.data.datasets[1].data = [0, data.total_incomplete];

                // Update chart
                combinedChart.update();
            }
        };

        // Sesuaikan URL PHP yang sesuai
        xhr.open("GET", "function/functions_getData_tugas.php", true);
        xhr.send();
    }

    // Panggil fungsi updateChart saat halaman dimuat pertama kali
    updateChart();
});




function openPopupskala() {
    document.getElementById("popup-container-skala").style.display = "block";
    document.getElementById("overlay1").style.display = "block";
    document.body.style.overflow = 'auto';
}

function closePopupskala() {
        document.getElementById("popup-container-skala").style.display = "none";
        document.getElementById("overlay1").style.display = "none";
        document.body.style.overflow = 'auto';
}
function openPopupskala2() {
    document.getElementById("popup-container-skala2").style.display = "block";
    document.getElementById("overlay1").style.display = "block";
    document.body.style.overflow = 'auto';
}

function closePopupskala2() {
        document.getElementById("popup-container-skala2").style.display = "none";
        document.getElementById("overlay1").style.display = "none";
        document.body.style.overflow = 'auto';
}

