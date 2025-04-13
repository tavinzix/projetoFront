const hamb = document.getElementById('menu-hamburguer');
const menu = document.getElementById('menu-link');

hamb.addEventListener('click', () => {
  menu.classList.toggle('active');
});
