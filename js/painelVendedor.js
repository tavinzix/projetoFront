function mostrarImagem(input, imgId, spanId) {
    const img = document.getElementById(imgId);
    const span = document.getElementById(spanId);

    if (input.files.length > 0) {
        img.src = window.URL.createObjectURL(input.files[0]);
        span.style.display = 'none'; // Esconde o span
    } else {
        img.src = '';
        span.style.display = 'inline'; // Mostra o span novamente se não houver imagem
    }
}