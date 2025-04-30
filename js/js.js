/*Menu hamburguer*/
const hamb = document.getElementById('menu-hamburguer');
const menu = document.getElementById('menu-link');

hamb.addEventListener('click', () => {
  menu.classList.toggle('active');
});

/*Scrol categorias */

/*const categoria = document.querySelectorAll('.categorias').forEach(() => {



});*/

const categoria = document.querySelector('.categorias');

const btnEsquerdaCategoria = document.querySelector('.seta-esquerda-categoria');
const btnDireitaCategoria = document.querySelector('.seta-direita-categoria');

btnEsquerdaCategoria.addEventListener('click', () => {
  categoria.scrollBy({ left: -800, behavior: 'smooth' });
});

btnDireitaCategoria.addEventListener('click', () => {
  categoria.scrollBy({ left: 800, behavior: 'smooth' });
});



/*Scrol ofertas recentes*/
const carrossel = document.querySelector('.carrossel-oferta');
const btnEsquerdaOferta = document.querySelector('.seta-esquerda-oferta');
const btnDireitaOferta = document.querySelector('.seta-direita-oferta');

btnEsquerdaOferta.addEventListener('click', () => {
  carrossel.scrollBy({ left: -220, behavior: 'smooth' });
});

btnDireitaOferta.addEventListener('click', () => {
  carrossel.scrollBy({ left: 220, behavior: 'smooth' });
});

