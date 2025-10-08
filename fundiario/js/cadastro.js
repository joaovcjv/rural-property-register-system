// cadastro.js

// Inicializar o mapa
var map = L.map('map').setView([-22.74, -43.7], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

var layerGeoJSON;

// Escolher Polígono
const botaoEscolher = document.getElementById('botao-escolher');
const inputFile = document.getElementById('file');

botaoEscolher.addEventListener('click', function() {
    inputFile.click();
});

inputFile.addEventListener('change', function(e) {
    var file = e.target.files[0];
    var reader = new FileReader();
    reader.onload = function(event) {
        try {
            var geojson = JSON.parse(event.target.result);

            if (layerGeoJSON) map.removeLayer(layerGeoJSON);

            layerGeoJSON = L.geoJSON(geojson, {
                style: { color: "#075e04", weight: 3 }
            }).addTo(map);

            map.fitBounds(layerGeoJSON.getBounds());
            map.dragging.disable();
            map.scrollWheelZoom.disable();
            map.doubleClickZoom.disable();
            map.boxZoom.disable();
            map.keyboard.disable();
            if (map.zoomControl) map.zoomControl.remove();

            document.getElementById('geojson').value = JSON.stringify(geojson);
            calcularAreaPerimetro(layerGeoJSON);
        } catch (error) {
            alert('Erro: O arquivo selecionado não é um GeoJSON válido!');
            console.error('Erro ao ler GeoJSON:', error);
        }
    };
    reader.readAsText(file);
});

// Calcular área e perímetro
function calcularAreaPerimetro(layer) {
    if (!layer) return;

    const geojson = layer.toGeoJSON();
    if (!geojson || geojson.features.length === 0) return;

    const feature = geojson.features[0];

    if (feature.geometry.type === "Polygon" || feature.geometry.type === "MultiPolygon") {
        const area_m2 = turf.area(feature);
        const perimeter_m = turf.length(turf.lineString(feature.geometry.coordinates[0]), { units: 'meters' });
        const area_ha = area_m2 / 10000;

        document.querySelector('input[name="area_total"]').value = area_ha.toFixed(8);
        document.querySelector('input[name="perimetro"]').value = perimeter_m.toFixed(2);

        layer.bindPopup(`
            <b>Área:</b> ${area_ha.toFixed(8)} ha<br>
            <b>Perímetro:</b> ${perimeter_m.toFixed(2)} m
        `).openPopup();
    }
}

// Buscar sugestões de endereço
function buscarSugestoes() {
    const enderecoInput = document.getElementById('endereco');
    const municipioInput = document.querySelector('input[name="municipio"]');
    const sugestoesDiv = document.getElementById('sugestoes');
    const query = enderecoInput.value.trim();

    if (query.length < 3) {
        sugestoesDiv.innerHTML = '';
        return;
    }

    fetch('https://photon.komoot.io/api/?q=' + encodeURIComponent(query) + '&limit=5')
        .then(response => response.json())
        .then(data => {
            sugestoesDiv.innerHTML = '';

            data.features.forEach(item => {
                if (item.properties.name) {
                    let sugestao = item.properties.name;
                    if (item.properties.city) sugestao += ', ' + item.properties.city;
                    if (item.properties.state) sugestao += ', ' + item.properties.state;

                    const div = document.createElement('div');
                    div.style.padding = '8px';
                    div.style.cursor = 'pointer';
                    div.textContent = sugestao;

                    div.addEventListener('click', function() {
                        enderecoInput.value = item.properties.name;
                        municipioInput.value = item.properties.city || '';
                        sugestoesDiv.innerHTML = '';
                    });

                    sugestoesDiv.appendChild(div);
                }
            });
        })
        .catch(error => console.error('Erro ao buscar sugestões:', error));
}

// Fecha sugestões ao clicar fora
window.addEventListener('click', function(event) {
    const enderecoInput = document.getElementById('endereco');
    const sugestoesDiv = document.getElementById('sugestoes');
    if (!enderecoInput.contains(event.target) && !sugestoesDiv.contains(event.target)) {
        sugestoesDiv.innerHTML = '';
    }
});

// Modal de Confirmação de Formulário
const formulario = document.querySelector('form.formulario');

formulario.addEventListener('submit', function(event) {
    event.preventDefault();

    const dados = new FormData(formulario);
    let html = '<h2 style="color:#075e04;">Confirmação dos Dados</h2>';
    dados.forEach((valor, chave) => {
        if (chave !== 'geojson') {
            html += `<p><b>${chave.replace(/_/g, ' ')}:</b> ${valor}</p>`;
        }
    });

    criarModalConfirmacao(html, function(confirmado) {
        if (confirmado) {
            abrirPopupSucesso('Cadastro realizado com sucesso!');
            setTimeout(() => {
                formulario.submit();
            }, 1500); // Espera 1,5s para enviar
        }
    });
});


// Função para criar modais personalizados
function criarModalConfirmacao(textoHtml, callback) {
    const fundoModal = document.createElement('div');
    fundoModal.style.position = 'fixed';
    fundoModal.style.top = '0';
    fundoModal.style.left = '0';
    fundoModal.style.width = '100%';
    fundoModal.style.height = '100%';
    fundoModal.style.backgroundColor = 'rgba(0,0,0,0.5)';
    fundoModal.style.display = 'flex';
    fundoModal.style.justifyContent = 'center';
    fundoModal.style.alignItems = 'center';
    fundoModal.style.zIndex = '10000';

    const box = document.createElement('div');
    box.style.background = 'white';
    box.style.padding = '30px';
    box.style.borderRadius = '10px';
    box.style.boxShadow = '0 0 20px rgba(0,0,0,0.3)';
    box.innerHTML = `
        ${textoHtml}
        <div style="margin-top:20px; text-align:center;">
            <button id="confirmar" style="background:#075e04; color:white; border:none; padding:10px 20px; border-radius:5px; margin:5px; cursor:pointer;">Confirmar</button>
            <button id="cancelar" style="background:#d9534f; color:white; border:none; padding:10px 20px; border-radius:5px; margin:5px; cursor:pointer;">Cancelar</button>
        </div>
    `;

    fundoModal.appendChild(box);
    document.body.appendChild(fundoModal);

    document.getElementById('confirmar').onclick = function() {
        document.body.removeChild(fundoModal);
        callback(true);
    };

    document.getElementById('cancelar').onclick = function() {
        document.body.removeChild(fundoModal);
        callback(false);
    };
}

// (função limpar polígono já com pop-up)
document.getElementById('limparMapa').addEventListener('click', function() {
    if (layerGeoJSON) {
        abrirPopupConfirmacao('Deseja realmente limpar o polígono do mapa?', function() {
            map.removeLayer(layerGeoJSON);
            layerGeoJSON = null;

            // Libera novamente a movimentação
            map.dragging.enable();
            map.scrollWheelZoom.enable();
            map.doubleClickZoom.enable();
            map.boxZoom.enable();
            map.keyboard.enable();
            
            if (!map.zoomControl) {
                L.control.zoom({ position: 'topright' }).addTo(map);
            }

            document.querySelector('input[name="area_total"]').value = '';
            document.querySelector('input[name="perimetro"]').value = '';
            document.getElementById('file').value = '';

            abrirPopupSucesso('Polígono removido com sucesso!');
        });
    }
});

// Nova função para abrir pop-up de confirmação
function abrirPopupConfirmacao(mensagem, onConfirmar) {
    const fundoModal = document.createElement('div');
    fundoModal.className = 'fundo-modal';

    const box = document.createElement('div');
    box.className = 'modal-box';
    box.innerHTML = `
        <p style="margin-bottom:20px;">${mensagem}</p>
        <button id="confirmarAcao">Confirmar</button>
        <button id="cancelarAcao" style="background:#d9534f;">Cancelar</button>
    `;

    fundoModal.appendChild(box);
    document.body.appendChild(fundoModal);

    document.getElementById('confirmarAcao').onclick = function() {
        fundoModal.remove();
        if (onConfirmar) onConfirmar();
    };
    document.getElementById('cancelarAcao').onclick = function() {
        fundoModal.remove();
    };
}

// Nova função para abrir pop-up de sucesso
function abrirPopupSucesso(mensagem) {
    const fundoModal = document.createElement('div');
    fundoModal.className = 'fundo-modal';

    const box = document.createElement('div');
    box.className = 'modal-box';
    box.innerHTML = `
        <h2 style="color:#075e04;">Sucesso!</h2>
        <p style="margin:15px 0;">${mensagem}</p>
        <button id="fecharPopup">Fechar</button>
    `;

    fundoModal.appendChild(box);
    document.body.appendChild(fundoModal);

    document.getElementById('fecharPopup').onclick = function() {
        fundoModal.remove();
    };
}

// E no salvamento também (depois que confirmar o cadastro):
document.getElementById('confirmar').addEventListener('click', function() {
    fundoModal.remove();
    abrirPopupSucesso('Cadastro realizado com sucesso!');
    setTimeout(() => formulario.submit(), 1500); // Envia o formulário depois de 1.5s
});