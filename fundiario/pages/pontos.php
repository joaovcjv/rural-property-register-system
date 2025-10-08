<?php
include '../includes/conexao.php';
header('Content-Type: application/json');

$imoveis = [];

// Buscar nome + coordenadas dos imóveis
$query = "SELECT i.id AS id_imovel, i.nome AS nome_imovel, ST_X(c.geom) as longitude, ST_Y(c.geom) as latitude, c.ordem
          FROM coordenadas_sigef c
          JOIN imovel i ON i.id = c.id_imovel
          ORDER BY i.id, c.ordem ASC";

$result = pg_query($conn, $query);

while ($row = pg_fetch_assoc($result)) {
    $id = $row['id_imovel'];
    if (!isset($imoveis[$id])) {
        $imoveis[$id] = [
            'nome' => $row['nome_imovel'],
            'coordenadas' => []
        ];
    }
    $imoveis[$id]['coordenadas'][] = [(float)$row['latitude'], (float)$row['longitude']];
}

// Fechar os polígonos (repete o primeiro ponto no final se for necessário)
foreach ($imoveis as &$imovel) {
    $coordenadas = $imovel['coordenadas'];
    if ($coordenadas[0] !== end($coordenadas)) {
        $imovel['coordenadas'][] = $coordenadas[0];
    }
}
unset($imovel);

echo json_encode($imoveis);
?>
