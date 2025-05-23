/*Menu hamburguer*/
const hamb = document.getElementById('menu-hamburguer');
const menu = document.getElementById('menu-link');

if (hamb && menu) {
    hamb.addEventListener('click', () => {
        menu.classList.toggle('active');
    });
}

/*CARRINHO*/
function alterarQuantidadeCarrinho(botao, valor) {
    const container = botao.closest('.quantidade-container');
    const input = container.querySelector('input[type="number"]');
    const novaQtd = Math.max(1, parseInt(input.value) + valor);
    input.value = novaQtd;
}
