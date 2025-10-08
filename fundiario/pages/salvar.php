<?php
include '../includes/conexao.php';
session_start();

// Mostrar erros (debug)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Dados do formulário
$nome_imovel = $_POST['nome_imovel'];
$nome_proprietario = $_POST['nome_proprietario'];
$telefone = $_POST['telefone'];
$cpf = $_POST['cpf'];
$estado_civil = $_POST['estado_civil'];
$endereco = $_POST['endereco'];
$numero = $_POST['numero'];
$area_total = $_POST['area_total'];
$perimetro = $_POST['perimetro'];
$cep = $_POST['cep'];
$municipio = $_POST['municipio'];
$geojson = $_POST['geojson'];

// 1. Inserir Proprietário
$queryProp = "INSERT INTO proprietario (nome, telefone, cpf, estado_civil) VALUES ($1, $2, $3, $4) RETURNING id";
$resultProp = pg_query_params($conn, $queryProp, [$nome_proprietario, $telefone, $cpf, $estado_civil]);
$id_proprietario = pg_fetch_result($resultProp, 0, 'id');

// 2. Município
$queryMun = "SELECT id FROM municipio WHERE nome = $1";
$resultMun = pg_query_params($conn, $queryMun, [$municipio]);
$id_municipio = pg_num_rows($resultMun) > 0 ? pg_fetch_result($resultMun, 0, 'id') : null;

if (!$id_municipio) {
    $insertMun = "INSERT INTO municipio (nome) VALUES ($1) RETURNING id";
    $resultInsertMun = pg_query_params($conn, $insertMun, [$municipio]);
    $id_municipio = pg_fetch_result($resultInsertMun, 0, 'id');
}

// 3. Inserir Imóvel
$queryImovel = "INSERT INTO imovel (nome, id_proprietario, id_municipio, area_total, perimetro, endereco, numero, cep) 
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8) RETURNING id";
$resultImovel = pg_query_params($conn, $queryImovel, [
    $nome_imovel, $id_proprietario, $id_municipio,
    $area_total, $perimetro, $endereco, $numero, $cep
]);
$id_imovel = pg_fetch_result($resultImovel, 0, 'id');

// 4. Inserir coordenadas
$geojsonData = json_decode($geojson, true);
if (!$geojsonData || !isset($geojsonData['features'][0]['geometry']['coordinates'])) {
    die("GeoJSON inválido ou vazio.");
}

$feature = $geojsonData['features'][0];
$coordinates = $feature['geometry']['coordinates'];

if ($feature['geometry']['type'] === 'MultiPolygon') {
    $coordinates = $coordinates[0][0]; // pega o primeiro anel do primeiro polígono
} elseif ($feature['geometry']['type'] === 'Polygon') {
    $coordinates = $coordinates[0]; // pega o anel externo
} else {
    die("Tipo de geometria não suportado: " . $feature['geometry']['type']);
}

$ordem = 1;

foreach ($coordinates as $coord) {
    $lng = $coord[0];
    $lat = $coord[1];

    $wkt = "POINT($lng $lat)";

    echo "Inserindo ponto $ordem: LAT = $lat, LNG = $lng, WKT = $wkt<br>"; // debug

    $queryCoord = "
        INSERT INTO coordenadas_sigef (id_imovel, ordem, latitude, longitude, geom) 
        VALUES ($1, $2, $3, $4, ST_GeomFromText($5, 4674))
    ";
    $res = pg_query_params($conn, $queryCoord, [
        $id_imovel, $ordem++, $lat, $lng, $wkt
    ]);

    if (!$res) {
        echo "Erro ao inserir coordenada: " . pg_last_error($conn);
    }
}

$_SESSION['sucesso'] = "Imóvel cadastrado com sucesso!";
header("Location: ../pages/cadastro.php");
exit;
