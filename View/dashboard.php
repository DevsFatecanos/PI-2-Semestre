<?php
session_start();
//bloqueia o acesso direto

if (!isset($_SESSION['usuario_email'])) {
    header("Location: login.html");
    exit;
}
$email = $_SESSION['usuario_email'];



require_once __DIR__ . '/../Controller/VeiculoController.php';
require_once __DIR__ . '/../conexao.php';

$controller = new VeiculoController($pdo);
$veiculos = $controller->listar();



$veiculoController = new VeiculoController($pdo);

// Dados
$veiculosDisponiveis = $veiculoController->contarDisponiveis();
$veiculosManutencao = $veiculoController->contarManutencao();
?>


<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="shortcut icon" href="../Assets/IMG/logo.webp" type="image/x-icon">
  <link rel="stylesheet" href="../Assets/CSS/dashboard.css">
  <title>Admin - SuperSonic Transportes</title>
  <!-- Estilos simples embutidos para facilitar uso sem dependências -->
  <style>

  </style>
</head>

<body>
<div class="app">
  <aside class="sidebar card">
    <div class="brand"><div class="logo" style="display: flex; justify-content: center; align-items: center;"><img src="../Assets/IMG/logo.webp" alt="Logo SuperSonic Transportes" style="width: 20px; height: 20px;"></div>SuperSonic Transportes<br><span class="small">Admin</span></div>
    <nav class="nav" id="menu">
      <a href="#" data-view="dashboard" class="active">Dashboard</a>
      <a href="#" data-view="envios">Envios</a>
      <a href="#" data-view="criar-envio">Aprovar Envio</a>
      <a href="#" data-view="veiculos">Veículos</a>
      <a href="#" data-view="motoristas">Motoristas</a>
      <a href="#" data-view="relatorios">Relatórios</a>
      <a href="#" data-view="usuarios">Usuários</a>
      <a href="#" data-view="config">Configurações</a>
    </nav>
  </aside>

  <main class="main">
    <div class="topbar">
      <div>
        <h2 id="page-title">Dashboard</h2>
        <div class="small">Painel administrativo — visão geral rápida</div>
      </div>
      <div style="display:flex;gap:12px;align-items:center">
        <input placeholder="Pesquisar envios, placas, clientes..." style="padding:8px 12px;border-radius:10px;border:1px solid rgba(255,255,255,0.03);background:transparent;color:inherit" id="search" />
        <button class="btn" onclick="openView('criar-envio')">Novo Envio</button>
      </div>
    </div>

    <!-- VIEWS: cada view é uma seção toggleable -->

    <section id="dashboard" class="view">
      <div class="grid cols-3" style="margin-bottom:16px">
        <div class="card">
          <div class="small">Envios ativos</div>
          <h3 style="margin:8px 0">124</h3>
          <div class="small">Última atualização: 22/10/2025</div>
        </div>
         <div class="card">
          <div class="small">Veículos disponíveis</div>
          <h3 style="margin:8px 0"><?= $veiculosDisponiveis ?></h3>
          <div class="small">Em manutenção: <?= $veiculosManutencao ?></div>
        </div>
        <div class="card">
          <div class="small">Receita mensal</div>
          <h3 style="margin:8px 0">R$ 48.720,50</h3>
          <div class="small">Média diária: R$ 1.573,24</div>
        </div>
      </div>

      <div class="grid" style="grid-template-columns:2fr 1fr;gap:16px">
        <div class="card">
          <h4>Últimos envios</h4>
          <table>
            <thead><tr><th>#</th><th>Cliente</th><th>Origem → Destino</th><th>Placa</th><th>Status</th></tr></thead>
            <tbody>
              <tr><td>00123</td><td>Distribuidora A</td><td>08060-160 → 08430-000</td><td>ABC-1D23</td><td>Em rota</td></tr>
              <tr><td>00124</td><td>Loja B</td><td>BH → SP</td><td>XYZ-9F88</td><td>Aguardando</td></tr>
              <tr><td>00125</td><td>Cliente C</td><td>POA → CWB</td><td>LMN-4E56</td><td>Entregue</td></tr>
            </tbody>
          </table>
        </div>
        <div class="card">
          <h4>Atividades</h4>
          <ul style="padding-left:18px;color:var(--muted)">
            <li>Motorista João atualizou status do envio 00123</li>
            <li>Novo usuário cadastrado: operador1</li>
            <li>Relatório diário gerado</li>
          </ul>
        </div>
      </div>
    </section>

    <section id="envios" class="view" style="display:none">
      <div class="card" style="margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <h3>Lista de Envios</h3>
          <div style="display:flex;gap:8px"><button class="btn ghost" onclick="exportTable()">Exportar CSV</button><button class="btn" onclick="openView('criar-envio')">Novo Envio</button></div>
        </div>
        <table style="margin-top:12px">
          <thead><tr><th>ID</th><th>Cliente</th><th>Origem</th><th>Destino</th><th>Placa</th><th>Data</th><th>Status</th><th>Ações</th></tr></thead>
          <tbody>
            <!-- Exemplo estático; adaptar para dados reais -->
            <tr><td>00123</td><td>Distribuidora A</td><td>São Paulo</td><td>Rio de Janeiro</td><td>ABC-1D23</td><td>20/10/2025</td><td>Em rota</td><td><button class="btn ghost" onclick="viewShipment('00123')">Ver</button></td></tr>
            <tr><td>00124</td><td>Loja B</td><td>Belo Horizonte</td><td>São Paulo</td><td>XYZ-9F88</td><td>21/10/2025</td><td>Aguardando</td><td><button class="btn ghost" onclick="viewShipment('00124')">Ver</button></td></tr>
          </tbody>
        </table>
      </div>
    </section>


<section id="criar-envio" class="view" style="display:none">
  <div class="card">
    <h3>Pedidos Aguardando Aprovação</h3>
       <div id="listaAprovarContainer" class="scrollArea">
        <div id="listaFretes" style="margin-top:20px;"></div>
      </div>
</section>

<style>
  .scrollArea {
    max-height: calc(100vh - 220px); /* ajuste fino da altura da tela */
    overflow-y: auto;
    margin-top: 14px;
    padding-right: 6px;
    scrollbar-width: thin; 
    scrollbar-color: #4b5563 #1f2937;
}

.scrollArea::-webkit-scrollbar {
    width: 8px;
}
.scrollArea::-webkit-scrollbar-thumb {
    background-color: #4b5563;
    border-radius: 6px;
}
.scrollArea::-webkit-scrollbar-track {
    background: #1f2937;
}
</style>


<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>

<script>
const SUPABASE_URL = "https://oudhyeawauuzvkrhsgsk.supabase.co";
const SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im91ZGh5ZWF3YXV1enZrcmhzZ3NrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA3MTA2OTcsImV4cCI6MjA3NjI4NjY5N30.-SdoeQo9GYcTeaXI7hvHJ9M0-ONVovFpQ1aUbkojCF0";

async function carregarFretes() {
    const resposta = await fetch(
        `${SUPABASE_URL}/rest/v1/fretes_solicitados?select=*,usuario:cliente_id(*),veiculo:veiculo_id(*)`,
        {
            headers: {
                "apikey": SUPABASE_KEY,
                "Authorization": `Bearer ${SUPABASE_KEY}`
            }
        }
    );

    const dados = await resposta.json();
    console.log("FRETES CARREGADOS:", dados);
    mostrarFretes(dados);
}


function mostrarFretes(lista) {
    const div = document.getElementById("listaFretes");
    div.innerHTML = "";

    if (lista.length === 0) {
        div.innerHTML = "<p>Nenhum frete encontrado.</p>";
        return;
    }

    lista.forEach(frete => {

        const user = frete.usuario || {};
        const veic = frete.veiculo || {};

        // ============================
        // 1. DEFINIR COR DO STATUS
        // ============================
        let bg = "";
        let color = "";
        let icone = "";

        switch (frete.status) {
            case "Aguardando Aprovação":
                bg = "#fff6d4";
                color = "#a67c00";
                icone = '<i class="fa-solid fa-hourglass-half" style="color:#f1c40f;"></i>';
                break;

            case "Aprovado":
                bg = "#d6f5d6";
                color = "#1b7e1b";
                icone = '<i class="fa-solid fa-circle-check" style="color:#27ae60;"></i>';
                break;

            case "Em Transporte":
                bg = "#d4e8ff";
                color = "#1e6bb8";
                icone = '<i class="fa-solid fa-truck-fast" style="color:#3498db;"></i>';
                break;

            case "Entregue":
                bg = "#e8f9f0";
                color = "#16803a";
                icone = '<i class="fa-solid fa-box-open" style="color:#2ecc71;"></i>';
                break;

            case "Cancelado":
                bg = "#ffe0e0";
                color = "#b33939";
                icone = '<i class="fa-solid fa-circle-xmark" style="color:#e74c3c;"></i>';
                break;

            default:
                bg = "#e0e0e0";
                color = "#555";
                icone = '<i class="fa-solid fa-circle-info" style="color:#95a5a6;"></i>';
        }

        // ============================
        // 2. CARD ESTILO TELA ADM
        // ============================

        const card = document.createElement("div");
        card.style.cssText = `
            background: var(--card);
            border-left: 4px solid ${color};
            padding: 20px;
            border-radius: 14px;
            margin-bottom: 18px;
            box-shadow: 0px 8px 24px rgba(2, 6, 23, 0.6);
        `;

        const statusTag = `
            <span style="
                display:inline-flex;
                align-items:center;
                gap:6px;
                background:${bg};
                color:${color};
                padding:6px 10px;
                border-radius:6px;
                font-weight:600;
                font-size:13px;
            ">
                ${icone} ${frete.status}
            </span>
        `;

        card.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <h2 style="margin:0;font-size:20px;color:#fff">Frete #${frete.id}</h2>
                ${statusTag}
            </div>

            <div style="margin-top:15px">
                <p><strong>Cliente:</strong> ${user.nome || "—"} — ${user.telefone || ""}</p>
                <p><strong>Email:</strong> ${user.email || ""}</p>

                <p style="margin-top:10px"><strong>Origem:</strong> ${frete.origem}, Nº ${frete.numero_origem} ${frete.complemento_origem}</p>
                <p><strong>Destino:</strong> ${frete.destino}, Nº ${frete.numero_destino} ${frete.complemento_destino}</p>

                <p style="margin-top:10px"><strong>Veículo:</strong> ${veic.modelo || "—"} (${veic.placa || ""})</p>
                <p><strong>Carga:</strong> ${frete.descricao_carga}</p>
                <p><strong>Distância:</strong> ${frete.distancia}</p>
                <p><strong>Valor:</strong> ${frete.valor}</p>

                <p style="margin-top:10px"><strong>Data/Hora:</strong> ${new Date(frete.data_hora).toLocaleString("pt-BR")}</p>
            </div>

            <div style="margin-top:18px; display:flex; gap:10px;">
                <button onclick="aprovar(${frete.id})" class="btn">Aprovar</button>
                <button onclick="recusar(${frete.id})" class="btn ghost" 
                    style="border:1px solid #ff4d4d;color:#ff6b6b;">
                    Recusar
                </button>
            </div>
        `;

        div.appendChild(card);
    });
}



function aprovar(id) {
    fetch(`${SUPABASE_URL}/rest/v1/fretes_solicitados?id=eq.${id}`, {
        method: "PATCH",
        headers: {
            "apikey": SUPABASE_KEY,
            "Authorization": `Bearer ${SUPABASE_KEY}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ status: "Aprovado" })
    }).then(() => {
        alert("Frete aprovado!");
        carregarFretes();
    });
}

function recusar(id) {
    fetch(`${SUPABASE_URL}/rest/v1/fretes_solicitados?id=eq.${id}`, {
        method: "PATCH",
        headers: {
            "apikey": SUPABASE_KEY,
            "Authorization": `Bearer ${SUPABASE_KEY}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ status: "Recusado" })
    }).then(() => {
        alert("Frete recusado!");
        carregarFretes();
    });
}

document.querySelector('[data-view="criar-envio"]')
    .addEventListener("click", carregarFretes);


</script>


<section id="veiculos" class="view" style="display:none" >
  <div class="card">
    <h3>Veículos</h3>
    <table style="margin-top:12px">
      <thead>
        <tr><th>ID</th><th>Modelo</th><th>Placa</th><th>Status</th><th>Ações</th></tr>
      </thead>
      <tbody>
        <?php foreach ($veiculos as $v): ?>
          <tr>
            <td><?= htmlspecialchars($v->id_veiculo) ?></td>
            <td><?= htmlspecialchars($v->modelo) ?></td>
            <td><?= htmlspecialchars($v->placa) ?></td>
            <td><?= htmlspecialchars($v->status) ?></td>
            <td><button class="btn ghost">Editar</button></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div style="margin-top:12px;display:flex;gap:12px;justify-content:flex-end">
    <button class="btn ghost" onclick="abrirRemover()">Remover veículo</button>
    <button class="btn" id="btnAbrirModal">Adicionar veículo</button>

</div>

  </div>
</section>


    <section id="motoristas" class="view" style="display:none">
      <div class="card">
        <h3>Motoristas</h3>
        <table style="margin-top:12px">
          <thead><tr><th>Nome</th><th>Telefone</th><th>CNH</th><th>Status</th><th>Ações</th></tr></thead>
          <tbody>
            <tr><td>João Silva</td><td>(11) 99999-9999</td><td>AB123456</td><td>Ativo</td><td><button class="btn ghost">Perfil</button></td></tr>
          </tbody>
        </table>
        <div style="margin-top:12px;display:flex;gap:12px;justify-content:flex-end">
        <button class="btn ghost">Remover motorista</button>  
        <button class="btn">Adicionar motorista</button>
          
        </div>
      </div>
    </section>

    <section id="relatorios" class="view" style="display:none">
      <div class="card">
        <h3>Relatórios</h3>
        <div class="small">Gere relatórios por período, veículo, motorista ou cliente.</div>
        <form style="margin-top:12px" onsubmit="generateReport(event)">
          <div class="row">
            <div style="flex:1"><label>Período início</label><input type="date" required id="r-start" /></div>
            <div style="flex:1"><label>Período fim</label><input type="date" required id="r-end" /></div>
          </div>
          <div style="margin-top:12px;display:flex;gap:8px;justify-content:flex-end">
            <button class="btn" type="submit">Gerar</button>
          </div>
        </form>
      </div>
    </section>

    <section id="usuarios" class="view" style="display:none">
      <div class="card">
        <h3>Usuários</h3>
        <table style="margin-top:12px">
          <thead><tr><th>Login</th><th>Nome</th><th>Perfil</th><th>Ações</th></tr></thead>
          <tbody>
            <tr><td>admin</td><td>Administrador</td><td>Admin</td><td><button class="btn ghost">Editar</button></td></tr>
            <tr><td>operador1</td><td>Operador</td><td>Operador</td><td><button class="btn ghost">Editar</button></td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <section id="config" class="view" style="display:none">
      <div class="card">
        <h3>Configurações</h3>
        <div class="small">Ajustes gerais do sistema.</div>
        <form style="margin-top:12px" onsubmit="saveConfig(event)">
          <label>Nome da empresa</label>
          <input id="cfg-name" placeholder="Transportadora Exemplo" />
          <label style="margin-top:12px">Fuso horário</label>
          <select id="cfg-tz"><option>America/Sao_Paulo</option><option>UTC</option></select>
          <div style="margin-top:12px;display:flex;justify-content:flex-end"><button class="btn" type="submit">Salvar</button></div>
        </form>
      </div>
    </section>

  </main>
</div>
<!-- Modal Adicionar -->
<div id="modalAdicionar" class="modal-overlay hidden">
    <div class="modal">
        <h2>Adicionar veículo</h2>

        <form action="../Controller/VeiculoRouter.php?action=adicionar" method="POST">
            <label>Modelo</label>
            <input type="text" name="modelo" required>

            <label>Placa</label>
            <input type="text" name="placa" required>

            <label>Status</label>
            <select name="status">
                <option value="disponivel">Disponível</option>
                <option value="em uso">Em uso</option>
                <option value="manutencao">Manutenção</option>
            </select>

            <div style="margin-top: 16px; display:flex; justify-content: flex-end; gap:10px;">
                <button type="button" id="fecharModal" class="btn ghost">Cancelar</button>
                <button type="submit" class="btn">Adicionar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Remover -->
<div id="modalRemover" class="modal-overlay hidden">
    <div class="modal">
        <h2>Remover Veículo</h2>

        <form action="../Controller/VeiculoController.php?action=remover" method="POST">
            <label>ID do veículo</label>
            <input type="number" name="id" required>

            <button type="submit" class="btn ghost">Remover</button>
            <button type="button" class="btn" onclick="fecharRemover()">Cancelar</button>
        </form>
    </div>
</div>

<script>

function abrirRemover() {
    document.getElementById("modalRemover").classList.remove("hidden");
}

function fecharRemover() {
    document.getElementById("modalRemover").classList.add("hidden");
}

const modal = document.getElementById("modalAdicionar");
document.getElementById("btnAbrirModal")?.addEventListener("click", () => {
    modal.classList.remove("hidden");
});

document.getElementById("fecharModal")?.addEventListener("click", () => {
    modal.classList.add("hidden");
});

// Fechar ao clicar FORA do modal
modal.addEventListener("click", (e) => {
    if (e.target === modal) {
        modal.classList.add("hidden");
    }
});




  // Navegação simples entre views
  function openView(id){
    document.querySelectorAll('.view').forEach(v=>v.style.display='none');
    const el = document.getElementById(id);
    if(el) el.style.display='block';
    // atualizar menu ativo e título
    document.querySelectorAll('#menu a').forEach(a=>a.classList.remove('active'));
    const menuItem = document.querySelector('#menu a[data-view="'+id+'"]');
    if(menuItem) menuItem.classList.add('active');
    document.getElementById('page-title').innerText = menuItem? menuItem.innerText : id;
    window.scrollTo(0,0);
  }
  document.querySelectorAll('#menu a').forEach(a=>a.addEventListener('click', e=>{e.preventDefault();openView(a.dataset.view);}));

  function saveEnvio(e){e.preventDefault();alert('Envio salvo (demo). Integre com backend para persistir.');openView('envios');}
  function viewShipment(id){alert('Abrir detalhes do envio '+id+' (demo)');}
  function exportTable(){alert('Exportar CSV (demo)');}
  function generateReport(e){e.preventDefault();alert('Relatório gerado (demo)');}
  function saveConfig(e){e.preventDefault();alert('Configurações salvas (demo)');}

  // Sugestão: substituir os alert por modais e conectar a APIs (fetch/fetch POST/PUT/DELETE)
</script>
<script src="https://kit.fontawesome.com/02669f3445.js" crossorigin="anonymous"></script>
</body>
</html>
