// Inicializa o mapa
const map = L.map('map').setView([-23.55, -46.63], 11);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
}).addTo(map);

let marcadorOrigem, marcadorDestino, rotaLayer;
let coordenadas = { origem: null, destino: null };

// --- AUTOCOMPLETE COM FILTRO PARA REGIÃO METROPOLITANA DE SÃO PAULO ---

const GEOAPIFY_KEY = "f68c5677fcb64b719fe631b6288e2a1d"; // 🔹 coloque sua chave aqui
let timer;

async function autocomplete(inputId, sugestoesId, tipo) {
  const input = document.getElementById(inputId);
  const sugestoesDiv = document.getElementById(sugestoesId);

  input.addEventListener("input", () => {
    clearTimeout(timer);
    const query = input.value.trim();
    sugestoesDiv.innerHTML = "";

    if (query.length < 3) return;

    timer = setTimeout(async () => {
      try {
        const url = `https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(query)}&lang=pt&filter=countrycode:br&limit=10&apiKey=${GEOAPIFY_KEY}`;
        const resposta = await fetch(url);
        const dados = await resposta.json();

        sugestoesDiv.innerHTML = "";

        if (!dados.features || dados.features.length === 0) {
          const item = document.createElement("div");
          item.textContent = "Nenhum endereço encontrado";
          item.style.color = "#777";
          sugestoesDiv.appendChild(item);
          return;
        }

        dados.features.forEach(lugar => {
          const display = lugar.properties.formatted;
          const lat = lugar.geometry.coordinates[1];
          const lon = lugar.geometry.coordinates[0];

          const item = document.createElement("div");
          item.textContent = display;

          item.onclick = () => {
            input.value = display;
            sugestoesDiv.innerHTML = "";
            coordenadas[tipo] = [lat, lon];

            if (tipo === "origem") {
              if (marcadorOrigem) map.removeLayer(marcadorOrigem);
              marcadorOrigem = L.marker([lat, lon]).addTo(map).bindPopup("Origem").openPopup();
            } else {
              if (marcadorDestino) map.removeLayer(marcadorDestino);
              marcadorDestino = L.marker([lat, lon]).addTo(map).bindPopup("Destino").openPopup();
            }

            map.setView([lat, lon], 13);
          };

          sugestoesDiv.appendChild(item);
        });
      } catch (erro) {
        console.error("Erro no autocomplete:", erro);
      }
    }, 400);
  });
}

// Inicializa autocomplete para origem e destino
autocomplete("origem", "sugestoes-origem", "origem");
autocomplete("destino", "sugestoes-destino", "destino");


// ===== CARROSSEL DE VEÍCULOS =====
let precoPorKm = null;
let veiculoSelecionado = null; // 🔹 Armazena o veículo escolhido
let veiculoId = null;
const listaVeiculos = document.getElementById('listaVeiculos');
const btnVoltar = document.getElementById('voltar');
const btnAvancar = document.getElementById('avancar');
const veiculos = document.querySelectorAll('.veiculo');

// 🔹 Rolagem do carrossel
btnAvancar.addEventListener('click', () => {
  listaVeiculos.scrollBy({ left: 200, behavior: 'smooth' });
});

btnVoltar.addEventListener('click', () => {
  listaVeiculos.scrollBy({ left: -200, behavior: 'smooth' });
});

// 🔹 Selecionar veículo
veiculos.forEach(v => {
  v.addEventListener('click', () => {
    veiculos.forEach(outro => outro.classList.remove('selecionado'));
    v.classList.add('selecionado');
    precoPorKm = parseFloat(v.dataset.preco);
    veiculoId = parseInt(v.dataset.id);
    veiculoSelecionado = v; // guarda o veículo escolhido
  });
});

// 🔹 Função de cálculo da rota (ajustada)
async function tracarRota() {
  if (!coordenadas.origem || !coordenadas.destino) {
    alert("Selecione origem e destino!");
    return;
  }

  if (precoPorKm === null) {
    alert("Selecione um tipo de veículo antes de calcular o frete!");
    return;
  }

  const [latO, lonO] = coordenadas.origem;
  const [latD, lonD] = coordenadas.destino;

  const url = `https://router.project-osrm.org/route/v1/driving/${lonO},${latO};${lonD},${latD}?overview=full&geometries=geojson`;

  try {
    const resposta = await fetch(url);
    const dados = await resposta.json();

    if (dados.routes && dados.routes.length > 0) {
      const rota = dados.routes[0];

      if (rotaLayer) map.removeLayer(rotaLayer);
      rotaLayer = L.geoJSON(rota.geometry, {
        style: { color: "#417dff", weight: 4 }
      }).addTo(map);

      map.fitBounds(rotaLayer.getBounds());

      const distanciaKm = (rota.distance / 1000).toFixed(2);
      const valorFrete = (distanciaKm * precoPorKm).toFixed(2);

      // Exibir resultado
      const divFrete = document.getElementById("precoFrete");
      document.getElementById("distanciaSpan").textContent = `${distanciaKm} km`;
      document.getElementById("valorSpan").textContent = ` ${valorFrete}`;
      divFrete.style.display = "flex";

      // 🔹 Mantém o veículo visualmente selecionado
      if (veiculoSelecionado) {
        veiculos.forEach(v => v.classList.remove('selecionado'));
        veiculoSelecionado.classList.add('selecionado');
      }
    } else {
      alert("Não foi possível calcular a rota.");
    }
  } catch (erro) {
    console.error("Erro ao calcular rota:", erro);
    alert("Erro ao calcular rota.");
  }
}

// ======== Modal de Confirmação ========

const modal = document.getElementById("modalConfirmacao");
const btnConfirmar = document.getElementById("btnConfirmar");
const btnCancelarModal = document.getElementById("cancelarModalBtn");
const btnFinalizarPedido = document.getElementById("confirmarPedidoBtn");

btnConfirmar.addEventListener("click", () => {
  // Preenche os dados do modal com as informações atuais
  document.getElementById("modalOrigem").textContent = document.getElementById("origem").value;
  document.getElementById("modalDestino").textContent = document.getElementById("destino").value;
  document.getElementById("modalDistancia").textContent = document.getElementById("distanciaSpan").textContent;
  document.getElementById("modalValor").textContent = document.getElementById("valorSpan").textContent;

  // Mostra o veículo selecionado
  const veiculoSelecionado = document.querySelector(".veiculo.selecionado");
  document.getElementById("modalVeiculo").textContent = veiculoSelecionado 
    ? veiculoSelecionado.querySelector("p").innerText.split("\n")[0]
    : "Não selecionado";

  // Exibe o modal
  modal.style.display = "flex";
});

// Fecha o modal ao clicar em cancelar
btnCancelarModal.addEventListener("click", () => {
  modal.style.display = "none";
});

// Ao confirmar o pedido
btnFinalizarPedido.addEventListener("click", () => {
  // Fecha o modal
  modal.style.display = "none";

const veiculoElemento = document.querySelector(".veiculo.selecionado");
  const veiculoNome = veiculoElemento?.querySelector("p").innerText.split("\n")[0] || "Não selecionado";
  const veiculoId = parseInt(veiculoElemento?.dataset.id || 0); // ✅ ID do veículo (vindo do data-id)


// Coleta os dados do pedido
const pedido = {
  origem: document.getElementById("origem").value,
  numero_origem: document.querySelectorAll("#input_numero")[0]?.value || "",
  complemento_origem: document.querySelectorAll("#input_complemento")[0]?.value || "",
  destino: document.getElementById("destino").value,
  numero_destino: document.querySelectorAll("#input_numero")[1]?.value || "",
  complemento_destino: document.querySelectorAll("#input_complemento")[1]?.value || "",
  descricao_carga: document.querySelector("textarea[name='descrição_Carga']")?.value || "",
  distancia: document.getElementById("distanciaSpan").textContent,
  valor: document.getElementById("valorSpan").textContent,
  veiculo_id: veiculoId, 
  status: "Aguardando Aprovação",
  data_hora: new Date().toISOString()
};

  pedido.veiculo_nome = veiculoNome;
// Recupera os pedidos existentes (ou cria um array vazio)
let pedidos = JSON.parse(localStorage.getItem("pedidos")) || [];

// Adiciona o novo pedido ao array
pedidos.push(pedido);

// Salva de volta no localStorage
localStorage.setItem("pedidos", JSON.stringify(pedidos));
salvarPedidoNoSupabase(pedido);

// Mensagem de sucesso
alert("✅ Pedido confirmado com sucesso!");

// Redireciona após 1 segundo
setTimeout(() => {
  window.location.href = "home.html";
}, 1000);

});



async function salvarPedidoNoSupabase(pedido) {
  const SUPABASE_URL = "https://oudhyeawauuzvkrhsgsk.supabase.co";
  const SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im91ZGh5ZWF3YXV1enZrcmhzZ3NrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA3MTA2OTcsImV4cCI6MjA3NjI4NjY5N30.-SdoeQo9GYcTeaXI7hvHJ9M0-ONVovFpQ1aUbkojCF0";
  
  // 🔹 envia apenas os campos válidos para o banco
  const pedidoSupabase = {
    id_cliente: pedido.id_cliente,
    origem: pedido.origem,
    numero_origem: pedido.numero_origem,
    complemento_origem: pedido.complemento_origem,
    destino: pedido.destino,
    numero_destino: pedido.numero_destino,
    complemento_destino: pedido.complemento_destino,
    descricao_carga: pedido.descricao_carga,
    distancia: pedido.distancia,
    valor: pedido.valor,
    veiculo_id: pedido.veiculo_id,
    status: pedido.status,
    data_hora: pedido.data_hora
  };


  try {
    const resposta = await fetch(`${SUPABASE_URL}/rest/v1/fretes_solicitados`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "apikey": SUPABASE_KEY,
        "Authorization": `Bearer ${SUPABASE_KEY}`,
        "Prefer": "return=representation" // Retorna o registro criado
      },
      body: JSON.stringify(pedidoSupabase)
    });

    if (!resposta.ok) {
      const erro = await resposta.text();
      console.error("⚠️ Erro ao salvar no Supabase:", erro);
      alert("Erro ao salvar o pedido no servidor!");
      return;
    }

    const resultado = await resposta.json();
    console.log("✅ Pedido salvo com sucesso no Supabase:", resultado);
  } catch (erro) {
    console.error("❌ Erro de conexão com o Supabase:", erro);
    alert("Falha ao conectar ao banco de dados!");
  }
}




