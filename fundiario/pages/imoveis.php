<?php include '../includes/protecao.php'; ?>
<?php include '../includes/menu.php'; ?>
<?php include '../includes/conexao.php'; ?>

<?php
$query = "SELECT imovel.id, imovel.nome, imovel.area_total, municipio.nome AS municipio
          FROM imovel
          JOIN municipio ON imovel.id_municipio = municipio.id";
$result = pg_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lista de Imóveis</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="fundiario/icon.ico" type="image/ico">
</head>
<body>
    <h2 style="margin-left:2rem;">Lista de Imóveis</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Área Total (ha)</th>
            <th>Município</th>
        </tr>
        <?php while ($row = pg_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nome'] ?></td>
                <td><?= $row['area_total'] ?></td>
                <td><?= $row['municipio'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
