function validarCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

    let soma = 0;
    for (let i = 0; i < 9; i++) soma += parseInt(cpf[i]) * (10 - i);
    let digito1 = (soma * 10) % 11;
    if (digito1 === 10 || digito1 === 11) digito1 = 0;
    if (digito1 !== parseInt(cpf[9])) return false;

    soma = 0;
    for (let i = 0; i < 10; i++) soma += parseInt(cpf[i]) * (11 - i);
    let digito2 = (soma * 10) % 11;
    if (digito2 === 10 || digito2 === 11) digito2 = 0;
    return digito2 === parseInt(cpf[10]);
}

function validarCNPJ(cnpj) {
    cnpj = cnpj.replace(/\D/g, '');
    if (cnpj.length !== 14 || /^(\d)\1+$/.test(cnpj)) return false;

    let t = cnpj.length - 2;
    let d = cnpj.substring(t);
    let calc = s => {
        let n = cnpj.substring(0, s);
        let pos = s - 7;
        let soma = 0;
        for (let i = s; i >= 1; i--) {
            soma += n[s - i] * pos--;
            if (pos < 2) pos = 9;
        }
        let r = soma % 11 < 2 ? 0 : 11 - soma % 11;
        return r;
    };

    return calc(t) == d[0] && calc(t + 1) == d[1];
}

function validarTelefone(telefone) {
    const pattern = /^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/;
    return pattern.test(telefone.trim());
}

function validarCEP(cep) {
    const pattern = /^\d{5}-?\d{3}$/;
    return pattern.test(cep.trim());
}


function MascaraDataDiaMes(Campo, teclapres) {
    if (Campo.maxLength > 2) {
        var tecla = teclapres.keyCode;
        var vr = new String(Campo.value);

        vr = vr.replace("/", "");
        vr = vr.replace("/", "");
        tam = vr.length + 1;

        if (tecla != 4) {
            if (tam > 0 && tam < 2)
                Campo.value = vr.substr(0, 2);
            if (tam > 2 && tam < 4)
                Campo.value = vr.substr(0, 2) + '/' + vr.substr(2, 2);
        }
    }
}

function MascaraDataDiaMesAno(Campo, teclapres) {
    var tecla = teclapres.keyCode;
    var vr = new String(Campo.value);

    vr = vr.replace("/", "");
    vr = vr.replace("/", "");
    vr = vr.replace("/", "");
    tam = vr.length + 1;

    if (tecla != 8) {
        if (tam > 0 && tam < 2)
            Campo.value = vr.substr(0, 2);
        if (tam > 2 && tam < 4)
            Campo.value = vr.substr(0, 2) + '/' + vr.substr(2, 2);
        if (tam > 4 && tam < 7)
            Campo.value = vr.substr(0, 2) + '/' + vr.substr(2, 2) + '/' + vr.substr(4, 7);
    }
}

function MascaraCep(Campo, teclapres) {
    var tecla = teclapres.keyCode;
    var vr = new String(Campo.value);

    vr = vr.replace("-", "");
    tam = vr.length + 1;

    if (tecla != 8) {
        if (tam > 5)
            Campo.value = vr.substr(0, 5) + '-' + vr.substr(5, 3);
    }
}

function MascaraTel(Campo, teclapres) {
    var tecla = teclapres.keyCode;
    var vr = new String(Campo.value);

    vr = vr.replace("-", "");
    tam = vr.length + 1;

    if (tecla != 8) {
        if (tam > 4)
            Campo.value = vr.substr(0, 4) + '-' + vr.substr(4, 4);
    }
}

function FormataCnpj(campo, teclapres) {
    var tecla = teclapres.keyCode;
    var vr = new String(campo.value);

    vr = vr.replace(".", "");
    vr = vr.replace("/", "");
    vr = vr.replace("-", "");
    tam = vr.length + 1;

    if (tecla != 14 && tecla != 8) {
        if (tam == 3)
            campo.value = vr.substr(0, 2) + '.';
        if (tam == 6)
            campo.value = vr.substr(0, 2) + '.' + vr.substr(2, 5) + '.';
        if (tam == 10)
            campo.value = vr.substr(0, 2) + '.' + vr.substr(2, 3) + '.' + vr.substr(6, 3) + '/';
        if (tam == 15)
            campo.value = vr.substr(0, 2) + '.' + vr.substr(2, 3) + '.' + vr.substr(6, 3) + '/' + vr.substr(9, 4) + '-' + vr.substr(13, 2);
    }
}

function validarCNPJ(cnpj) {
    cnpj = cnpj.replace(/[^\d]+/g, '');

    if (cnpj == '') return false;

    if (cnpj.length != 14)
        return false;

    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" ||
        cnpj == "11111111111111" ||
        cnpj == "22222222222222" ||
        cnpj == "33333333333333" ||
        cnpj == "44444444444444" ||
        cnpj == "55555555555555" ||
        cnpj == "66666666666666" ||
        cnpj == "77777777777777" ||
        cnpj == "88888888888888" ||
        cnpj == "99999999999999")
        return false;

    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0, tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
        return false;

    return true;
}