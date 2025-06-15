/*SOLICITAÇÕES PENDENTES*/
function abrirJanelaSolicitacao(dados_loja) {
    document.getElementById("id_pedido").innerHTML = dados_loja.id;
    document.getElementById("id_user").innerHTML = dados_loja.user_id;
    document.getElementById("status").innerHTML = dados_loja.status;
    document.getElementById("nome").innerHTML = dados_loja.nome_loja;
    document.getElementById("cnpj").innerHTML = dados_loja.cnpj;
    document.getElementById("email").innerHTML = dados_loja.email;
    document.getElementById("telefone").innerHTML = dados_loja.telefone;
    document.getElementById("cep").innerHTML = dados_loja.cep;
    document.getElementById("estado").innerHTML = dados_loja.estado;
    document.getElementById("cidade").innerHTML = dados_loja.cidade;
    document.getElementById("bairro").innerHTML = dados_loja.bairro;
    document.getElementById("rua").innerHTML = dados_loja.rua;
    document.getElementById("numero").innerHTML = dados_loja.numero;
    document.getElementById("categoria").innerHTML = dados_loja.categoria;
    document.getElementById("descricao").innerHTML = dados_loja.descricao_loja;
    document.getElementById("data").innerHTML = dados_loja.data_solicitacao;
    document.getElementById("motivo").innerHTML = dados_loja.motivo_rejeicao;

    let status = document.getElementById("status").innerText;

    if (status == '3' || status == '2') {
        const btnRejeitar = document.getElementById("rejeitarBtn");
        const btnAprovar = document.getElementById("aprovarBtn");
        const motivo = document.getElementById("motivo");


        btnRejeitar.disabled = true;
        btnAprovar.disabled = true;
        motivo.disabled = true;

        btnRejeitar.style.backgroundColor = "#ccc";
        btnAprovar.style.backgroundColor = "#ccc";
        btnRejeitar.style.cursor = "not-allowed";
        btnAprovar.style.cursor = "not-allowed";
    }

    document.getElementById("janela-solicitacoes").style.display = "block";
}

function fecharJanelaSolicitacao() {
    const btnRejeitar = document.getElementById("rejeitarBtn");
    const btnAprovar = document.getElementById("aprovarBtn");

    btnRejeitar.style.backgroundColor = "";
    btnAprovar.style.backgroundColor = "";
    btnRejeitar.style.cursor = "";
    btnAprovar.style.cursor = "";

    btnRejeitar.disabled = false;
    btnAprovar.disabled = false;

    document.getElementById("janela-solicitacoes").style.display = "none";
}

function aprovar() {
    const form = document.getElementById("formularioSolicitacao")
    const formData = new FormData(form);
    let userId = document.getElementById("id_user").innerHTML;
    let nome = document.getElementById("nome").innerHTML;
    let cnpj = document.getElementById("cnpj").innerHTML;
    let descricao = document.getElementById("descricao").innerHTML;
    let email = document.getElementById("email").innerHTML;
    let telefone = document.getElementById("telefone").innerHTML;
    let cep = document.getElementById("cep").innerHTML;
    let estado = document.getElementById("estado").innerHTML;
    let cidade = document.getElementById("cidade").innerHTML;
    let bairro = document.getElementById("bairro").innerHTML;
    let rua = document.getElementById("rua").innerHTML;
    let numero = document.getElementById("numero").innerHTML;
    let categoria = document.getElementById("categoria").innerHTML;

    formData.append('acao', 'aprovar');
    formData.append('id_user', userId);
    formData.append('nome', nome);
    formData.append('cnpj', cnpj);
    formData.append('descricao', descricao);
    formData.append('email', email);
    formData.append('telefone', telefone);
    formData.append('cep', cep);
    formData.append('estado', estado);
    formData.append('cidade', cidade);
    formData.append('bairro', bairro);
    formData.append('rua', rua);
    formData.append('numero', numero);
    formData.append('categoria', categoria);

    fetch('../bd/solicitacao_vendedor.php', {
        method: "POST",
        body: formData
    });

    fecharJanelaSolicitacao();
    window.location.reload(true);
}

function rejeitar() {
    const form = document.getElementById("formularioSolicitacao")
    const formData = new FormData(form);

    let pedidoId = document.getElementById("id_pedido").innerHTML;

    formData.append('acao', 'rejeitar');
    formData.append('id_pedido', pedidoId);

    fetch('../bd/solicitacao_vendedor.php', {
        method: "POST",
        body: formData
    });

    fecharJanelaSolicitacao();
    window.location.reload(true);
}

function abrirJanelaCategoria(categoria) {
    document.getElementById("id_categoria").value = categoria.id;
    document.getElementById("nome").value = categoria.nome;
    document.getElementById("descricao").value = categoria.descricao;
    document.getElementById("url").value = categoria.url;

    const imagemAtual = document.getElementById("imagemAtual");
    imagemAtual.src = "../img/categoria/" + categoria.imagem;

    const inativarBtn = document.getElementById("inativarBtn");
    const ativarBtn = document.getElementById("ativarBtn");

    if (categoria.status == '1') {
        inativarBtn.style.display = "inline-block";
        ativarBtn.style.display = "none";
    } else {
        inativarBtn.style.display = "none";
        ativarBtn.style.display = "inline-block";
    }

    document.getElementById("janela-categoria").style.display = "block";
}

function fecharJanelaCategoria() {
    document.getElementById("janela-categoria").style.display = "none";
}

function editar() {
    const form = document.getElementById("formularioCategoria")
    const formData = new FormData(form);

    formData.append('acao', 'editar');

    fetch('../bd/controller/Categoria_controller.php', {
        method: "POST",
        body: formData
    });

    window.location.reload(true);
}

function inativar() {
    const form = document.getElementById("formularioCategoria")
    const formData = new FormData(form);

    formData.append('acao', 'inativar');

    fetch('../bd/controller/Categoria_controller.php', {
        method: "POST",
        body: formData
    });

    window.location.reload(true);
}

function ativar() {
    const form = document.getElementById("formularioCategoria")
    const formData = new FormData(form);

    formData.append('acao', 'ativar');

    fetch('../bd/controller/Categoria_controller.php', {
        method: "POST",
        body: formData
    });

    window.location.reload(true);
}