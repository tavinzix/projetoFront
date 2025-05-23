/*Menu hamburguer*/
const hamb = document.getElementById('menu-hamburguer');
const menu = document.getElementById('menu-link');

if (hamb && menu) {
    hamb.addEventListener('click', () => {
        menu.classList.toggle('active');
    });
}


/*Scrol categorias */
const categoria = document.querySelector('.categorias');
const btnEsquerdaCategoria = document.querySelector('.seta-esquerda-categoria');
const btnDireitaCategoria = document.querySelector('.seta-direita-categoria');

if (categoria && btnEsquerdaCategoria && btnDireitaCategoria) {
    btnEsquerdaCategoria.addEventListener('click', () => {
        categoria.scrollBy({ left: -200, behavior: 'smooth' });
    });

    btnDireitaCategoria.addEventListener('click', () => {
        categoria.scrollBy({ left: 200, behavior: 'smooth' });
    });
}


/*Scrol ofertas recentes*/
const carrossel = document.querySelector('.carrossel-oferta');
const btnEsquerdaOferta = document.querySelector('.seta-esquerda-oferta');
const btnDireitaOferta = document.querySelector('.seta-direita-oferta');

if (carrossel && btnEsquerdaOferta && btnDireitaOferta) {
    btnEsquerdaOferta.addEventListener('click', () => {
        carrossel.scrollBy({ left: -220, behavior: 'smooth' });
    });

    btnDireitaOferta.addEventListener('click', () => {
        carrossel.scrollBy({ left: 220, behavior: 'smooth' });
    });
}


/*Mostrar/esconder senha*/
const mostrarSenha = document.getElementById('mostrar-senha');
const senhaInput = document.getElementById('senha');
if (mostrarSenha) {
    mostrarSenha.addEventListener('click', () => {
        if (senhaInput.type === 'password') {
            senhaInput.type = 'text';
            mostrarSenha.innerHTML = '<img src="../img/site/olhoFechado.png"></img>';
        } else {
            senhaInput.type = 'password';
            mostrarSenha.innerHTML = '<img src="../img/site/olhoAberto.png"></img>';
        }
    });
}

const mostrarCSenha = document.getElementById('mostrar-cSenha');
const cSenhaInput = document.getElementById('cSenha');
if (mostrarCSenha) {
    mostrarCSenha.addEventListener('click', () => {
        if (cSenhaInput.type === 'password') {
            cSenhaInput.type = 'text';
            mostrarCSenha.innerHTML = '<img src="../img/site/olhoFechado.png"></img>';
        } else {
            cSenhaInput.type = 'password';
            mostrarCSenha.innerHTML = '<img src="../img/site/olhoAberto.png"></img>';
        }
    });
}


/*Recuperar senha*/
function enviarCodigo() {
    const cpf = document.getElementById("cpf").value;
    const dtNasc = document.getElementById("dtNasc").value;

    document.getElementById("form1").setAttribute('style', 'display: none');
    document.getElementById("form2").setAttribute('style', 'display: block');
}

function validarCodigo() {
    const codigo = document.getElementById("codigo").value;

    document.getElementById("form2").setAttribute('style', 'display: none');
    document.getElementById("form3").setAttribute('style', 'display: block');
}

function redefinirSenha() {
    alert("Senha alterada");
    window.location.href = "index.html";
}


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


/*CARRINHO*/
function alterarQuantidadeCarrinho(botao, valor) {
    const container = botao.closest('.quantidade-container');
    const input = container.querySelector('input[type="number"]');
    const novaQtd = Math.max(1, parseInt(input.value) + valor);
    input.value = novaQtd;
}

/*SOLICITAÇÕES PENDENTES*/
function abrirJanelaSolicitacao(dados_loja) {
    document.getElementById("id_pedido").innerHTML = dados_loja.id;
    document.getElementById("id_user").innerHTML = dados_loja.user_id;
    document.getElementById("nome").innerHTML = dados_loja.nome_loja;
    document.getElementById("cnpj").innerHTML = dados_loja.cnpj;
    document.getElementById("email").innerHTML = dados_loja.email;
    document.getElementById("telefone").innerHTML = dados_loja.telefone;
    document.getElementById("cep").innerHTML = dados_loja.cep;
    document.getElementById("endereco").innerHTML = dados_loja.endereco;
    document.getElementById("categoria").innerHTML = dados_loja.categoria;
    document.getElementById("descricao").innerHTML = dados_loja.descricao_loja;
    document.getElementById("data").innerHTML = dados_loja.data_solicitacao;

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