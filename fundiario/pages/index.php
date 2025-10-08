<?php
include('../includes/menu.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sistema Fundiário</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="fundiario/icon.ico" type="image/ico">

    <style>

.container-home {
    max-width: 800px;
    margin: 60px auto;
    padding: 0 20px;
    text-align: center;
}

.titulo {
    font-size: 36px;
    color: #075e04;
    margin-bottom: 10px;
}

.subtitulo {
    font-size: 18px;
    color: #555;
    margin-bottom: 50px;
}

.carrossel {
    position: relative;
    min-height: 180px;
}

.noticia-box {
    display: none;
    background-color: #075e04;
    color: white;
    padding: 30px 25px;
    border-radius: 20px;
    transition: opacity 0.4s ease;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
}

.noticia-box.ativa {
    display: block;
}

.noticia-box a {
    text-decoration: none;
    color: white;
    display: block;
}

.noticia-box h2 {
    font-size: 24px;
    margin-bottom: 0px;
}

.noticia-box p {
    font-size: 16px;
    margin: 0;
}

.noticia-box img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 12px;
    margin-top: 25px;
    margin-bottom: 25px; 
}

.controle {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: white;
    color: #075e04;
    border: none;
    font-size: 24px;
    padding: 8px 15px;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    transition: background-color 0.3s ease;
}

.controle:hover {
    background-color: #eee;
}

.anterior {
    left: -40px;
}

.proximo {
    right: -40px;
}

</style> 

</head>
<body>
    <div class="container-home">
        <h1 class="titulo">Geoportal Fundiário</h1>
        <p class="subtitulo">Informações e atualizações sobre a Regularização Fundiária</p>

        <div class="carrossel">
            <div class="noticia-box ativa">
                <a href="https://portal.ufrrj.br/ufrrj-e-incra-firmam-parceria-para-regularizacao-fundiaria-da-fazenda-nacional-de-santa-cruz/" target="_blank">
                    <h2>Oportunidades de Estágio</h2>
                    <p>UFRRJ e Incra firmam parceria para regularização fundiária da Fazenda Nacional de Santa Cruz.</p>
                    <img src="../imagens/ufrrj-oficina.jpg" alt="Oficina UFRRJ">
                </a>
            </div>

            <div class="noticia-box">
                <a href="https://www.car.gov.br/publico/imoveis/index" target="_blank">
                    <h2>Cadastro Ambiental Rural (CAR)</h2>
                    <p>Novos dados sobre áreas de preservação permanente e reserva legal.</p>
                    <img src="../imagens/topografia-georreferenciamento-01.jpg" alt="Cadastro Ambiental Rural">
                </a>
            </div>

            <div class="noticia-box">
                <a href="https://sigef.incra.gov.br" target="_blank">
                    <h2>Certificação no SIGEF</h2>
                    <p>Imóveis da Fazenda Santa Cruz recebem certificação no sistema federal.</p>
                    <img src="../imagens/certificacao_imovel_sigef.jpg" alt="Certificação SIGEF">
                </a>
            </div>

            <button class="controle anterior">❮</button>
            <button class="controle proximo">❯</button>
        </div>
    </div>

    <script>
        const boxes = document.querySelectorAll('.noticia-box');
        let atual = 0;

        document.querySelector('.proximo').addEventListener('click', () => {
            boxes[atual].classList.remove('ativa');
            atual = (atual + 1) % boxes.length;
            boxes[atual].classList.add('ativa');
        });

        document.querySelector('.anterior').addEventListener('click', () => {
            boxes[atual].classList.remove('ativa');
            atual = (atual - 1 + boxes.length) % boxes.length;
            boxes[atual].classList.add('ativa');
        });
    </script>
</body>



</html>


