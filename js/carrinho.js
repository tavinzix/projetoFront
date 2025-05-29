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

document.addEventListener('DOMContentLoaded', function () { // Espera o carregamento completo da pagina
    // Seleciona os campos
    const checkboxes = document.querySelectorAll('.selecionar-item');
    const totalItensp = document.querySelector('.qtd-itens');
    const totalPrecop = document.querySelector('.preco-total');

    // atualiza o resumo da compra
    function atualizarResumo() {
        let totalItens = 0;
        let totalValor = 0;  

        // Percorre todos os checkboxs
        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                const item = checkbox.closest('.item-carrinho'); //procura o item-carrinho do checkbox

                // converte o preco para float
                const precoText = item.querySelector('.preco').textContent.replace('R$', '').replace(',', '.');
                const preco = parseFloat(precoText);

                // Pega a quantidade atual no input
                const quantidadeInput = item.querySelector('input[type="number"]');
                const quantidade = parseInt(quantidadeInput.value);

                // Soma a quantidade e o valor correspondente ao total
                totalItens += quantidade;
                totalValor += preco * quantidade;
            }
        });

        // Atualiza os textos
        totalItensp.textContent = `Itens Selecionados: ${totalItens}`;
        totalPrecop.textContent = `R$ ${totalValor.toFixed(2).replace('.', ',')}`;
    }

    //atualiza o resumo sempre mudar o checkbo
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', atualizarResumo);
    });
});

