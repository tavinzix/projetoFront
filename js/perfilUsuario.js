/*PERFIL DO USUARIO*/
function filtrarPedidos(filtro) {
    const pedidos = document.querySelectorAll('.lista-pedidos .pedido');
    const abas = document.querySelectorAll('.filtros-pedidos .aba');
    if (pedidos && abas) {
        abas.forEach(aba => aba.classList.remove('ativa'));

        const abaSelecionada = document.querySelector(`.filtros-pedidos .aba[onclick="filtrarPedidos('${filtro}')"]`);
        if (abaSelecionada) {
            abaSelecionada.classList.add('ativa');
        }

        pedidos.forEach(pedido => {
            const statusPedido = pedido.querySelector('.status');
            const status = statusPedido?.classList[1];

            if (filtro === 'todos' || status === filtro) {
                pedido.style.display = 'flex';
            } else {
                pedido.style.display = 'none';
            }
        });
    }
}

function abrirJanelaEndereco(endereco) {
    document.getElementById("id_endereco").value = endereco.id;
    document.getElementById("id_usuario").value = endereco.user_id;
    document.getElementById("tipo").value = endereco.tipo;
    document.getElementById("cep").value = endereco.cep;
    document.getElementById("estado").value = endereco.estado;
    document.getElementById("cidade").value = endereco.cidade;
    document.getElementById("bairro").value = endereco.bairro;
    document.getElementById("rua").value = endereco.rua;
    document.getElementById("numero").value = endereco.numero;
    document.getElementById("complemento").value = endereco.complemento;

    document.getElementById("janela-endereco").style.display = "block";
}

function fecharJanelaEndereco() {
    document.getElementById("janela-endereco").style.display = "none";
}

function editar() {
    const form = document.getElementById("formularioEdicaoEndereco")
    const formData = new FormData(form);

    formData.append('acao', 'editar');

    fetch('../bd/controller/EnderecoUsuario_controller.php', {
        method: "POST",
        body: formData
    });

    window.location.reload(true);
}

function abrirJanelaPagamento(pagamento) {
    document.getElementById("id_forma").value = pagamento.id;
    document.getElementById("id_usuario").value = pagamento.user_id;
    document.getElementById("nome_titular").value = pagamento.nome_titular;
    document.getElementById("nome_cartao").value = pagamento.nome_cartao;
    document.getElementById("numero_cartao").value = pagamento.numero_cartao;
    document.getElementById("validade").value = pagamento.validade;
    document.getElementById("cvv").value = pagamento.cvv;

    document.getElementById("janela-pagamento").style.display = "block";
}

function fecharJanelaPagamento() {
    document.getElementById("janela-pagamento").style.display = "none";
}

function editarPagamento() {
    const formPagamento = document.getElementById("formularioEdicaoPagamento")
    const formDataPagamento = new FormData(formPagamento);

    formDataPagamento.append('acao', 'editar');

    fetch('../bd/controller/FormaPagamentoUsuario_controller.php', {
        method: "POST",
        body: formDataPagamento
    });

    window.location.reload(true);
}