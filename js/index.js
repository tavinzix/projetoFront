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