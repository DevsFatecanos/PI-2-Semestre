<?php
session_start();
//bloqueia o acesso direto

if (!isset($_SESSION['usuario_email'])) {
    header("Location: login.html");
    exit;
}
$email = $_SESSION['usuario_email'];
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
    <div style="position:absolute;bottom:24px;left:24px;right:24px;font-size:13px;color:var(--muted)">Conectado como:<br><strong><?php echo $email?></strong></div>
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
          <h3 style="margin:8px 0">18</h3>
          <div class="small">Em manutenção: 2</div>
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
        <h3>Aprovar Envio</h3>
        <form id="form-envio" style="margin-top:12px" onsubmit="saveEnvio(event)">
          <div class="row">
            <div style="flex:1">
              <label>Cliente</label>
              <input required placeholder="Nome do cliente" id="envio-cliente" />
            </div>
            <div style="width:160px">
              <label>Placa</label>
              <input required placeholder="ABC-1D23" id="envio-placa" />
            </div>
          </div>

          <div class="row" style="margin-top:12px">
            <div style="flex:1">
              <label>Origem</label>
              <input required id="envio-origem" />
            </div>
            <div style="flex:1">
              <label>Destino</label>
              <input required id="envio-destino" />
            </div>
          </div>

          <div style="margin-top:12px">
            <label>Tipo de carga</label>
            <select id="envio-tipo">
              <option>Geral</option>
              <option>Refrigerado</option>
              <option>Perigosa</option>
            </select>
          </div>

          <div style="margin-top:12px;display:flex;gap:12px;justify-content:flex-end">
            <button type="button" class="btn ghost" onclick="openView('envios')">Cancelar</button>
            <button type="submit" class="btn">Salvar</button>
          </div>
        </form>
      </div>
    </section>

    <section id="veiculos" class="view" style="display:none">
      <div class="card">
        <h3>Veículos</h3>
        <table style="margin-top:12px">
          <thead><tr><th>ID</th><th>Modelo</th><th>Placa</th><th>Status</th><th>Ações</th></tr></thead>
          <tbody>
            <tr><td>V001</td><td>Scania R440</td><td>ABC-1D23</td><td>Disponível</td><td><button class="btn ghost">Editar</button></td></tr>
            <tr><td>V002</td><td>Volvo FH</td><td>XYZ-9F88</td><td>Em rota</td><td><button class="btn ghost">Editar</button></td></tr>
          </tbody>
        </table>
        <div style="margin-top:12px;display:flex;gap:12px;justify-content:flex-end">
        <button class="btn ghost">Remover veículo</button>  
        <button class="btn">Adicionar veículo</button>
          
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

<script>
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

</body>
</html>
