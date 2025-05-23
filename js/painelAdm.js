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
    document.getElementById("endereco").innerHTML = dados_loja.endereco;
    document.getElementById("categoria").innerHTML = dados_loja.categoria;
    document.getElementById("descricao").innerHTML = dados_loja.descricao_loja;
    document.getElementById("data").innerHTML = dados_loja.data_solicitacao;

    let status = document.getElementById("status").innerText;

    if (status == '3' || status == '2') {
        const btnRejeitar = document.getElementById("rejeitar");
        const btnAprovar = document.getElementById("aprovar");

        btnRejeitar.disabled = true;
        btnAprovar.disabled = true;

        btnRejeitar.style.backgroundColor = "#ccc";
        btnAprovar.style.backgroundColor = "#ccc";
        btnRejeitar.style.cursor = "not-allowed";
        btnAprovar.style.cursor = "not-allowed";
    }

    document.getElementById("janela-solicitacoes").style.display = "block";
}

function fecharJanelaSolicitacao() {
    document.getElementById("janela-solicitacoes").style.display = "none";
}

function aprovar() {
    const form = document.getElementById("formularioSolicitacao")
    const formData = new FormData(form);
    
    let userId = document.getElementById("id_user").innerHTML;
    let nome = document.getElementById("nome").innerHTML;
    let cnpj = document.getElementById("cnpj").innerHTML;
    let descricao = document.getElementById("descricao").innerHTML;

    formData.append('acao', 'aprovar');
    
    formData.append('id_user', userId);
    formData.append('nome', nome);
    formData.append('cnpj', cnpj);
    formData.append('descricao', descricao);

    fetch('../bd/solicitacao_vendedor.php', {
        method: "POST",
        body: formData
    });

    fecharJanelaSolicitacao();
    window.location.reload();
}