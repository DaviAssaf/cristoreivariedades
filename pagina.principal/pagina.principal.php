<?php
session_start();
$admin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'administrador';
$cliente = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'cliente';
$logado = $admin || $cliente;

if ($logado && isset($_SESSION['user_id'])) {
    $username = 'Usuário';
    $email = 'email@exemplo.com';
    $fotoPerfil = '';

    include '../conexao/conexao.php';

    if ($admin) {
        $query = "SELECT nome AS username, email, foto_perfil FROM administradores WHERE id = ?";
    } elseif ($cliente) {
        $query = "SELECT nome AS username, email, foto_perfil FROM clientes WHERE id = ?";
    }

    if (isset($query)) {
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $stmt->bind_result($username, $email, $fotoPerfil);
            $stmt->fetch();
            $stmt->close();
        } else {
            echo "Erro na preparação da consulta.";
        }
    }

    $profileImageSrc = !empty($fotoPerfil) ? 'data:image/jpeg;base64,' . base64_encode($fotoPerfil) : 'imagens-produtos/material-escolar/profile-icon.jpg';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="pagina.principal.css">
    <title>Cristo Rei Variedades</title>
</head>
<body>
    <div id="logo-container">
        <img src="imagens-produtos/logo/logo.principal.adaptada.jpg" alt="GIF Promocional">
    </div>


    <header id="titulo_pagina">
        <h1>Cristo Rei Variedades</h1>
    </header>

    <div id="box_nav">
        <nav>
            <a href="pagina.principal.html" target="_blank">HOME</a>
            <a href="../material.escolar/material-escolar.html" target="_blank">MATERIAL ESCOLAR</a>
            <a href="../material.escritorio/material-escritorio.html" target="_blank">MATERIAL DE ESCRITÓRIO</a>
        </nav>
    </div>

    <div id="search-container">
        <form action="/search" method="get">
            <button type="submit">🔍</button>
            <input type="text" placeholder="Buscar..." name="search" width="100%">
        </form>
    </div>

    <?php if (!$logado): ?>
        <button id="login-button" onclick="window.location.href='../login/login.html'">Logar</button>
    <?php else: ?>
        <div class="user-menu">
            <button onclick="togglePopup()">
                <img src="<?php echo $profileImageSrc; ?>" alt="Ícone do Perfil" id="profile-icon">
                <span><?php echo htmlspecialchars($username); ?></span>
            </button>
            <div id="popup" class="popup">
                <div class="popup-header">
                    <img src="<?php echo $profileImageSrc; ?>" alt="Ícone do Perfil" class="profile-icon-large">
                    <span class="profile-name"><?php echo htmlspecialchars($username); ?></span>
                    <span class="profile-email"><?php echo htmlspecialchars($email); ?></span>
                </div>
                <div class="popup-content">
                    <a href="../perfil/perfil.php">Minha Conta</a>
                    <?php if ($admin): ?>
                        <a href="../gerenciar-produtos/produtos.php">Gerenciar Produtos</a>
                        <a href="../gerenciar-administradores/administradores.php">Gerenciar Administradores</a>
                        <a href="../tabelas/tabelas.php">Visualizar Tabelas</a>
                    <?php endif; ?>
                </div>
                <div class="popup-footer">
                    <button type="button" onclick="window.location.href='../login/logout.php'">Sair</button>
                </div>
            </div>
        </div>
    <?php endif; ?>


    
    <main>
        <h2>PROMOÇÃO DA SEMANA</h2>
        <div class="container">
            <div class="prateleira1">
                <img src="../pagina.principal/imagens-produtos/material-escolar/Lápis Leo & Leo (Preto) 200X200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Lápis Leo & Leo (Preto).</p>
                <p class="preco">R$1,00</p>
                <button>COMPRAR</button>
            </div>
            <div class="prateleira1">
                <img src="../pagina.principal/imagens-produtos/material-escolar/Tinta guache com 6 unidades 200X200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Tinta Guache com 6 Unidades.</p>
                <p class="preco">R$8,00</p>
                <button>COMPRAR</button>
            </div>
            <div class="prateleira1">
                <img src="../pagina.principal/imagens-produtos/material-escolar/Lápis de cor grande Leo & Leo 200X200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Lápis de Cor Grande Leo & Leo.</p>
                <p class="preco">R$10,00</p>
                <button>COMPRAR</button>
            </div>
            <div class="prateleira1">
                <img src="../pagina.principal/imagens-produtos/material-escolar/Massinha De Modelar Soft 12 Core 200X200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Massinha de Modelar.</p>
                <p class="preco">R$7,00</p>
                <button>COMPRAR</button>
            </div>
        </div>

        <div class="container">
            <div class="prateleira2">
                <img src="../pagina.principal/imagens-produtos/material-escritorio/estilete pequeno200x200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Estilete Pequeno.</p>
                <p class="preco">R$4,00</p>
                <button>COMPRAR</button>
            </div>
            <div class="prateleira2">
                <img src="../pagina.principal/imagens-produtos/material-escritorio/papel de certificado com textura 200x200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Papel para Certificado com Textura.</p>
                <p class="preco">R$1,00</p>
                <button>COMPRAR</button>
            </div>
            <div class="prateleira2">
                <img src="../pagina.principal/imagens-produtos/material-escritorio/grampeado 200x200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Grampeador Pequeno.</p>
                <p class="preco">R$15,00</p>
                <button>COMPRAR</button>
            </div>
            <div class="prateleira2">
                <img src="../pagina.principal/imagens-produtos/material-escritorio/corretivo de pincel 200x200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Corretivo.</p>
                <p class="preco">R$4,00</p>
                <button>COMPRAR</button>
            </div>
        </div>

        <div class="container">
            <div class="prateleira3">
                <img src="../pagina.principal/imagens-produtos/material-escolar/Borracha escolar pequena 200X200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Borracha Pequena.</p>
                <p class="preco">R$0,50</p>
                <button>COMPRAR</button>
            </div>
            <div class="prateleira3">
                <img src="../pagina.principal/imagens-produtos/material-escolar/Papel Cartao Fosco 48X66Cm. 200G 200X200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Papel Cartão.</p>
                <p class="preco">R$4,00</p>
                <button>COMPRAR</button>
            </div>
            <div class="prateleira3">
                <img src="../pagina.principal/imagens-produtos/material-escolar/Big Giz de Cera 12 Cores Acrilex 200X200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Giz de Cera.</p>
                <p class="preco">R$6,00</p>
                <button>COMPRAR</button>
            </div>
            <div class="prateleira3">
                <img src="../pagina.principal/imagens-produtos/material-escolar/Borracha ponteira de lápis 200X200.jpg" alt="produto" class="produto">
                <p class="descricao-produto">Borracha para Ponta de Lápis.</p>
                <p class="preco">R$0,50</p>
                <button>COMPRAR</button>
            </div>
        </div>
    </main>

    <footer id="box_informacoes">
        <div id="contatos">
            <h4>CONTATO</h4>

            <nav id="box_contatos">
                <a href="https://api.whatsapp.com/send?l=pt_pt&phone=5591993233542" target="_blank">
                    <img src="../icones/icone-whatsapp.png" alt="">
                </a>

                <a href="https://www.instagram.com/cristorei_loja/" target="_blank">
                    <img src="../icones/icone-Instagram.png" alt="">
                </a>

                <a href="https://www.google.com.br/maps/place/Cristo+Rei+Servi%C3%A7os+e+Variedades/@-1.4689534,-48.4924003,17z/data=!3m1!4b1!4m6!3m5!1s0x92a48e6e34df4c1b:0xf31c5ea93708197!8m2!3d-1.4689534!4d-48.48982!16s%2Fg%2F11fzfd_g07?entry=tts&g_ep=EgoyMDI0MTAyOS4wIPu8ASoASAFQAw%3D%3D" target="_blank">
                    <img src="../icones/icone-loclizacao.png" alt="">
                </a>
            </nav>
        </div>

        <div id="sobre-nos">
            <h4>SOBRE NÓS</h4>
            <p>Há décadas servindo a comunidade, a papelaria Cristo Rei Variedades é um estabelecimento tradicional especializado em materiais escolares e de escritório. Com um atendimento personalizado e uma vasta gama de produtos de qualidade.</p>
        </div>
    </footer>

    <script>
        function togglePopup() {
            const popup = document.getElementById("popup");
            popup.style.display = (popup.style.display === "block") ? "none" : "block";
        }

        window.onclick = function(event) {
            const popup = document.getElementById("popup");
            const profileIcon = document.getElementById("profile-icon");

            if (event.target !== popup && event.target !== profileIcon && !popup.contains(event.target)) {
                popup.style.display = "none";
            }
        }
    </script>

</body>
</html>