/*Menu hamburguer*/
const hamb = document.getElementById('menu-hamburguer');
const menu = document.getElementById('menu-link');

hamb.addEventListener('click', () => {
  menu.classList.toggle('active');
});

/*Scrol categorias */

const categoria = document.querySelector('.categorias');
const btnEsquerdaCategoria = document.querySelector('.seta-esquerda-categoria');
const btnDireitaCategoria = document.querySelector('.seta-direita-categoria');

btnEsquerdaCategoria.addEventListener('click', () => {
  categoria.scrollBy({ left: -800, behavior: 'smooth' });
});

btnDireitaCategoria.addEventListener('click', () => {
  categoria.scrollBy({ left: 800, behavior: 'smooth' });
});



/*Scrol produtos em destaque*/
const carrossel = document.querySelector('.carrossel-produto');
const btnEsquerdaProduto = document.querySelector('.seta-esquerda-produto');
const btnDireitaProduto = document.querySelector('.seta-direita-produto');

btnEsquerdaProduto.addEventListener('click', () => {
  carrossel.scrollBy({ left: -220, behavior: 'smooth' });
});

btnDireitaProduto.addEventListener('click', () => {
  carrossel.scrollBy({ left: 220, behavior: 'smooth' });
});

