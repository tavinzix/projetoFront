/*Menu hamburguer*/
const hamb = document.getElementById('menu-hamburguer');
const menu = document.getElementById('menu-link');

if (hamb && menu) {
    hamb.addEventListener('click', () => {
        menu.classList.toggle('active');
    });
}

/*CARRINHO*/
function alterarQuantidadeCarrinho(botao, valor) {
    const container = botao.closest('.quantidade-container');
    const input = container.querySelector('input[type="number"]');
    const novaQtd = Math.max(1, parseInt(input.value) + valor);
    input.value = novaQtd;
}


/*API Speech Recognition*/
function buscaAudio() {
    if ('webkitSpeechRecognition' in window) {
        // Inicializa o reconhecimento de fala
        const recognition = new webkitSpeechRecognition();
        recognition.lang = 'pt-BR'; // Define o idioma para português
        recognition.continuous = false; // Reconhecimento só acontece quando o usuário para de falar
        recognition.interimResults = false; // Só mostra quando para de falar

        const searchBox = document.getElementById('caixa-pesquisa'); // Caixa de pesquisa
        const form = document.querySelector('.busca-container'); // Formulário
        let timer;

        // Submete o formulário
        function submitFormulario() {
            if (searchBox.value.trim() !== "") {
                form.submit();
            }
        }

        recognition.onresult = function (event) {
            const transcript = event.results[0][0].transcript; //obtem o que foi dito
            searchBox.value = transcript; // Preenche o campo de pesquisa com o texto reconhecido

            clearTimeout(timer);
            timer = setTimeout(() => {
                submitFormulario(); // Envia o formulário
            }, 2000);
        };

        recognition.onerror = function (event) {
            console.error("Erro no reconhecimento de fala: ", event.error);
        };

        // Quando o microfone é clicado, inicia o reconhecimento de fala
        document.getElementById('microfone').addEventListener('click', function () {
            recognition.start();
            searchBox.value = ''; // Limpa o campo de pesquisa quando iniciar
            clearTimeout(timer); // Limpa o temporizador
        });
    } else {
        alert("A API de Reconhecimento de Fala não é suportada neste navegador.");
    }
}

//API GEOLOCATION
function getMyLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    } else {
        alert("Geolocalização não é suportada pelo seu navegador.");
    }
}

function successCallback(position) {
    const form = document.getElementById("cadastroEnderecoUsuario");
    if (!form) {
        console.error("Formulário com id 'cadastroEnderecoUsuario' não encontrado.");
        return;
    }

    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;

    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
        .then(response => response.json())
        .then(data => {
            if (data.address) {
                form.querySelector("#cep").value = data.address.postcode || "";
                form.querySelector("#estado").value = data.address.state || "";
                form.querySelector("#cidade").value = data.address.city || data.address.town || "";
                form.querySelector("#rua").value = data.address.road || "";
                form.querySelector("#numero").value = data.address.house_number || "";
                form.querySelector("#bairro").value = data.address.suburb || "";
            } else {
                alert("Endereço não encontrado.");
            }
        })
        .catch(error => console.error("Erro ao obter endereço:", error));
}


function errorCallback(error) {
    if (error.code === error.PERMISSION_DENIED) {
        alert("Você negou o acesso à localização. Permita o uso da localização para preencher o endereço automaticamente.");
    } else {
        alert("Erro ao obter localização: " + error.message);
    }
}
