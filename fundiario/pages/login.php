<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/conexao.php';
include '../includes/menu.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $query = "SELECT * FROM usuarios WHERE email = $1";
    $result = pg_query_params($conn, $query, [$email]);

    if ($row = pg_fetch_assoc($result)) {
        if (password_verify($senha, $row['senha'])) {
            $_SESSION['usuario'] = $row['nome'];
            header("Location: ../pages/index.php");
            exit;
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="fundiario/icon.ico" type="image/ico">
    <style>
        .login-container {
            width: 100%;
            height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(242, 242, 242, 0);
            flex-direction: column;
        }
        .login-box {
            background-color: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.14);
            text-align: center;
            min-width: 300px;
        }
        .login-box h2 {
            color: #075e04;
            margin-bottom: 20px;
        }
        .login-box input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .login-box button {
            background-color: #075e04;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .login-box button:hover {
            background-color: #064d03;
        }
        .erro {
            color: red;
            margin-top: 10px;
        }
        .cadastro-link {
            margin-top: 15px;
            text-align: center;
        }
        .cadastro-link a {
            text-decoration: underline;
            color: #075e04;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Centro de Operações</h2>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="senha" placeholder="Senha" required><br>
                <button type="submit">Entrar</button>
            </form>
            <?php if ($erro): ?>
                <p class="erro"><?= $erro ?></p>
            <?php endif; ?>
        </div>
        <div class="cadastro-link">
            <a href="cadastrar_usuario.php">Cadastre-se</a>
        </div>
    </div>
</body>
</html>
