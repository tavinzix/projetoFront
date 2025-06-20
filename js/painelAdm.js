/*SOLICITAÇÕES PENDENTES*/
function abrirJanelaSolicitacao(dados_loja) {
    document.getElementById("id_pedido").value = dados_loja.id;
    document.getElementById("id_user").value = dados_loja.user_id;
    document.getElementById("status").value = dados_loja.status;
    document.getElementById("nome").value = dados_loja.nome_loja;
    document.getElementById("cnpj").value = dados_loja.cnpj;
    document.getElementById("email").value = dados_loja.email;
    document.getElementById("telefone").value = dados_loja.telefone;
    document.getElementById("cep").value = dados_loja.cep;
    document.getElementById("estado").value = dados_loja.estado;
    document.getElementById("cidade").value = dados_loja.cidade;
    document.getElementById("bairro").value = dados_loja.bairro;
    document.getElementById("rua").value = dados_loja.rua;
    document.getElementById("numero").value = dados_loja.numero;
    document.getElementById("categoria").value = dados_loja.categoria;
    document.getElementById("descricao").value = dados_loja.descricao_loja;
    document.getElementById("data").value = dados_loja.data_solicitacao;
    document.getElementById("motivo").value = dados_loja.motivo_rejeicao;

    let status = document.getElementById("status").value;

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

    formData.append('acao', 'aprovar');

    fetch('../bd/controller/Solicitacao_controller.php', {
        method: "POST",
        body: formData
    });

    fecharJanelaSolicitacao();
    window.location.reload(true);
}

function rejeitar() {
    const form = document.getElementById("formularioSolicitacao")
    const formData = new FormData(form);

    formData.append('acao', 'rejeitar');

    fetch('../bd/controller/Solicitacao_controller.php', {
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