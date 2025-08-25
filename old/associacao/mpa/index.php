<?php
// =========================================
// MODO API (proxy seguro para o front-end)
// =========================================
if (isset($_GET['data'])) {
    header('Content-Type: application/json; charset=utf-8');

    // Suas credenciais e token para a requisição
    $authToken = 'Basic NTFDNjFEQjMtMTU1RC03MzI1LTQ1MjFFOTEzNUNDNUQ4QzI=';

    // Dados para a requisição
    $postData = array(
        'login' => 'unb',
        'senha' => '+zv!t{7t^EY5+[CaAssoc%d&#@Csep)*@!%#',
        'idAssociacao' => $_GET['id'] ?? 0,
        'tipoRelatorio' => $_GET['tipo'] ?? 1
    );

    // Configuração do cURL
    $ch = curl_init('https://ecommerce-api-alpha.cpb.com.br/v1/reports/manaByUnion.json');
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Authorization: ' . $authToken,
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_TIMEOUT => 25
    ));

    $response = curl_exec($ch);

    if ($response === FALSE) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => curl_error($ch)], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status >= 400) {
        http_response_code($status);
        echo json_encode(['ok' => false, 'error' => 'HTTP ' . $status, 'raw' => $response], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo $response;
    exit;
}

// =========================================
// MODO HTML/Front-end (com pré-carregamento)
// =========================================
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>MANAMÔMETRO-UNB</title>
    <meta name="description" content="Mananometro da União Norte Brasileira da IASD" />
    <meta name="keywords" content="mana, escola, sabaina, iasd, adventista, lição, assinatura, cpb, unb" />
    <meta name="author" content="União Norte Brasileira da IASD" />
    
    <link rel="apple-touch-icon" href="../../wp-content/uploads/2023/06/logo_web" >
    <link rel="stylesheet" id="elementor-gf-local-poppins-css" href="./wp-content/uploads/elementor/google-fonts/css/poppins.css?ver=1742225474" type="text/css" media="all">
    <link rel="stylesheet" id="elementor-gf-local-raleway-css" href="./wp-content/uploads/elementor/google-fonts/css/raleway.css?ver=1742225488" type="text/css" media="all">
    <link rel="stylesheet" id="elementor-gf-local-roboto-css" href="./wp-content/uploads/elementor/google-fonts/css/roboto.css?ver=1742225465" type="text/css" media="all">
    <link rel="stylesheet" id="elementor-gf-local-robotoslab-css" href="./wp-content/uploads/elementor/google-fonts/css/robotoslab.css?ver=1742225471" type="text/css" media="all">

    <style>
        body {
            font-family: 'Raleway', sans-serif;
            color: #1F2933;
            margin: 0;
            padding: 0;
            background-image: url('../../img/Banner_MANA-2025.jpg');  
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: auto;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- Loading Screen Styles --- */
        #loading {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            
            z-index: 9999;
            
        }
        .loading-text {
            margin-top: 16px;
            font-size: 18px;
            color: #3478BD;
            font-weight: bold;
        }
        .spinner {
            border:8px solid #f3f3f3;
            border-top:8px solid #3478BD;
            border-radius:50%;
            width:70px;
            height:70px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        .preloader { display: none; }

        /* --- Main Content and Footer --- */
        .main-container {
            display: none; /* Initially hidden */
            flex: 1;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }
        .footer-credit {
            display: none; /* Initially hidden */
            width: 100%;
            background-color: #f0f0f0;
            padding: 20px 0;
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: auto;
        }
        
        .header-section {
            text-align: center;
            margin-bottom: 40px;
        }
        .main-counter {
            font-size: 80px;
            font-weight: 700;
            color: #1F2933;
            margin: 0;
        }
        .page-subtitle {
            font-size: 18px;
            font-weight: 500;
            margin-top: 10px;
        }
        .districts-container {
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        .district-box {
            background-color: #347cbc;
            color: #ffffff;
            border-radius: 4px;
            padding: 15px 25px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            box-sizing: border-box;
            transition: background-color 0.3s ease;
        }
        .district-box:hover {
            background-color: #ffc107;
            color: #fffefeff;
        }
        .district-name {
            font-size: 16px;
            font-weight: 600;
        }
        .district-total {
            font-size: 24px;
            font-weight: 700;
        }
        .footer-section {
            text-align: center;
            margin-top: 50px;
        }
        .back-button {
            background-color: #347cbc;
            color: #ffffff;
            border: none;
            padding: 15px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            text-transform: uppercase;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #F9AF08;
            color: #ffffffff;
        }

        /* Estilos do Menu */
        .header {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-logo img {
            max-width: 200px;
        }
        .nav-menu-desktop, .header-button {
            display: flex;
            align-items: center;
        }
        .nav-menu-desktop ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }
        .nav-menu-desktop a {
            text-decoration: none;
            color: #1F2933;
            font-weight: 600;
            padding: 10px 15px;
            transition: color 0.3s ease;
            position: relative;
            font-family: 'Poppins', sans-serif;
        }
        .nav-menu-desktop a:hover {
            color: #347cbc;
        }
        .nav-menu-desktop a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0;
            height: 2px;
            background-color: #347cbc;
            transition: width 0.3s ease;
        }
        .nav-menu-desktop a:hover::after {
            width: 100%;
        }
        .header-button a {
            background-color: #64C5DC;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }
        .header-button a:hover {
            background-color: #347cbc;
        }
        
        /* Menu Mobile */
        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 5px;
            z-index: 100;
        }
        .mobile-menu-toggle .bar {
            width: 25px;
            height: 3px;
            background-color: #1F2933;
            transition: all 0.3s ease;
        }
        .mobile-nav-menu {
            display: none;
            position: absolute;
            top: 80px;
            left: 0;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            gap: 10px;
        }
        .mobile-nav-menu.active {
            display: flex;
        }
        .mobile-nav-menu li {
            width: 100%;
            text-align: center;
            list-style: none;
        }
        .mobile-nav-menu a {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            padding: 15px 0;
            display: block;
            text-decoration: none;
            color: #1F2933;
            border-bottom: 1px solid #eee;
        }
        .mobile-nav-menu a:last-child {
            border-bottom: none;
        }

        /* Oculta o menu de desktop e o botão de assinatura em telas pequenas */
        @media (max-width: 620px) {
            .nav-menu-desktop, .header-button {
                display: none;
            }
            .mobile-menu-toggle {
                display: flex;
            }
        }
    </style>
</head>
<body>

    <div id="loading">
        <div class="spinner"></div>
        <div class="loading-text">Carregando dados...</div>
    </div>

<header class="header">
    <div class="header-logo">
        <img src="http://mana.unb.org.br/wp-content/uploads/2023/06/logo_web.png" alt="Logo da União Norte Brasileira">
    </div>
    
    <div class="mobile-menu-toggle" onclick="toggleMenu()">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>
    
    <nav class="nav-menu-desktop">
        <ul>
            <li><a href="../../index.php">Home</a></li>
            <li><a href="../../home/index.html">Sobre o Projeto</a></li>
        </ul>
    </nav>
    <div class="header-button">
        <a href="https://projetomana.cpb.com.br/catalogo.html" target="_blank">ASSINATURA</a>
    </div>
</header>

<nav class="mobile-nav-menu">
    <ul>
         <li><a href="../../index.php">Home</a></li>
            <li><a href="../../home/index.html">Sobre o Projeto</a></li>
        <li><a href="https://projetomana.cpb.com.br/catalogo.html" target="_blank">ASSINATURA</a></li>
    </ul>
</nav>

<div class="main-container" id="conteudo">
    <div class="header-section">
        <h1 class="main-counter" id="total-number">00000</h1>
        <h2 class="page-subtitle">Números de Assinaturas da Lição da Escola Sabatina na <span id="association-name">Missão Pará Amapá</span></h2>
    </div>

    <div class="districts-container" id="districts-grid">
        </div>

    <div class="footer-section">
        <input type="button" value="Voltar" class="back-button" onClick="history.back();">
    </div>
</div>

<div class="footer-credit" id="rodape">
    © 2023 - Todos os direitos reservados à União Norte Brasileira.
</div>

<script>
    function toggleMenu() {
        const mobileMenu = document.querySelector('.mobile-nav-menu');
        mobileMenu.classList.toggle('active');
    }

    const pad5 = (n) => String(n ?? 0).padStart(5, '0');

    function preencherDados(data) {
        // Find the total number from the data
        const totalResult = data?.results.find(res => res.siglaAssociacao === 'Todos');
        const total = totalResult ? totalResult.total : 0;
        document.getElementById('total-number').textContent = pad5(total);

        // Update the association name
        document.getElementById('association-name').textContent = 'Missão Pará Amapá';
        
        // Populate the districts
        const districtsGrid = document.getElementById('districts-grid');
        districtsGrid.innerHTML = ''; // Clear previous content

        const filteredDistricts = data?.results.filter(d => d.distrito && d.distrito !== "" && d.siglaAssociacao !== "Todos") || [];
        
        filteredDistricts.forEach(distrito => {
            const box = document.createElement('div');
            box.classList.add('district-box');
            box.innerHTML = `
                <span class="district-name">${distrito.distrito}</span>
                <strong class="district-total">${distrito.total}</strong>
            `;
            districtsGrid.appendChild(box);
        });
    }

    function mostrarConteudo() {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('conteudo').style.display = 'flex';
        document.getElementById('rodape').style.display = 'flex';
    }

    function mostrarErro(msg) {
        const txt = document.querySelector('.loading-text');
        if (txt) txt.textContent = msg || 'Falha ao carregar. Tente novamente.';
    }

    async function carregarAPI() {
        try {
            const id = 67; // For Missão Pará Amapá
            const tipo = 2; // For detailed report
            
            const resp = await fetch(window.location.pathname + `?data=1&id=${id}&tipo=${tipo}`, { cache: 'no-store' });
            
            if (!resp.ok) {
                const errorData = await resp.json();
                throw new Error('HTTP ' + resp.status + ': ' + (errorData.error || 'Unknown Error'));
            }
            const data = await resp.json();

            preencherDados(data);
            mostrarConteudo();
        } catch (e) {
            console.error(e);
            mostrarErro('Não foi possível carregar os dados. Verifique sua conexão e recarregue a página.');
        }
    }

    document.addEventListener('DOMContentLoaded', carregarAPI);
    
</script>
</body>
</html>