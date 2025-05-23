/*LOJA*/
function abrirJanela() {
    document.getElementById("janela-avaliacoes").style.display = "block";
}

function fecharJanela() {
    document.getElementById("janela-avaliacoes").style.display = "none";
}

function mostrarCategoria(categoria) {
    const abas = document.querySelectorAll('.aba');
    const produtos = document.querySelectorAll('.lista-produtos');

    abas.forEach(btn => btn.classList.remove('ativa'));
    produtos.forEach(div => div.classList.add('oculto'));

    document.getElementById(categoria).classList.remove('oculto');
    document.querySelector(`.aba[onclick="mostrarCategoria('${categoria}')"]`).classList.add('ativa');
}