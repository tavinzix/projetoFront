const hamb = document.getElementById('menu-hamburguer');
const menu = document.getElementById('menu-link');

hamb.addEventListener('click', () => {
  menu.classList.toggle('active');
});

const carrossel = document.querySelector('.carrossel');
const btnEsquerda = document.querySelector('.setaEsquerda');
const btnDireita = document.querySelector('.setaDireita');

btnEsquerda.addEventListener('click', () => {
  carrossel.scrollBy({ left: -220, behavior: 'smooth' });
});

btnDireita.addEventListener('click', () => {
  carrossel.scrollBy({ left: 220, behavior: 'smooth' });
});