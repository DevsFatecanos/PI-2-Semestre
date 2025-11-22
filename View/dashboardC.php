<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="shortcut icon" href="../Assets/IMG/logo.webp" type="image/x-icon">
  <link rel="stylesheet" href="../Assets/CSS/dashboard.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <title>Admin - SuperSonic Transportes</title>
  <!-- Estilos simples embutidos para facilitar uso sem dependências -->
  <style>

  </style>
</head>

<body>
  
  <!--MODAL DE MAPS EM CADA CARD-->
  <a id="btnFecharModal" class="close-button hidden">&times;</a>
<div id="modalMapaRota" class="modal-overlay hidden"> 
    <div class="modal-conteudo">
      

        <h2 style="color: #fff; margin-bottom: 15px;">
            Detalhes da Rota <span id="modalRotaId"></span>
        </h2>
        
        <p style="color: #ccc;">
            Origem: <strong id="modalOrigemRota"></strong>
        </p>
        <p style="color: #ccc; margin-bottom: 15px;">
            Destino: <strong id="modalDestinoRota"></strong>
        </p>

        <div id="mapaModal"></div>
    </div>
</div>

<style>
/* FUNDO ESCURO */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9000;
}

/* CONTEÚDO DO MODAL */
.modal-conteudo {
    background: var(--card);
    width: 90%;
    max-width: 800px;
    padding: 20px;
    border-radius: 10px;
    position: relative;
    z-index: 12000; /* GARANTE QUE FICA ACIMA DO MAPA */
}

/* OCULTAR MODAL */
.hidden {
    display: none !important;
}

/* BOTÃO FECHAR */
.close-button {
    position: absolute;
    top: 10px;
    right: 15px;
    color: #fff;
    font-size: 35px;
    font-weight: bold;
    cursor: pointer;
    z-index: 15000; /* SEMPRE POR CIMA */
    background: transparent;
    line-height: 1;
}

.close-button:hover {
    color: #ff4d4d;
}

/* MAPA */
#mapaModal {
    height: 500px;
    width: 100%;
    border-radius: 8px;
    margin-top: 10px;
    position: relative;
    z-index: 1 !important;
}
</style>

<!--FIM DO MODAL MAPS-->

<!--MODAL DE ATUALIZAR FRETE-->

<div id="modalEditarFrete" class="modal">
  <div class="modal-conteudo2">
    <span class="fechar2" onclick="fecharModal2()">&times;</span>

    <h2>Editar Frete</h2>

    <label>Novo Valor (R$)</label>
    <input type="number" id="modalValor" placeholder="Digite o novo valor">

    <label>Veículo</label>
    <select id="modalVeiculo"></select>

    <button id="btnSalvarAlteracoes" class="btn ghost" style="margin-top: 20px;">
      Salvar Alterações
    </button>
  </div>
</div>

<style>
.modal {
  display: none;
  position: fixed;
  z-index: 99999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6);
}
.modal-conteudo2 {
  background:black;
  width: 350px;
  margin: 10% auto;
  padding: 20px;
  border-radius: 10px;
}
.fechar2 {
  float: right;
  font-size: 25px;
  cursor: pointer;
}

#modalEditarFrete select,
#modalEditarFrete input {
  background: #0f0f0f;
  color: #ffffff;
  border: 1px solid #444;
  padding: 8px;
  border-radius: 6px;
  width: 100%;
}

#modalEditarFrete select option {
  background: #0f0f0f;
  color: #fff;
}
</style>

<script>
let freteAtual = null;
let emailAtual = null;
let nomeAtual = null;


async function carregarVeiculos() {
  const res = await fetch(`${SUPABASE_URL}/rest/v1/veiculo?select=id_veiculo,modelo`, {
    headers: {
      "apikey": SUPABASE_KEY,
      "Authorization": `Bearer ${SUPABASE_KEY}`
    }
  });

  return await res.json();
}

async function abrirModalEditar(idFrete, valor, veiculo_id, email, nome) {
  freteAtual = idFrete;
  emailAtual = email;
  nomeAtual = nome;

  // Valor atual
  document.getElementById("modalValor").value = valor;

  // Carregar veículos e preencher select
  const veiculos = await carregarVeiculos();
  const select = document.getElementById("modalVeiculo");

  select.innerHTML = veiculos
    .map(v => `
      <option value="${v.id_veiculo}" ${v.id_veiculo == veiculo_id ? "selected" : ""}>
        ${v.modelo}
      </option>
    `)
    .join("");

  // Abrir modal
  document.getElementById("modalEditarFrete").style.display = "block";
}

function fecharModal2() {
  document.getElementById("modalEditarFrete").style.display = "none";
}


// 🔥 SALVAR ALTERAÇÕES
document.getElementById("btnSalvarAlteracoes").onclick = async function () {
  const novoValor = Number(document.getElementById("modalValor").value);
  const novoVeiculo = Number(document.getElementById("modalVeiculo").value);

  if (!novoValor || !novoVeiculo) {
    alert("Preencha todos os campos!");
    return;
  }

  // Atualizar no Supabase
  const res = await fetch(`${SUPABASE_URL}/rest/v1/fretes_solicitados?id=eq.${freteAtual}`, {
    method: "PATCH",
    headers: {
      "apikey": SUPABASE_KEY,
      "Authorization": `Bearer ${SUPABASE_KEY}`,
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      valor: novoValor,
      veiculo_id: novoVeiculo
    })
  });

  if (!res.ok) {
    alert("Erro ao atualizar frete.");
    return;
  }

  fecharModal2();
  alert("Frete atualizado com sucesso!");

  // Recarrega lista
  carregarFretes();

  // Notificar cliente
  await fetch("/PI-2-Semestre/notificacao.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      email: emailAtual,
      nome: nomeAtual,
      pedido: freteAtual,
      status: "Nova Proposta de Frete, Verifique seu Pedido na Home, entraremos em contato para mais Informações ",
      valor: novoValor,
      dataHora: new Date().toLocaleString("pt-BR")
    })
  });
};
</script>
<!--FIM DO MODAL DE ATUALIZAR-->



<div class="app">
  <aside class="sidebar card">
    <div class="brand"><div class="logo" style="display: flex; justify-content: center; align-items: center;"><img src="../Assets/IMG/logo.webp" alt="Logo SuperSonic Transportes" style="width: 20px; height: 20px;"></div>SuperSonic Transportes<br><span class="small">Admin</span></div>
    <nav class="nav" id="menu">
      <a href="#" data-view="dashboard" class="active">Dashboard</a>
      <a href="#" data-view="criar-envio">Lista de Pedidos</a>
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
<section id="criar-envio" class="view" style="display:none">
  <div class="card">
    <h3>Pedidos Ativos</h3>
        <div style="display:flex; gap:10px; margin-bottom:15px;">
          <button class="btn ghost" onclick="filtrarStatus('Todos')">Todos</button>
          <button class="btn ghost" onclick="filtrarStatus('Aguardando Aprovação')">Aguardando</button>
          <button class="btn ghost" onclick="filtrarStatus('Aprovado')">Aprovado</button>
          <button class="btn ghost" onclick="filtrarStatus('Em Transporte')">Em Transporte</button>
          <button class="btn ghost" onclick="filtrarStatus('Entregue')">Entregue</button>
          <button class="btn ghost" onclick="filtrarStatus('Recusado')">Recusado</button>
       </div>
       <div id="listaAprovarContainer" class="scrollArea">
        <div id="listaFretes" style="margin-top:20px;"></div>
      </div>
</section>


<!--CSS DA BARRA DE ROLAGEM EM APROVAR FRETES-->
<style>
  .scrollArea {
    max-height: calc(100vh - 220px); 
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

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

const GEOAPIFY_KEY = "f68c5677fcb64b719fe631b6288e2a1d"; 
let mapaAtual = null;       
let camadaDesenhos = null;  

// 1. FECHAR MODAL
function fecharModal() {
    const modal = document.getElementById('modalMapaRota');
    const modal2 = document.getElementById('btnFecharModal');
    modal.classList.add('hidden');
    modal2.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnFecharModal')
        ?.addEventListener('click', fecharModal);

    document.getElementById('modalMapaRota')
        ?.addEventListener('click', (e) => {
            if (e.target.id === 'modalMapaRota') fecharModal();
        });
});

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnFecharModal')?.addEventListener('click', fecharModal);
    document.getElementById('modalMapaRota')?.addEventListener('click', (e) => {
        if (e.target.id === 'modalMapaRota') fecharModal();
    });
});

// 2. ABRIR MODAL

window.abrirModalMapa = async function(idFrete, origem, destino) {
    const modal = document.getElementById('modalMapaRota');
    const modal2 = document.getElementById('btnFecharModal');
    // 1. Mostra o modal primeiro
    modal.classList.remove('hidden');
    modal2.classList.remove('hidden');

    // 2. Atualiza Textos
    document.getElementById('modalRotaId').innerText = `#${idFrete}`;
    document.getElementById('modalOrigemRota').innerText = origem;
    document.getElementById('modalDestinoRota').innerText = destino;

    // 3. Lógica do Mapa (Singleton)
    const mapDiv = document.getElementById('mapaModal');

    // Se o mapa AINDA NÃO EXISTE, cria ele (acontece só na 1ª vez)
    if (!mapaAtual) {
        mapaAtual = L.map('mapaModal').setView([-23.55, -46.63], 10); // Inicia em SP
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(mapaAtual);

        // Cria o GRUPO onde vamos desenhar (facilita limpar depois)
        camadaDesenhos = L.layerGroup().addTo(mapaAtual);
    } 
    else {
        // Se JÁ EXISTE, precisamos "acordar" ele porque estava escondido
        setTimeout(() => {
            mapaAtual.invalidateSize(); // Corrige o erro do mapa cinza
        }, 10);
        
        // LIMPA a rota anterior (remove pinos e linhas velhas)
        if (camadaDesenhos) {
            camadaDesenhos.clearLayers(); 
        }
    }

    // 4. Busca a Nova Rota
    const coordsOrigem = await geocodeAddress(origem);
    const coordsDestino = await geocodeAddress(destino);

    if (!coordsOrigem || !coordsDestino) {
        alert("Endereço não encontrado para traçar rota.");
        return;
    }

    // Adiciona marcadores NO GRUPO (não direto no mapa)
    const m1 = L.marker(coordsOrigem).bindPopup("Origem").openPopup();
    const m2 = L.marker(coordsDestino).bindPopup("Destino");
    
    camadaDesenhos.addLayer(m1);
    camadaDesenhos.addLayer(m2);

    // Centraliza para mostrar os dois pontos enquanto a linha carrega
    const grupoLimites = new L.LatLngBounds([coordsOrigem, coordsDestino]);
    mapaAtual.fitBounds(grupoLimites, { padding: [50, 50] });

    // Puxa a linha da rota
    try {
        const [latO, lonO] = coordsOrigem;
        const [latD, lonD] = coordsDestino;
        const url = `https://router.project-osrm.org/route/v1/driving/${lonO},${latO};${lonD},${latD}?overview=full&geometries=geojson`;
        
        const resp = await fetch(url);
        const dados = await resp.json();

        if (dados.routes && dados.routes.length > 0) {
            // Cria a linha azul
            const linhaRota = L.geoJSON(dados.routes[0].geometry, {
                style: { color: "#417dff", weight: 5 }
            });
            
            // Adiciona a linha NO GRUPO
            camadaDesenhos.addLayer(linhaRota);
            
            // Ajusta o zoom final
            mapaAtual.fitBounds(linhaRota.getBounds(), { padding: [50, 50] });
        }
    } catch (e) {
        console.error("Erro na rota:", e);
    }
};


// 3. AUXILIAR (Geocoding)

async function geocodeAddress(address) {
    try {
        const url = `https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(address)}&lang=pt&filter=countrycode:br&limit=1&apiKey=${GEOAPIFY_KEY}`;
        const r = await fetch(url);
        const d = await r.json();
        if (d.features?.length) {
            const c = d.features[0].geometry.coordinates;
            return [c[1], c[0]];
        }
    } catch (e) { console.error(e); }
    return null;
}
</script>



<script>
const SUPABASE_URL = "https://oudhyeawauuzvkrhsgsk.supabase.co";
const SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im91ZGh5ZWF3YXV1enZrcmhzZ3NrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA3MTA2OTcsImV4cCI6MjA3NjI4NjY5N30.-SdoeQo9GYcTeaXI7hvHJ9M0-ONVovFpQ1aUbkojCF0";

let fretesCarregados = [];

function carregarFretes() {
    fetch(`${SUPABASE_URL}/rest/v1/fretes_solicitados?select=*,usuario:cliente_id(*),veiculo:veiculo_id(modelo)`, {
        headers: {
            "apikey": SUPABASE_KEY,
            "Authorization": `Bearer ${SUPABASE_KEY}`
        }
    })
    .then(r => r.json())
    .then(dados => {
        fretesCarregados = dados;
        mostrarFretes(dados);
    });
}

function filtrarStatus(status) {
    if (status === "Todos") {
        mostrarFretes(fretesCarregados);
    } else {
        const filtrado = fretesCarregados.filter(f => f.status === status);
        mostrarFretes(filtrado);
    }
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


        // 1. DEFINIR COR DO STATUS

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
                color = "#167980ff";
                icone = '<i class="fa-solid fa-box-open" style="color:#2ecc71;"></i>';
                break;

            case "Recusado":
                bg = "#ffe0e0";
                color = "#b33939";
                icone = '<i class="fa-solid fa-circle-xmark" style="color:#e74c3c;"></i>';
                break;

            default:
                bg = "#e0e0e0";
                color = "#555";
                icone = '<i class="fa-solid fa-circle-info" style="color:#95a5a6;"></i>';
        }

      // Formatação da Numero de Telefone

      function formatarTelefone(numero) {
    if (!numero) return "—";

    // Remove qualquer coisa que não seja número
    numero = numero.toString().replace(/\D/g, "");

    if (numero.length === 11) {
        return `(${numero.slice(0, 2)}) ${numero.slice(2, 7)}-${numero.slice(7)}`;
    }
    if (numero.length === 10) {
        return `(${numero.slice(0, 2)}) ${numero.slice(2, 6)}-${numero.slice(6)}`;
    }

    return numero; // fallback
}


        // 2. Criação dos CARDs

        const mapaId = `mapa-frete-${frete.id}`;

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
                <p><strong>Cliente:</strong> ${user.nome || "—"} — ${formatarTelefone(user.telefone) || ""}</p>
                <p><strong>Email:</strong> ${user.email || ""}</p>

                <p style="margin-top:10px"><strong>Origem:</strong> ${frete.origem},<br> Nº ${frete.numero_origem} ${frete.complemento_origem}</p>
                <p><strong>Destino:</strong> ${frete.destino},<br> Nº ${frete.numero_destino} ${frete.complemento_destino}</p>

                <p style="margin-top:10px"><strong>Veículo:</strong> ${veic.modelo}</p>
                <p><strong>Carga:</strong> ${frete.descricao_carga}</p>
                <p><strong>Distância:</strong> ${frete.distancia}</p>
                <p><strong>Valor: R$</strong> ${frete.valor}</p>
                <p style="margin-top:10px"><strong>Data/Hora:</strong> ${new Date(frete.data_hora).toLocaleString("pt-BR")}</p>
                
            </div>

            <div style="margin-top:18px; display:flex; gap:10px;">
              <button onclick="atualizarStatus(${frete.id}, 'Aprovado', '${user.email}', '${user.nome}')"class = "btn ghost" style="border:1px solid #1b7e1b; color:#1b7e1b; ">Aprovar</button>
              <button onclick="atualizarStatus(${frete.id}, 'Recusado', '${user.email}', '${user.nome}')"
                class="btn ghost" style="border:1px solid #ff4d4d;color:#ff6b6b;">Recusar</button>
              <button onclick="atualizarStatus(${frete.id}, 'Em Transporte', '${user.email}', '${user.nome}')"class = "btn ghost" style = " border:1px solid #1e6bb8; color:#1e6bb8; " >Em Transporte</button>
              <button onclick="atualizarStatus(${frete.id}, 'Entregue', '${user.email}', '${user.nome}')"class = "btn ghost" style = " border:1px solid #167980ff; color:#167980ff; " >Entregue</button>    
              <button onclick="abrirWhats('${user.telefone}')" class="btn ghost" style="border:1px solid #25D366;color:#25D366;">WhatsApp</button>
              <button onclick="abrirModalMapa(${frete.id}, '${frete.origem}', '${frete.destino}')" class="btn ghost" style="border: 1px solid #ff7300ff; color: #ff7300ff;"><i class="fa-solid fa-map-location-dot"></i> Ver Rota</button>
              <button class="btn" onclick="abrirModalEditar(${frete.id}, ${frete.valor}, ${frete.veiculo_id}, '${user.email}', '${user.nome}')"> Editar Frete</button>
              </div>
            
        `;

        div.appendChild(card);
        
    });

}

  // Abrir conversa via whatzapp

  function abrirWhats(telefone) {
    if (!telefone) return alert("Número indisponível");
    const link = `https://wa.me/55${telefone}`;
    window.open(link, "_blank");
}

async function atualizarStatus(id, status, email, nome) {

    await fetch(`${SUPABASE_URL}/rest/v1/fretes_solicitados?id=eq.${id}`, {
        method: "PATCH",
        headers: {
            "apikey": SUPABASE_KEY,
            "Authorization": `Bearer ${SUPABASE_KEY}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ status })
    });

    // chamar função de notificação
await fetch("/PI-2-Semestre/notificacao.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
        email,
        nome,
        pedido: id,
        status,
        dataHora: new Date().toLocaleString("pt-BR")
    })
});


    carregarFretes();
}

function aprovar(id, nomeCliente, emailCliente) {
    // atualizar status via fetch ou supabase
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
        notificarClienteEmail(id,nomeCliente, emailCliente, "Aprovado");
    });
}

function recusar(id, nomeCliente, emailCliente) {
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
        notificarClienteEmail(id,nomeCliente, emailCliente, "Recusado");
    });
}

function EmTransporte(id, nomeCliente, emailCliente) {
    fetch(`${SUPABASE_URL}/rest/v1/fretes_solicitados?id=eq.${id}`, {
        method: "PATCH",
        headers: {
            "apikey": SUPABASE_KEY,
            "Authorization": `Bearer ${SUPABASE_KEY}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ status: "Em Transporte" })
    }).then(() => {
        alert("Frete Em transporte!");
        carregarFretes();
        notificarClienteEmail(id,nomeCliente, emailCliente, "Em Transporte");
    });
}

function Entregue(id, nomeCliente, emailCliente) {
    fetch(`${SUPABASE_URL}/rest/v1/fretes_solicitados?id=eq.${id}`, {
        method: "PATCH",
        headers: {
            "apikey": SUPABASE_KEY,
            "Authorization": `Bearer ${SUPABASE_KEY}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ status: "Entregue" })
    }).then(() => {
        alert("Pedido Entregue!");
        carregarFretes();
        notificarClienteEmail(id,nomeCliente, emailCliente, "Entregue");
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
        <tr><th>ID</th><th>Modelo</th><th>Placa</th><th>Preço Por KM</th><th>Status</th><th>Ações</th></tr>
      </thead>
      <tbody>
        <?php foreach ($veiculos as $v): ?>
          <tr>
            <td><?= htmlspecialchars($v->id_veiculo) ?></td>
            <td><?= htmlspecialchars($v->modelo) ?></td>
            <td><?= htmlspecialchars($v->placa) ?></td>
            <td><?= htmlspecialchars($v->valor_por_km) ?></td>
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
<div id="modalRemover" class="modal" style="display:none">
    <div class="modal-content">
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
