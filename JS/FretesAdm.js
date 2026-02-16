
async function carregarPedidosAguardando() {

    const resposta = await fetch(
        `${SUPABASE_URL}/rest/v1/fretes_solicitados?status=eq.Aguardando%20Aprovação&select=*,clientes(*)`,
        {
            headers: {
                "apikey": SUPABASE_KEY,
                "Authorization": `Bearer ${SUPABASE_KEY}`
            }
        }
    );

    const pedidos = await resposta.json();
    console.log("PEDIDOS:", pedidos);

    mostrarPedidosAprovar(pedidos);
}




function mostrarPedidosAprovar(pedidos) {
    const div = document.getElementById("listaAprovar");
    div.innerHTML = "";

    if (pedidos.length === 0) {
        div.innerHTML = "<p>Nenhum pedido aguardando aprovação.</p>";
        return;
    }

    pedidos.forEach((p) => {

        const cliente = p.clientes || {}; // evita erros

        const card = document.createElement("div");
        card.classList.add("pedido-card");
        card.style.cssText = `
            border:1px solid #cbd6ff;
            background:#f4f7ff;
            padding:15px;
            border-radius:10px;
            margin-bottom:15px;
            font-size:15px;
        `;

        card.innerHTML = `
            <p><strong>ID do Pedido:</strong> ${p.id}</p>
            <p><strong>Status:</strong> <span style="background:#ffe9a3;padding:3px 8px;border-radius:6px;">
                ${p.status} <i class="fa-solid fa-hourglass-half"></i>
            </span></p>

            <p><strong>Cliente:</strong> ${cliente.nome || "Não informado"}</p>
            <p><strong>Email:</strong> ${cliente.email || "Não informado"}</p>
            <p><strong>Telefone:</strong> ${cliente.telefone || "Não informado"}</p>

            <p><strong>Origem:</strong> ${p.origem} Nº ${p.numero_origem} ${p.complemento_origem || ""}</p>
            <p><strong>Destino:</strong> ${p.destino} Nº ${p.numero_destino} ${p.complemento_destino || ""}</p>

            <p><strong>Veículo:</strong> ${p.veiculo_id}</p>
            <p><strong>Descrição:</strong> ${p.descricao_carga}</p>
            <p><strong>Distância:</strong> ${p.distancia}</p>
            <p><strong>Valor:</strong> ${p.valor}</p>
            <p><strong>Data:</strong> ${new Date(p.data_hora).toLocaleString("pt-BR")}</p>

            <br>
            <button class="btn" onclick="aprovarPedido(${p.id})">Aprovar</button>
            <button class="btn ghost" onclick="recusarPedido(${p.id})" 
                    style="background:#ffb0b0;color:#690000;border:none">
                Recusar
            </button>
        `;

        div.appendChild(card);
    });
}


async function aprovarPedido(id) {
    if (!confirm("Confirmar aprovação deste pedido?")) return;

    await fetch(`${SUPABASE_URL}/rest/v1/fretes_solicitados?id=eq.${id}`, {
        method: "PATCH",
        headers: {
            "Content-Type": "application/json",
            "apikey": SUPABASE_KEY,
            "Authorization": `Bearer ${SUPABASE_KEY}`
        },
        body: JSON.stringify({ status: "Aprovado" })
    });

    alert("Pedido aprovado!");
    carregarPedidosAguardando();
}

async function recusarPedido(id) {
    if (!confirm("Deseja recusar este pedido?")) return;

    await fetch(`${SUPABASE_URL}/rest/v1/fretes_solicitados?id=eq.${id}`, {
        method: "PATCH",
        headers: {
            "Content-Type": "application/json",
            "apikey": SUPABASE_KEY,
            "Authorization": `Bearer ${SUPABASE_KEY}`
        },
        body: JSON.stringify({ status: "Recusado" })
    });

    alert("Pedido recusado!");
    carregarPedidosAguardando();
}



document.querySelector('[data-view="criar-envio"]').addEventListener("click", () => {
    carregarPedidosAguardando();
});
