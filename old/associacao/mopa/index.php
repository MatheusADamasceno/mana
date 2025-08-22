<?php
// Suas credenciais e token para a primeira requisição (relatório geral)
$authToken1 = 'Basic NTFDNjFEQjMtMTU1RC03MzI1LTQ1MjFFOTEzNUNDNUQ4QzI=';

// Dados para a primeira requisição
$postData1 = array(
    'login' => 'unb',
    'senha' => '+zv!t{7t^EY5+[CaAssoc%d&#@Csep)*@!%#',
    'idAssociacao' => 0,
    'tipoRelatorio' => 1
);

// Configuração do cURL
$ch1 = curl_init('https://ecommerce-api-alpha.cpb.com.br/v1/reports/manaByUnion.json');
curl_setopt_array($ch1, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => array(
        'Authorization: ' . $authToken1,
        'Content-Type: application/json'
    ),
    CURLOPT_POSTFIELDS => json_encode($postData1)
));
$response1 = curl_exec($ch1);
curl_close($ch1);

if ($response1 === FALSE) {
    die(curl_error($ch1));
}
$responseData1 = json_decode($response1, TRUE);

// Define a variável $total
$total = '';
if (isset($responseData1['results']) && is_array($responseData1['results'])) {
    foreach ($responseData1['results'] as $result) {
        if (isset($result['siglaAssociacao']) && $result['siglaAssociacao'] === 'Todos') {
            $total = $result['total'];
            break;
        }
    }
}
if (empty($total) && isset($responseData1['results'][0]['total'])) {
    $total = $responseData1['results'][0]['total'];
}

// Credenciais para a segunda requisição (dados por distrito)
$authToken = 'Basic NTFDNjFEQjMtMTU1RC03MzI1LTQ1MjFFOTEzNUNDNUQ4QzI=';
$Campo = "Missão Oeste do Pará";
$postData = array(
    'login' => 'unb',
    'senha' => '+zv!t{7t^EY5+[CaAssoc%d&#@Csep)*@!%#',
    'idAssociacao' => 50,
    'tipoRelatorio' => 2
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
    CURLOPT_POSTFIELDS => json_encode($postData)
));
$response = curl_exec($ch);
curl_close($ch);

if ($response === FALSE) {
    die(curl_error($ch));
}
$responseData = json_decode($response, TRUE);
$qtde = isset($responseData['results']) ? count($responseData['results']) : 0;

// Corrigindo a busca do total para a Missão Oeste do Pará (idAssociacao 50)
$total_local = '';
if (isset($responseData['results']) && is_array($responseData['results'])) {
    foreach ($responseData['results'] as $result) {
        if (isset($result['siglaAssociacao']) && $result['siglaAssociacao'] === 'Todos' && isset($result['total'])) {
            $total_local = $result['total'];
            break;
        }
    }
}

// Se o total_local não for encontrado na segunda requisição, use um valor padrão.
// Isso evita erros caso a API não retorne o formato esperado.
if (empty($total_local)) {
    $total_local = 0;
}

$total_display = str_pad($total_local, 5, '0', STR_PAD_LEFT);
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
}
        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
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
        .preloader { display: none; }
        .footer-credit {
            width: 100%;
            background-color: #f0f0f0;
            padding: 20px 0;
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 50px;
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
        .nav-menu-desktop, .header-button-desktop {
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
        .header-button-desktop a {
            background-color: #64C5DC;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }
        .header-button-desktop a:hover {
            background-color: #347cbc;
        }
        
        /* Menu Mobile */
        .mobile-menu-container {
            display: none;
        }
        .mobile-menu-toggle {
            display: flex;
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
            .nav-menu-desktop, .header-button-desktop {
                display: none;
            }
            .mobile-menu-container {
                display: block;
            }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="header-logo">
        <img src="http://mana.unb.org.br/wp-content/uploads/2023/06/logo_web.png" alt="Logo da União Norte Brasileira">
    </div>
    
    <div class="mobile-menu-container">
        <div class="mobile-menu-toggle" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    <nav class="mobile-nav-menu">
            <ul>
                <li><a href="../../index.php">Home</a></li>
                <li><a href="../../home/index.html">Sobre o Projeto</a></li>
                <li><a href="https://projetomana.cpb.com.br/catalogo.html" target="_blank">ASSINATURA</a></li>
            </ul>
        </nav>
    </div>
    
    <nav class="nav-menu-desktop">
        <ul>
            <li><a href="../../index.php">Home</a></li>
            <li><a href="../../home/index.html">Sobre o Projeto</a></li>
        </ul>
    </nav>
    <div class="header-button-desktop">
        <a href="https://projetomana.cpb.com.br/catalogo.html" target="_blank">ASSINATURA</a>
    </div>
</header>

<div class="main-container">
    <div class="header-section">
        <h1 class="main-counter"><?php echo str_pad($total_display, 5, '0', STR_PAD_LEFT); ?></h1>
        <h2 class="page-subtitle">Números de Assinaturas da Lição da Escola Sabatina na <?php echo $Campo; ?></h2>
    </div>

    <div class="districts-container">
        <?php
        if ($qtde > 0):
            foreach ($responseData['results'] as $distrito):
                // Exibe os distritos, ignorando o totalizador "Todos"
                if (isset($distrito['distrito']) && $distrito['distrito'] !== "" && isset($distrito['siglaAssociacao']) && $distrito['siglaAssociacao'] !== "Todos"):
        ?>
                    <div class="district-box">
                        <span class="district-name"><?php echo htmlspecialchars($distrito['distrito']); ?></span>
                        <strong class="district-total"><?php echo htmlspecialchars($distrito['total']); ?></strong>
                    </div>
        <?php
                endif;
            endforeach;
        endif;
        ?>
    </div>

    <div class="footer-section">
        <input type="button" value="Voltar" class="back-button" onClick="history.back();">
    </div>
</div>

<div class="footer-credit">
    © 2023 - Todos os direitos reservados à União Norte Brasileira.
</div>

<script>
function toggleMenu() {
    const mobileMenu = document.querySelector('.mobile-nav-menu');
    mobileMenu.classList.toggle('active');
}
</script>

<script type="text/javascript" src="../assets/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery-ui-1.10.4.min.js"></script>
<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../assets/js/notifyMe.js"></script>
<script type="text/javascript" src="../assets/js/jquery.placeholder.js"></script>
<script type="text/javascript" src="../assets/js/jquery.plugin.js"></script> 
<script type="text/javascript" src="../assets/js/jquery.countdown.js"></script>
<script type="text/javascript" src="../assets/js/init.js?chave=<?php echo date('YmdHis'); ?>"></script>
<script type="text/javascript" src="https://mana.unb.org.br/wp-content/plugins/royal-elementor-addons/assets/js/lib/particles/particles.js?ver=3.0.6" id="wpr-particles-js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var particleConfig = {
        "particles": {
            "number": {
                "value": 80,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            },
            "color": {
                "value": "#7391ae"
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#7391ae"
                },
                "polygon": {
                    "nb_sides": 5
                },
                "image": {
                    "src": "img/github.svg",
                    "width": 100,
                    "height": 100
                }
            },
            "opacity": {
                "value": 0.5,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 1,
                    "opacity_min": 0.1,
                    "sync": false
                }
            },
            "size": {
                "value": 3,
                "random": true,
                "anim": {
                    "enable": false,
                    "speed": 40,
                    "size_min": 0.1,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": true,
                "distance": 150,
                "color": "#7391ae",
                "opacity": 0.4,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 6,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "bounce": false,
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "window",
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "repulse"
                },
                "onclick": {
                    "enable": true,
                    "mode": "push"
                },
                "resize": true
            },
            "modes": {
                "grab": {
                    "distance": 400,
                    "line_linked": {
                        "opacity": 1
                    }
                },
                "bubble": {
                    "distance": 400,
                    "size": 40,
                    "duration": 2,
                    "opacity": 8,
                    "speed": 3
                },
                "repulse": {
                    "distance": 200,
                    "duration": 0.4
                },
                "push": {
                    "particles_nb": 4
                },
                "remove": {
                    "particles_nb": 2
                }
            }
        },
        "retina_detect": true
    };
    particlesJS('main-container-particles', particleConfig);
});
</script>

</body>
</html>