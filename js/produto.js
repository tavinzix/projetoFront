/*ITENS */
function trocarImagem(elemento) {
    document.getElementById('imagem-grande').src = elemento.src;
}

function alterarQtd(valor) {
    const input = document.getElementById('quantidade');
    const novaQtd = Math.max(1, parseInt(input.value) + valor);
    input.value = novaQtd;
}

const miniaturas = document.querySelector('.miniaturas');
const btnEsquerdaMiniatura = document.querySelector('.seta-esquerda-miniatura');
const btnDireitaMiniatura = document.querySelector('.seta-direita-miniatura');

if (miniaturas && btnEsquerdaMiniatura && btnDireitaMiniatura) {
    btnEsquerdaMiniatura.addEventListener('click', () => {
        miniaturas.scrollBy({ left: -100, behavior: 'smooth' });
    });

    btnDireitaMiniatura.addEventListener('click', () => {
        miniaturas.scrollBy({ left: 100, behavior: 'smooth' });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const imagemPrincipal = document.querySelector('.imagem-principal');
    const imagem = document.querySelector('.imagem-principal img');

    if (imagemPrincipal && imagem) {
        imagemPrincipal.addEventListener('mousemove', function (e) {
            const rect = imagemPrincipal.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / imagemPrincipal.offsetWidth) * 100;
            const y = ((e.clientY - rect.top) / imagemPrincipal.offsetHeight) * 100;

            imagem.style.setProperty('--x', x + '%');
            imagem.style.setProperty('--y', y + '%');
        });
    }
});