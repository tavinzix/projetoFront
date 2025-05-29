function removerItem(itemId) {
    if (!confirm("Deseja remover este item do carrinho?")) {
        return;
    }

    const formData = new URLSearchParams();
    formData.append('acao', 'remover');
    formData.append('item_id', itemId);

    fetch('../bd/manipula_carrinho.php', {
        method: 'POST',
        body: formData
    });

    window.location.reload(true);
}

function atualizarQuantidade(itemId) {
    const inputQuantidade = document.querySelector(`#quantidade-item-${itemId}`);

    const novaQuantidade = parseInt(inputQuantidade.value);

    if (isNaN(novaQuantidade) || novaQuantidade < 1) {
        alert("Quantidade inválida!");
        return;
    }

    const formData = new URLSearchParams();
    formData.append('acao', 'atualizar');
    formData.append('item_id', itemId);
    formData.append('quantidade', novaQuantidade);

    fetch('../bd/manipula_carrinho.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            if (data === 'atualizado') {
                alert("Quantidade atualizada com sucesso!");
                window.location.reload(true);
            } else {
                alert("Erro ao atualizar a quantidade.");
            }
        });
}
