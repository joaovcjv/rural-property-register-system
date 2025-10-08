<?php include '../includes/protecao.php'; ?>
<?php include '../includes/menu.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mapa Geoespacial</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="icon" href="fundiario/icon.ico" type="image/ico">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            height: 600px;
            margin: 2rem;
            border: 2px solid #ccc;
            border-radius: 8px;
            background-color: #f5f5f5; /* facilita ver se algo não foi carregado */
        }
    </style>
</head>
<body>
    <h2 style="margin-left:2rem;">Visualização Geoespacial (Mapa)</h2>
    <div id="map"></div>

    <script>
        const map = L.map('map').setView([-22.7439, -43.7078], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © OpenStreetMap contributors'
        }).addTo(map);

        fetch('pontos.php')
            .then(response => response.json())
            .then(imoveis => {
                console.log('Dados recebidos:', imoveis); // Debug

                const bounds = [];

                for (const id in imoveis) {
                    const coords = imoveis[id].coordenadas;
                    const nome = imoveis[id].nome;

                    if (coords.length >= 3) {
                        const poligono = L.polygon(coords, {
                            color: '#075e04',
                            weight: 2
                        }).addTo(map);

                        poligono.bindPopup(`<b>Imóvel:</b> ${nome}`);
                        bounds.push(...coords);
                    } else {
                        console.warn(`Imóvel ${nome} com coordenadas insuficientes`);
                    }
                }

                if (bounds.length > 0) {
                    map.fitBounds(bounds);
                } else {
                    alert("Nenhum polígono foi desenhado. Verifique os dados.");
                }
            })
            .catch(error => {
                console.error('Erro ao carregar polígonos:', error);
                alert('Erro ao carregar dados do servidor.');
            });
    </script>
</body>
</html>
