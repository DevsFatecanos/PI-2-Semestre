  const ultimoPedido = localStorage.getItem("ultimoPedido");

  if (ultimoPedido) {
    const dados = JSON.parse(ultimoPedido);
    document.getElementById("resumo-pedido").style.display = "block";

    // Escolhe o ícone com base no status
    let iconeStatus = "";
    switch (dados.status) {
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

    // Mostra o status com ícone
    document.getElementById("resumoStatus").innerHTML = ` ${dados.status} ${iconeStatus}`;

    // Preenche o restante dos dados
    document.getElementById("resumoOrigem").textContent = dados.origem;
    document.getElementById("resumoNumOrigem").textContent = dados.numeroOrigem;
    document.getElementById("resumoComplOrigem").textContent = dados.complementoOrigem;
    document.getElementById("resumoDestino").textContent = dados.destino;
    document.getElementById("resumoNumDestino").textContent = dados.numeroDestino;
    document.getElementById("resumoComplDestino").textContent = dados.complementoDestino;
    document.getElementById("resumoDescricao").textContent = dados.descricaoCarga;
    document.getElementById("resumoVeiculo").textContent = dados.veiculo;
    document.getElementById("resumoDistancia").textContent = dados.distancia;
    document.getElementById("resumoValor").textContent = dados.valor;
    document.getElementById("resumoData").textContent = dados.dataHora;
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