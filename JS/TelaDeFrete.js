// Inicializa o mapa
const map = L.map('map').setView([-23.55, -46.63], 11);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
}).addTo(map);

let marcadorOrigem, marcadorDestino, rotaLayer;
let coordenadas = { origem: null, destino: null };

// --- AUTOCOMPLETE COM FILTRO PARA REGIÃO METROPOLITANA DE SÃO PAULO ---
async function autocomplete(inputId, sugestoesId, tipo) {
  const input = document.getElementById(inputId);
  const sugestoesDiv = document.getElementById(sugestoesId);

  input.addEventListener("input", async () => {
    const query = input.value.trim();
    sugestoesDiv.innerHTML = "";

    if (query.length < 3) return;

    // 🔹 Busca limitada à Região Metropolitana de SP
    const url = `https://nominatim.openstreetmap.org/search?format=json&limit=15&addressdetails=1&bounded=1&viewbox=-47.5,-23.2,-46.2,-24.1&q=${encodeURIComponent(query)}`;

    try {
      const resposta = await fetch(url, {
        headers: { 'Accept-Language': 'pt-BR' }
      });
      const dados = await resposta.json();

      if (dados.length === 0) {
        const item = document.createElement("div");
        sugestoesDiv.appendChild(item);
        return;
      }

      dados.forEach(lugar => {
let display = lugar.display_name;

// Remove pedaços longos e genéricos
display = display
  .replace(/Região Imediata de [^,]+,?/g, "")
  .replace(/Região Metropolitana de [^,]+,?/g, "")
  .replace(/Região Geográfica Intermediária de [^,]+,?/g, "")
  .replace(/Região Sudeste,?/g, "")
  .replace(/São Paulo,?/g, "")
  .replace(/Brasil,?/g, "")
  .replace(/,+/g, ",") // remove vírgulas duplicadas
  .trim();

// Se sobrar uma vírgula no fim, tira
if (display.endsWith(",")) display = display.slice(0, -1);

// Cria o item da sugestão
const item = document.createElement("div");
item.textContent = display;

        item.onclick = () => {
          input.value = display;
          sugestoesDiv.innerHTML = "";

          const lat = parseFloat(lugar.lat);
          const lon = parseFloat(lugar.lon);
          coordenadas[tipo] = [lat, lon];

          if (tipo === "origem") {
            if (marcadorOrigem) map.removeLayer(marcadorOrigem);
            marcadorOrigem = L.marker([lat, lon])
              .addTo(map)
              .bindPopup("Origem")
              .openPopup();
          } else {
            if (marcadorDestino) map.removeLayer(marcadorDestino);
            marcadorDestino = L.marker([lat, lon])
              .addTo(map)
              .bindPopup("Destino")
              .openPopup();
          }

          map.setView([lat, lon], 14);
        };

        sugestoesDiv.appendChild(item);
      });
    } catch (erro) {
      console.error("Erro ao buscar endereços:", erro);
    }
  });
}

// Inicializa autocomplete para origem e destino
autocomplete("origem", "sugestoes-origem", "origem");
autocomplete("destino", "sugestoes-destino", "destino");


// ===== CARROSSEL DE VEÍCULOS =====
let precoPorKm = null;
let veiculoSelecionado = null; // 🔹 Armazena o veículo escolhido
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
      document.getElementById("valorSpan").textContent = `R$ ${valorFrete}`;
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

// Coleta os dados do pedido
const pedido = {
  origem: document.getElementById("origem").value,
  numeroOrigem: document.querySelectorAll("#input_numero")[0]?.value || "",
  complementoOrigem: document.querySelectorAll("#input_complemento")[0]?.value || "",
  destino: document.getElementById("destino").value,
  numeroDestino: document.querySelectorAll("#input_numero")[1]?.value || "",
  complementoDestino: document.querySelectorAll("#input_complemento")[1]?.value || "",
  descricaoCarga: document.querySelector("textarea[name='descrição_Carga']")?.value || "",
  distancia: document.getElementById("distanciaSpan").textContent,
  valor: document.getElementById("valorSpan").textContent,
  veiculo: document.querySelector(".veiculo.selecionado")?.querySelector("p").innerText.split("\n")[0] || "Não selecionado",
  status: "Aguardando Aprovação",
  dataHora: new Date().toLocaleString("pt-BR")
};

// Recupera os pedidos existentes (ou cria um array vazio)
let pedidos = JSON.parse(localStorage.getItem("pedidos")) || [];

// Adiciona o novo pedido ao array
pedidos.push(pedido);

// Salva de volta no localStorage
localStorage.setItem("pedidos", JSON.stringify(pedidos));

// Mensagem de sucesso
alert("✅ Pedido confirmado com sucesso!");

// Redireciona após 1 segundo
setTimeout(() => {
  window.location.href = "home.html";
}, 1000);

});
