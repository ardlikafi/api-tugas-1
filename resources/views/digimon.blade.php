<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Digimon</title>
</head>
<body>
    <h2>Daftar Digimon</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama Digimon</th>
                <th>Level</th>
            </tr>
        </thead>
        <tbody id="digimon-list">
            <!-- Data dari API akan muncul di sini -->
        </tbody>
    </table>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('https://digimon-api.vercel.app/api/digimon') // Ambil data dari Laravel Proxy
                .then(response => response.json()) // Ubah ke JSON
                .then(data => {
                    let tableContent = '';
                    data.forEach(digimon => {
                        tableContent += `
                            <tr>
                                <td><img src="${digimon.img}" width="100"></td>
                                <td>${digimon.name}</td>
                                <td>${digimon.level}</td>
                            </tr>
                        `;
                    });
                    document.getElementById('digimon-list').innerHTML = tableContent;
                })
                .catch(error => console.error("Error fetching Digimon data:", error));
        });
    </script>
</body>
</html>