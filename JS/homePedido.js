// Configuração do Supabase (use as mesmas chaves do seu TelaDeFrete.js)
const SUPABASE_URL = "https://oudhyeawauuzvkrhsgsk.supabase.co";
const SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im91ZGh5ZWF3YXV1enZrcmhzZ3NrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA3MTA2OTcsImV4cCI6MjA3NjI4NjY5N30.-SdoeQo9GYcTeaXI7hvHJ9M0-ONVovFpQ1aUbkojCF0";

const db = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

// Garante que o ID do cliente injetado está acessível
const clienteIdLogado = Number(CLIENTE_ID); 

// Função para formatar a data
function formatarData(isoString) {
    if (!isoString) return "Não informado";

    const data = new Date(isoString);
    return data.toLocaleString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit"
    });
}

// =================================================================
// NOVO: Função para buscar pedidos do cliente logado no Supabase
// =================================================================
async function carregarPedidosDoCliente() {
    if (!clienteIdLogado || isNaN(clienteIdLogado)) {
        document.getElementById("resumo-pedidos").innerHTML = "<p style='color:red;'>Erro: ID do cliente não encontrado. Faça login novamente.</p>";
        return;
    }
    
    // 💡 BUSCA FILTRADA: 'eq' (equals) garante que apenas os pedidos do cliente logado venham
    const { data: pedidos, error } = await db
        .from('fretes_solicitados') // Sua tabela de fretes
        .select('*') // Seleciona todas as colunas
        .eq('cliente_id', clienteIdLogado) // ⬅️ FILTRO PRINCIPAL
        .order('data_hora', { ascending: false }); // Ordena do mais novo para o mais antigo

    if (error) {
        console.error("Erro ao carregar pedidos do Supabase:", error);
        document.getElementById("resumo-pedidos").innerHTML = "<p style='color:red;'>Erro ao carregar pedidos.</p>";
        return;
    }

    // Chama a função para renderizar os pedidos
    renderizarPedidos(pedidos);
}

// =================================================================
// FUNÇÃO PARA RENDERIZAR OS PEDIDOS (Antiga lógica de loop)
// =================================================================
function renderizarPedidos(pedidos) {
    const lista = document.getElementById("listaPedidos");
    lista.innerHTML = ''; // Limpa antes de carregar

    if (pedidos.length === 0) {
        document.getElementById("resumo-pedidos").innerHTML =
          "<p style='color:#777;'>Nenhum pedido confirmado ainda para este cliente.</p>";
        return;
    }

    pedidos.forEach((pedido, i) => {
        // --- Lógica de Ícones e Cores (Seu código original) ---
        let iconeStatus = "";
        let statusSpan = document.createElement("span");
        statusSpan.style.padding = "6px 10px";
        statusSpan.style.borderRadius = "8px";
        statusSpan.style.fontWeight = "600";
        statusSpan.style.display = "inline-block";
        statusSpan.style.marginLeft = "6px";
        
        switch (pedido.status) {
            case "Aguardando Aprovação":
                iconeStatus = '<i class="fa-solid fa-hourglass-half" style="color:#f1c40f;"></i>';
                statusSpan.style.backgroundColor = "#fff6d4"; 
                statusSpan.style.color = "#a67c00";
                break;
            case "Aprovado":
                iconeStatus = '<i class="fa-solid fa-circle-check" style="color:#27ae60;"></i>';
                statusSpan.style.backgroundColor = "#d6f5d6"; 
                statusSpan.style.color = "#1b7e1b";
                break;
            // ... (Adicione os outros cases para Em Transporte, Entregue, Cancelado)
            case "Em Transporte":
                 iconeStatus = '<i class="fa-solid fa-truck-fast" style="color:#3498db;"></i>';
                 statusSpan.style.backgroundColor = "#d4e8ff"; 
                 statusSpan.style.color = "#1e6bb8";
                 break;
            case "Entregue":
                 iconeStatus = '<i class="fa-solid fa-box-open" style="color:#2ecc71;"></i>';
                 statusSpan.style.backgroundColor = "#e8f9f0"; 
                 statusSpan.style.color = "#16803a";
                 break;
            case "Cancelado":
                 iconeStatus = '<i class="fa-solid fa-circle-xmark" style="color:#e74c3c;"></i>';
                 statusSpan.style.backgroundColor = "#ffe0e0"; 
                 statusSpan.style.color = "#b33939";
                 break;
            default:
                iconeStatus = '<i class="fa-solid fa-circle-info" style="color:#95a5a6;"></i>';
                statusSpan.style.backgroundColor = "#e0e0e0";
                statusSpan.style.color = "#555";
        }
        statusSpan.innerHTML = `${pedido.status} ${iconeStatus}`;


        // Cria o bloco do pedido
        const div = document.createElement("div");
        div.className = 'pedido-item'; // Adicione uma classe para estilização
        div.style.background = "#f0f6ff";
        div.style.padding = "15px";
        div.style.marginBottom = "10px";
        div.style.border = "1px solid #417dff";
        div.style.borderRadius = "10px";
        div.dataset.pedidoId = pedido.id; // Salva o ID do Supabase para o cancelamento

        // Monta o conteúdo do pedido
        div.innerHTML = `
            <p><b>Pedido #${pedido.id}</b></p>
            <p><b>Status:</b> </p>
        `;
        div.querySelector("p:last-child").appendChild(statusSpan);
        
        // ⚠️ OBS: Adaptei os nomes das propriedades para o que o Supabase provavelmente retorna (usando snake_case)
        div.innerHTML += `
            <p><b>Origem:</b> ${pedido.origem}, Nº ${pedido.numero_origem} ${pedido.complemento_origem || ''}</p>
            <p><b>Destino:</b> ${pedido.destino}, Nº ${pedido.numero_destino} ${pedido.complemento_destino || ''}</p>
            <p><b>Descrição:</b> ${pedido.descricao_carga}</p>
            <p><b>Veículo ID:</b> ${pedido.veiculo_id}</p>
            <p><b>Distância:</b> ${pedido.distancia}</p>
            <p><b>Valor:</b>R$ ${pedido.valor}</p>
            <p><strong>Data:</strong> ${formatarData(pedido.data_hora)}</p>
            <button class="Btn-Cancelar" data-id="${pedido.id}" data-status="${pedido.status}">Cancelar Pedido</button>
        `;

        lista.appendChild(div);
    });
    
    // Inicializa a lógica de cancelamento após a renderização
    inicializarCancelamento();
}

// =================================================================
// NOVO: LÓGICA DE CANCELAMENTO (Chamada no final da renderização)
// =================================================================
function inicializarCancelamento() {
    const modalH = document.getElementById("modalCancelarHome");
    const BtnFecharModal = document.getElementById("cancelarModalBtn");
    const BtnConfirmarCancelar = document.getElementById("confirmarCancelarBtn");

    let pedidoIdParaCancelar = null;

    document.querySelectorAll(".Btn-Cancelar").forEach(btn => {
        btn.addEventListener("click", (e) => {
            const statusAtual = e.target.dataset.status;
            
            if (statusAtual !== "Aguardando Aprovação") {
                alert("🚫 O pedido só pode ser cancelado se estiver 'Aguardando Aprovação'.");
                return;
            }

            // Armazena o ID REAL do Supabase
            pedidoIdParaCancelar = parseInt(e.target.dataset.id); 
            modalH.style.display = "flex";
        });
    });

    BtnFecharModal.addEventListener("click", () => {
        modalH.style.display = "none";
        pedidoIdParaCancelar = null;
    });

    BtnConfirmarCancelar.addEventListener("click", async () => {
        if (!pedidoIdParaCancelar) return;

        // 💡 CANCELAMENTO NO SUPABASE (Atualiza o status, não deleta)
        const { data, error } = await db
            .from('fretes_solicitados')
            .update({ status: 'Cancelado' })
            .eq('id', pedidoIdParaCancelar);

        if (error) {
            console.error("Erro ao cancelar no Supabase:", error);
            alert("❌ Erro ao cancelar pedido.");
        } else {
            alert("✅ Pedido cancelado com sucesso!");
        }
        
        modalH.style.display = "none";
        // Recarrega os pedidos para atualizar a lista
        carregarPedidosDoCliente(); 
    });
}


// 🚀 EXECUTA A BUSCA QUANDO O SCRIPT CARREGA
carregarPedidosDoCliente();