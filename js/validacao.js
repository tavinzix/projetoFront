// validacao.js

function validaNomeCompleto() {
    const input = document.getElementById('nome');

    const nome = input.value.trim();
    if (nome.length < 3) {
        alert('Por favor, digite um nome completo com pelo menos 3 caracteres.');
        return false;
    }
    return true;
}

function verificaSenhasIguais() {
    const senha = document.getElementById('senha');
    const confirma = document.getElementById('cSenha');

    if (senha.value !== confirma.value) {
        alert('As senhas não coincidem');
        return false;
    }
    return true;
}

function validaCpf() {
    const cpfInput = document.getElementById('cpf');

    const cpf = cpfInput.value.replace(/\D/g, '');
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        alert('CPF inválido');
        return false;
    }

    return true;
}

function validaTelefone() {
    const telInput = document.getElementById('telefone');
    const telefone = telInput.value.replace(/\D/g, '');

    if (telefone.length < 10 || telefone.length > 11) {
        alert('Telefone inválido. Digite DDD + número com 11 dígitos.');
        return false;
    }

    return true;
}

function validaCnpj() {
    const input = document.getElementById('cnpj');

    const cnpj = input.value.replace(/\D/g, '');
    if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) {
        alert('CNPJ inválido');
        return false;
    }
    return true;
}

function validaNumeroCartao() {
    const numeroCartao = document.getElementById('numero_cartao');
    const cartao = numeroCartao.value.replace(/\D/g, '');

    if (cartao.length < 13 || cartao.length > 19) {
        alert('Cartão inválido.');
        return false;
    }

    return true;
}

function validaDataCartao() {
    const dataCartao = document.getElementById('validade');

    if (!/^\d{2}\/\d{2}$/.test(dataCartao.value.trim())) {
        alert('Data de validade deve estar no formato MM/AA');
        return false;
    }
    return true;
}

function validaCvv() {
    const cvv = document.getElementById('cvv');

    if (!/^\d{3}$/.test(cvv.value.trim())) {
        alert('CVV deve conter exatamente 3 dígitos');
        return false;
    }
    return true;
}
  
  


const formCriarContaUsuario = document.getElementById("formCriarContaUsuario");
if (formCriarContaUsuario) {
    formCriarContaUsuario.addEventListener("submit", function (e) {
        if (!verificaSenhasIguais() || !validaCpf() || !validaTelefone() || !validaNomeCompleto()) {
            e.preventDefault();
        }
    });
}

const formSolicitaCadastroVendedor = document.getElementById("formSolicitaCadastroVendedor");
if (formSolicitaCadastroVendedor) {
    formSolicitaCadastroVendedor.addEventListener("submit", function (e) {
        if (!validaCnpj() || !validaTelefone() || !validaNomeCompleto()) {
            e.preventDefault();
        }
    });
}

const formEditarPerfilUsuario = document.getElementById("formEditarPerfilUsuario");
if (formEditarPerfilUsuario) {
    formEditarPerfilUsuario.addEventListener("submit", function (e) {
        if (!verificaSenhasIguais() || !validaTelefone() || !validaNomeCompleto()) {
            e.preventDefault();
        }
    });
}

const formEdicaoPagamento = document.getElementById("formularioEdicaoPagamento");
if (formEdicaoPagamento) {
    formEdicaoPagamento.addEventListener("submit", function (e) {
        if (!validaNomeCompleto() || !validaNumeroCartao() || !validaDataCartao() || !validaCvv() ) {
            e.preventDefault();
        }
    });
}