<?php
// =========================================
// MODO API (proxy seguro para o front-end)
// =========================================
if (isset($_GET['data'])) {
  header('Content-Type: application/json; charset=utf-8');

  $authToken = 'Basic NTFDNjFEQjMtMTU1RC03MzI1LTQ1MjFFOTEzNUNDNUQ4QzI=';
  $postData = array(
      'login' => 'unb',
      'senha' => '+zv!t{7t^EY5+[CaAssoc%d&#@Csep)*@!%#',
      'idAssociacao' => 0,
      'tipoRelatorio' => 1
  );

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
      echo json_encode(['ok' => false, 'error' => 'HTTP '.$status, 'raw' => $response], JSON_UNESCAPED_UNICODE);
      exit;
  }

  // repassa o JSON original da API
  echo $response;
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Home - MANÁ UNB</title>

  <style>
    * {margin:0; padding:0; box-sizing:border-box;}
    body {
      font-family:'Raleway', sans-serif;
      background-image: url('./img/Banner_MANA-2025.jpg');
      background-repeat: no-repeat;
      background-position: center;
      background-attachment: fixed;
      background-size: auto;
      min-height:100vh;
      display:flex;
      flex-direction:column;
      text-align:center;
      color:#000;
    }

    /* Loading: deixa ver o fundo */
    #loading {
      position: fixed;
      inset: 0;
      /* transparente p/ aparecer o background */
      display: flex;
      align-items:center;
      justify-content:center;
      z-index:9999;
      flex-direction: column;
     
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

    .main-content { 
      flex:1; 
      padding:30px 15px; 
      display: none; /* Fica escondido até a API chegar */
      
    }
    .total-number { font-size:100px; font-weight:700; margin:40px 0 15px; color:#1F2933;}
    .description { font-weight:500; margin-bottom:30px; font-size:1.1rem; }
    .grid {
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap:30px;
      max-width:1100px;
      margin:0 auto 50px;
    }
    .card-link { text-decoration: none; color: inherit; }
    .card { text-align:center; }
    .card-number { font-size:40px; font-weight:700; margin-bottom:1px; color:#0F2041; }

    .association-name {
      display:block;
      background-color:#3478BD;
      color:#fff;
      font-family:'Roboto', sans-serif;
      padding:14px;
      border-radius:6px;
      text-transform:uppercase;
      font-size:15px;
      font-weight:400;
      text-decoration:none;
      transition: background .2s ease, transform .1s ease;
      cursor: pointer;
    }
    .association-name:hover { background:#fcac0c; color:#fff; transform: translateY(-1px); }

    .footer {
      margin-top:auto;
      padding:15px;
      font-size:14px;
      border-top:1px solid #ddd;
      background:rgba(255,255,255,0.85);
      display: none; /* mostra só depois que os dados chegam */
    }

    /* Header / Menu */
    .header {
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header-logo img { max-width: 200px; }
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
    .nav-menu-desktop a:hover { color: #347cbc; }
    .nav-menu-desktop a::after {
        content: '';
        position: absolute;
        left: 0; bottom: -5px;
        width: 0; height: 2px;
        background-color: #347cbc;
        transition: width 0.3s ease;
    }
    .nav-menu-desktop a:hover::after { width: 100%; }
    .header-button-desktop a {
        background-color: #64C5DC;
        color: #ffffff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }
    .header-button-desktop a:hover { background-color: #347cbc; }

    /* Menu Mobile */
    .mobile-menu-container { display: none; }
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
    .mobile-nav-menu.active { display: flex; }
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
    .mobile-nav-menu a:last-child { border-bottom: none; }

    /* Responsivo */
    @media (max-width: 620px) {
      .nav-menu-desktop, .header-button-desktop { display: none; }
      .mobile-menu-container { display: block; }
    }
    @media (max-width:900px){ .grid {grid-template-columns: repeat(2, 1fr);} }
    @media (max-width:600px){
      .grid {grid-template-columns:1fr;}
      .total-number {font-size:60px;}
    }

#mopa {
  grid-column: 2 / -2;  /* ocupa todas as colunas */
  width: 100%; /* centraliza dentro da linha */
}

/* Garante que o cartão MOPA não tenha estilos conflitantes */



  </style>
</head>
<body>

  <!-- Loading -->
  <div id="loading">
    <div class="spinner"></div>
    <div class="loading-text">Carregando dados...</div>
  </div>
  

  <!-- Header -->
  <header class="header">
    <div class="header-logo">
      <img src="./img/logo_web.png" alt="Logo da União Norte Brasileira">
    </div>

    <div class="mobile-menu-container">
      <div class="mobile-menu-toggle" onclick="toggleMenu()">
        <div class="bar"></div><div class="bar"></div><div class="bar"></div>
      </div>
      <nav class="mobile-nav-menu">
        <ul>
          <li><a href="./index.php">Home</a></li>
          <li><a href="./home/index.html">Sobre o Projeto</a></li>
          <li><a href="https://projetomana.cpb.com.br/catalogo.html" target="_blank">ASSINATURA</a></li>
        </ul>
      </nav>
    </div>

    <nav class="nav-menu-desktop">
      <ul>
        <li><a href="./index.php">Home</a></li>
        <li><a href="./home/index.html">Sobre o Projeto</a></li>
      </ul>
    </nav>

    <div class="header-button-desktop">
      <a href="https://projetomana.cpb.com.br/catalogo.html" target="_blank">ASSINATURA</a>
    </div>
  </header>

  <!-- Conteúdo (começa oculto) -->
  <div class="main-content" id="conteudo">
    <h1 class="total-number" id="total-number">00000</h1>
    <p class="description">Números de Assinaturas da Lição da Escola Sabatina na União Norte Brasileira</p>

    <div class="grid">
      <a href="./associacao/anpa/index.php" class="card-link">
        <div class="card">
          <div class="card-number" id="anpa-number">00000</div>
          <span class="association-name">ASSOCIAÇÃO NORTE DO PARÁ</span>
        </div>
      </a>

      <div class="card">
        <div class="card-number" id="ama-number">00000</div>
        <a href="./associacao/ama/index.php" class="association-name">ASSOCIAÇÃO MARANHENSE</a>
      </div>

      <div class="card">
        <div class="card-number" id="aspa-number">00000</div>
        <a href="./associacao/aspa/index.php" class="association-name">ASSOCIAÇÃO SUL DO PARÁ</a>
      </div>

      <div class="card">
        <div class="card-number" id="asuma-number">00000</div>
        <a href="./associacao/asuma/index.php" class="association-name">ASSOCIAÇÃO SUL MARANHENSE</a>
      </div>

      <div class="card">
        <div class="card-number" id="mpa-number">00000</div>
        <a href="./associacao/mpa/index.php" class="association-name">MISSÃO PARÁ AMAPÁ</a>
      </div>

      <div class="card">
        <div class="card-number" id="mnem-number">00000</div>
        <a href="./associacao/mnem/index.php" class="association-name">MISSÃO NORDESTE MARANHENSE</a>
      </div>

      <div class="card" id="mopa">
        <div class="card-number" id="mopa-number">00000</div>
        <a href="./associacao/mopa/index.php" class="association-name" id="mopas" >MISSÃO OESTE DO PARÁ</a>
      </div>
    </div>
  </div>

  <div class="footer" id="rodape">
    &copy; 2023 – Todos os direitos reservados a União Norte Brasileira.
  </div>

  <script>
    function toggleMenu() {
      const mobileMenu = document.querySelector('.mobile-nav-menu');
      mobileMenu.classList.toggle('active');
    }

    const pad5 = (n) => String(n ?? 0).padStart(5, '0');

    function preencherDados(data) {
      // Mapeamento conforme seu código original:
      // total -> results[7]
      // ANPA -> [1], AMA -> [0], ASPA -> [2], ASUMA -> [3], MPA -> [6], MNEM -> [4], MOPA -> [5]
      const r = Array.isArray(data?.results) ? data.results : [];

      document.getElementById('total-number').textContent = pad5(r?.[7]?.total);

      document.getElementById('anpa-number').textContent  = pad5(r?.[1]?.total);
      document.getElementById('ama-number').textContent   = pad5(r?.[0]?.total);
      document.getElementById('aspa-number').textContent  = pad5(r?.[2]?.total);
      document.getElementById('asuma-number').textContent = pad5(r?.[3]?.total);
      document.getElementById('mpa-number').textContent   = pad5(r?.[6]?.total);
      document.getElementById('mnem-number').textContent  = pad5(r?.[4]?.total);
      document.getElementById('mopa-number').textContent  = pad5(r?.[5]?.total);
    }

    function mostrarConteudo() {
      document.getElementById('loading').style.display = 'none';
      document.getElementById('conteudo').style.display = 'block';
      document.getElementById('rodape').style.display = 'block';
    }

    function mostrarErro(msg) {
      const txt = document.querySelector('.loading-text');
      if (txt) txt.textContent = msg || 'Falha ao carregar. Tente novamente.';
    }

    async function carregarAPI() {
      try {
        const resp = await fetch(window.location.pathname + '?data=1', { cache: 'no-store' });
        if (!resp.ok) throw new Error('HTTP ' + resp.status);
        const data = await resp.json();

        // Preenche e mostra
        preencherDados(data);
        mostrarConteudo();
      } catch (e) {
        console.error(e);
        mostrarErro('Não foi possível carregar os dados. Verifique sua conexão e recarregue a página.');
      }
    }

    // Mostra o fundo imediatamente e busca a API assim que o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', carregarAPI);

    // (Opcional) Timeout de segurança: se demorar demais, avisa no loading
    setTimeout(() => {
      const loading = document.getElementById('loading');
      if (loading && loading.style.display !== 'none') {
        mostrarErro('Demorando para carregar... ainda tentando obter os dados.');
      }
    }, 12000);
  </script>
</body>
</html>
