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
    document.getElementById("id_endereco").innerText = endereco.id;
    document.getElementById("id_usuario").innerText = endereco.user_id;
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
    let enderecoId = document.getElementById("id_endereco").innerHTML;
    let userId = document.getElementById("id_usuario").innerHTML;
    let tipo = document.getElementById("tipo").value;
    let cep = document.getElementById("cep").value;
    let estado = document.getElementById("estado").value;
    let cidade = document.getElementById("cidade").value;
    let bairro = document.getElementById("bairro").value;
    let rua = document.getElementById("rua").value;
    let numero = document.getElementById("numero").value;
    let complemento = document.getElementById("complemento").value;

    formData.append('acao', 'editar');
    formData.append('id', enderecoId);
    formData.append('userId', userId);
    formData.append('tipo', tipo);
    formData.append('cep', cep);
    formData.append('estado', estado);
    formData.append('cidade', cidade);
    formData.append('bairro', bairro);
    formData.append('rua', rua);
    formData.append('numero', numero);
    formData.append('complemento', complemento);

    fetch('../bd/editarEnderecoUsuario.php', {
        method: "POST",
        body: formData
    });

    window.location.reload(true);
}