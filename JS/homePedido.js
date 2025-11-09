  const pedidos = JSON.parse(localStorage.getItem("pedidos")) || [];

  if (pedidos.length > 0) {
    const lista = document.getElementById("listaPedidos");



    
    pedidos.forEach((pedido, i) => {
      // Escolhe o ícone com base no status
      let iconeStatus = "";
      switch (pedido.status) {
        case "Aguardando Aprovação":
          iconeStatus = '<i class="fa-solid fa-hourglass-half" style="color:#f1c40f;"></i>';
          break;
        case "Aprovado":
          iconeStatus = '<i class="fa-solid fa-circle-check" style="color:#27ae60;"></i>';
          break;
        case "Em Transporte":
          iconeStatus = '<i class="fa-solid fa-truck-fast" style="color:#3498db;"></i>';
          break;
        case "Entregue":
          iconeStatus = '<i class="fa-solid fa-box-open" style="color:#2ecc71;"></i>';
          break;
        case "Cancelado":
          iconeStatus = '<i class="fa-solid fa-circle-xmark" style="color:#e74c3c;"></i>';
          break;
        default:
          iconeStatus = '<i class="fa-solid fa-circle-info" style="color:#95a5a6;"></i>';
      }

      

// Cria o bloco do pedido
const div = document.createElement("div");
div.style.background = "#f0f6ff";
div.style.padding = "15px";
div.style.marginBottom = "10px";
div.style.border = "1px solid #417dff";
div.style.borderRadius = "10px";

// Cria o elemento de status colorido
const statusSpan = document.createElement("span");
statusSpan.innerHTML = `${pedido.status} ${iconeStatus}`;
statusSpan.style.padding = "6px 10px";
statusSpan.style.borderRadius = "8px";
statusSpan.style.fontWeight = "600";
statusSpan.style.display = "inline-block";
statusSpan.style.marginLeft = "6px";

// Aplica cor conforme o status
switch (pedido.status) {
  case "Aguardando Aprovação":
    statusSpan.style.backgroundColor = "#fff6d4"; // amarelo claro
    statusSpan.style.color = "#a67c00";
    break;
  case "Aprovado":
    statusSpan.style.backgroundColor = "#d6f5d6"; // verde claro
    statusSpan.style.color = "#1b7e1b";
    break;
  case "Em Transporte":
    statusSpan.style.backgroundColor = "#d4e8ff"; // azul claro
    statusSpan.style.color = "#1e6bb8";
    break;
  case "Entregue":
    statusSpan.style.backgroundColor = "#e8f9f0"; // verde suave
    statusSpan.style.color = "#16803a";
    break;
  case "Cancelado":
    statusSpan.style.backgroundColor = "#ffe0e0"; // vermelho claro
    statusSpan.style.color = "#b33939";
    break;
  default:
    statusSpan.style.backgroundColor = "#e0e0e0";
    statusSpan.style.color = "#555";
}

// Monta o conteúdo do pedido
div.innerHTML = `
  <p><b>Pedido #${i + 1}</b></p>
  <p><b>Status:</b> </p>
`;
div.querySelector("p:last-child").appendChild(statusSpan);

div.innerHTML += `
  <p><b>Origem:</b> ${pedido.origem}, Nº ${pedido.numeroOrigem} ${pedido.complementoOrigem}</p>
  <p><b>Destino:</b> ${pedido.destino}, Nº ${pedido.numeroDestino} ${pedido.complementoDestino}</p>
  <p><b>Descrição:</b> ${pedido.descricaoCarga}</p>
  <p><b>Veículo:</b> ${pedido.veiculo}</p>
  <p><b>Distância:</b> ${pedido.distancia}</p>
  <p><b>Valor:</b> ${pedido.valor}</p>
  <p><b>Data:</b> ${pedido.dataHora}</p>
  <button id="Btn-Cancelar">Cancelar Pedido</button>
`;

lista.appendChild(div);

    });
  } else {
    document.getElementById("resumo-pedidos").innerHTML =
      "<p style='color:#777;'>Nenhum pedido confirmado ainda.</p>";
  }


  // MODAL PARA CANCELAR PEDIDOS
  const modalH = document.getElementById("modalCancelarHome");
  const BtnCancerPedido = document.getElementById("Btn-Cancelar");
  const BtnFecharModal = document.getElementById("cancelarModalBtn");
  const BtnConfirmarCancelar = document.getElementById("confirmarCancelarBtn");
  
  BtnCancerPedido.addEventListener("click", () => {
    // Exibe o modal
    modalH.style.display = "flex";
  });

  BtnFecharModal.addEventListener("click",() =>{
    // Fecha o modal
    modalH.style.display = "none";
  });

  BtnConfirmarCancelar.addEventListener("click", () => {
      // Fecha o modal
     modalH.style.display = "none";
     localStorage.clear()
     alert("✅ Pedido Cancelado com sucesso!");
     location.reload();
    
  });