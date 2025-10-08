<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0; /* para remover a margem externa */
        background-color: #f2f2f2;
    }

    .navbar {
        background-color: #075e04;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        \\position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar a {
    color: white;
    text-decoration: none;
    margin: 0 1rem;
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

    .navbar a:hover {
    background-color: white;
    color: #075e04;
    box-shadow: 0 0 0 2px white;
}
</style>

<div class="navbar">
    <div><a href="/fundiario/pages/index.php">Sistema Fundiário</a></div>
    <div>
        <a href="/fundiario/pages/index.php">Início</a>
        <?php if (isset($_SESSION['usuario'])): ?>
            <a href="/fundiario/pages/imoveis.php">Imóveis</a>
            <a href="/fundiario/pages/mapa.php">Mapa</a>
            <a href="/fundiario/pages/cadastro.php">Cadastrar</a>
            <a href="/fundiario/pages/logout.php">Sair</a>
        <?php else: ?>
            <a href="/fundiario/pages/login.php">Login</a>
        <?php endif; ?>
    </div>
</div>
