/*
LOGIN
Mostrar/esconder senha*/
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
    window.location.href = "../index.php";
}