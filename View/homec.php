
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem Vindo - Home</title>
    <link rel="stylesheet" href="../Assets/CSS/home.css">
    <link rel="shortcut icon" href="../Assets/IMG/logo.webp" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
</head>
<body>
<!--ANIMAÇÃO DE LOGIN-->
<div id="loading-overlay">
    <div class="spinner"></div>
    <p id="loading-message">Carregando Sistema...</p>
</div>
<script>
//animacao de login
function hideLoadingOverlay() {
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.classList.add('hidden');
        
        // 💡 CORREÇÃO: Mudar de 5000ms para 500ms
        setTimeout(() => {
            loadingOverlay.remove();
        }, 500); 
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const loadingMessage = document.getElementById('loading-message');
    
    // 1. Mudar o texto após 1.5 segundos
    setTimeout(() => {
        if (loadingMessage) {
            loadingMessage.textContent = "Bem vindo!";
        }
    }, 1500); 
    
    // 2. Esconder a tela após 3.5 segundos no total
    setTimeout(hideLoadingOverlay, 3500); 
});
</script>
<style>
  #loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.95);
    display: flex; 
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 1000; 
    font-family: Arial, sans-serif;
    color: #333;
    transition: opacity 0.5s ease;
}


.spinner {
    border: 8px solid #f3f3f3; 
    border-top: 8px solid #417dff; 
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1s linear infinite; 
    margin-bottom: 20px;
}


@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.hidden {
    opacity: 0;
    pointer-events: none; 
}
</style>
  <!-- SIDEBAR -->
  <section id="sidebar">
  <a href="../index.php" class="brand"><img width="100%" src="../Assets/IMG/LOGOSP.png" alt=""></a>
  <ul class="side-menu">
    <li><a href="../index.php">Home</a></li>
    <li class="divider" data-text="navegação">Navegação</li>

    <li><a href="./PaginaDeFrete.php">Calcular Frete</a></li>
    <li><a href="minhaconta.html">Minha Conta</a></li>

    <!-- Botão SAIR adicionado -->
    <li>
      <a href="../index.php" class="logout-btn">
        <i class='bx bx-log-out'></i> Sair
      </a>
    </li>
  </ul>

</section>
  <!-- CONTEÚDO -->
  <section id="content">
  
    <!-- MAIN -->
    <main>
      <h1 class="title">Home</h1>
      <ul class="breadcrumbs">
        <li><a href="../index.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Início</a></li>
      </ul>

      <!-- Apenas os 3 botões principais -->
      <div class="info-data" style="grid-template-columns: repeat(3, minmax(180px,1fr));">
        <a class="card" href="./home.html" style="text-decoration:none;">
          <div class="head">
            <div>
              <h2>Home</h2>
              <p>Voltar ao início</p>
            </div>
            <i class='bx bxs-home icon'></i>
          </div>
          <span class="label">Página inicial</span>
        </a>

        <a class="card" href="./PaginaDeFrete.php" style="text-decoration:none;">
          <div class="head">
            <div>
              <h2>Solicitações</h2>
              <p>Soliciatar Novos Fretes</p>
            </div>
            <i class='bx bxs-inbox icon'></i>
          </div>
          <span class="label">Abrir solicitações</span>
        </a>

        <a class="card" href="minhaconta.html" style="text-decoration:none;">
          <div class="head">
            <div>
              <h2>Minha Conta</h2>
              <p>Dados e ajustes</p>
            </div>
            <i class='bx bxs-user icon'></i>
          </div>
          <span class="label">Ver perfil</span>
        </a>
      </div>
      
    </main>


<section class="ResumoPedido" id="resumo-pedidos" style="margin-top: 20px;">
  <h3 style="color:#417dff;">Pedidos Solicitados</h3>
  <div id="listaPedidos"></div>
</section>

  <!-- Modal de Cancelar -->
<div id="modalCancelarHome" class="modal-overlay">
  <div class="modal-conteudo">
    <h2>Deseja Cancelar O Pedido?</h2>
  
    <div class="modal-botoes">
      <button id="confirmarCancelarBtn">Confirmar</button>
      <button id="cancelarModalBtn">Cancelar</button>
    </div>
  </div>
</div>

<script src="https://kit.fontawesome.com/02669f3445.js" crossorigin="anonymous"></script>
<script>

    const SUPABASE_URL = "https://oudhyeawauuzvkrhsgsk.supabase.co";
    const SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im91ZGh5ZWF3YXV1enZrcmhzZ3NrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA3MTA2OTcsImV4cCI6MjA3NjI4NjY5N30.-SdoeQo9GYcTeaXI7hvHJ9M0-ONVovFpQ1aUbkojCF0";

    const CLIENTE_ID = <?php echo json_encode($_SESSION['usuario_id'] ?? null); ?>;
    

    var db = supabase.createClient(SUPABASE_URL, SUPABASE_KEY); 
    
    console.log("ID do Cliente Injetado na Home:", CLIENTE_ID); 
</script>
<script src="../JS/TelaDeFrete.js"></script>
<script src="../JS/homePedido.js"></script>
</body>
</html>