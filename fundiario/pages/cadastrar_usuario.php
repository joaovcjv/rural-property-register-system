<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/conexao.php';
include '../includes/menu.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $verifica = pg_query_params($conn, "SELECT id FROM usuarios WHERE email = $1", [$email]);

    if (pg_num_rows($verifica) > 0) {
        $erro = "Este e-mail já está cadastrado.";
    } else {
        $query = "INSERT INTO usuarios (nome, email, cpf, senha) VALUES ($1, $2, $3, $4)";
        $result = pg_query_params($conn, $query, [$nome, $email, $cpf, $senha]);

        if ($result) {
            $sucesso = "Cadastro realizado com sucesso!";
        } else {
            $erro = "Erro ao cadastrar. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="fundiario/icon.ico" type="image/ico">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
        }

        .cadastro-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .cadastro-box {
            background-color: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.14);
            text-align: center;
            min-width: 300px;
        }

        .cadastro-box h2 {
            color: #075e04;
            margin-bottom: 20px;
        }

        .cadastro-box input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .cadastro-box button {
            background-color: #075e04;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        .cadastro-box button:hover {
            background-color: #064d03;
        }

        .erro {
            color: red;
            margin-top: 10px;
        }

        .sucesso {
            color: green;
            margin-top: 10px;
        }

        .voltar {
            margin-top: 15px;
        }

        .voltar a {
            text-decoration: underline;
            color: #075e04;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="cadastro-container">
        <div class="cadastro-box">
            <h2>Cadastrar Usuário</h2>
            <form method="POST">
                <input type="text" name="nome" placeholder="Nome completo" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="cpf" placeholder="CPF" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <button type="submit">Cadastrar</button>
            </form>

            <?php if ($erro): ?>
                <p class="erro"><?= $erro ?></p>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <p class="sucesso"><?= $sucesso ?></p>
            <?php endif; ?>

            <div class="voltar">
                <a href="login.php">Voltar ao login</a>
            </div>
        </div>
    </div>
</body>
</html>
