<?php include '../includes/protecao.php'; ?>
<?php include '../includes/menu.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Imóvel</title>
    <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
    <script src="../js/cadastro.js" defer></script>

    <style>
        .box-titulo { background: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1.titulo { font-size: 36px; color: #075e04; margin-bottom: 10px; }
        .subtitulo { font-size: 18px; color: #555; }
        .upload-box { background: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .upload-box button { background-color: #075e04; color: white; padding: 10px 20px; border-radius: 5px; font-weight: bold; border: none; margin: 0 5px; cursor: pointer; transition: background 0.3s ease; }
        .upload-box button:hover { opacity: 0.85; }
        #limparMapa { background-color: #d9534f; }
        #map { height: 400px; width: 100%; margin: 20px 0; border: 2px solid #075e04; border-radius: 10px; }
        .formulario { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-top: 20px; }
        .formulario input, .formulario button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ccc; }
        .formulario button { background-color: #075e04; color: white; font-weight: bold; cursor: pointer; }
        
        .fundo-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-box {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    max-width: 400px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

.modal-box button {
    background-color: #075e04;
    color: white;
    border: none;
    padding: 10px 20px;
    margin: 10px;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}

    </style>
</head>

<body>
<div class="container-home">
<?php if (isset($_SESSION['sucesso'])): ?>
    <div id="mensagem-sucesso" class="mensagem-sucesso">
        <?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
    </div>
<?php endif; ?>

<div class="box-titulo">
    <h1 class="titulo">Cadastro de Imóvel</h1>
    <p class="subtitulo">Importe o polígono e preencha as informações do imóvel</p>
</div>

<div class="upload-box">
    <button type="button" id="botao-escolher">Escolher Polígono (GeoJSON)</button>
    <input type="file" id="file" accept=".geojson,.json" style="display:none;">
    <button type="button" id="limparMapa">Limpar Polígono</button>
</div>

<div id="map"></div>

<form method="POST" action="../pages/salvar.php" class="formulario">
    <input type="text" name="nome_imovel" placeholder="Nome do Imóvel" required>
    <input type="text" name="nome_proprietario" placeholder="Nome do Proprietário" required>
    <input type="text" name="telefone" placeholder="Telefone" required>
    <input type="text" name="cpf" placeholder="CPF" required>
    <input type="text" name="estado_civil" placeholder="Estado Civil" required>

    <div style="position: relative;">
        <input type="text" id="endereco" name="endereco" placeholder="Endereço" required oninput="buscarSugestoes()" autocomplete="off">
        <div id="sugestoes" class="sugestoes"></div>
    </div>

    <input type="text" name="numero" placeholder="Número da Casa" required>
    <input type="text" name="area_total" placeholder="Área Total (ha)" readonly>
    <input type="text" name="perimetro" placeholder="Perímetro (m)" readonly>
    <input type="text" name="cep" placeholder="CEP" required>
    <input type="text" name="municipio" placeholder="Município" required>
    <input type="hidden" id="geojson" name="geojson">
    <button type="submit">Salvar Imóvel</button>
</form>
</div>
</body>
</html>