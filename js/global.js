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
function buscaAudio(){
    if ('webkitSpeechRecognition' in window) {
        // Inicializa o reconhecimento de fala
        const recognition = new webkitSpeechRecognition();
        recognition.lang = 'pt-BR';  // Define o idioma para português
        recognition.continuous = false;  // Reconhecimento só acontece quando o usuário para de falar
        recognition.interimResults = false;  // Não exibe resultados intermediários

        const searchBox = document.getElementById('caixa-pesquisa');  // Caixa de pesquisa
        let timer;  // Variável do temporizador

        // Função para enviar a pesquisa (simulando pressionamento de "Enter")
        function simulateEnter() {
            // Simula o evento "Enter" no campo de pesquisa
            const event = new KeyboardEvent('keypress', {
                key: 'Enter',
                keyCode: 13,
                which: 13
            });
            searchBox.dispatchEvent(event);
        }

        // Evento de resultado do reconhecimento
        recognition.onresult = function (event) {
            // Obtém o texto final reconhecido
            const transcript = event.results[0][0].transcript;
            searchBox.value = transcript;  // Preenche o campo de pesquisa com o texto reconhecido

            // Limpa o temporizador e reinicia
            clearTimeout(timer);
            timer = setTimeout(simulateEnter, 2000);  // Inicia o temporizador de 2 segundos
        };

        // Evento de erro
        recognition.onerror = function (event) {
            console.error("Erro no reconhecimento de fala: ", event.error);
        };

        // Quando o botão de microfone é clicado, inicia o reconhecimento de fala
        document.getElementById('microfone').addEventListener('click', function () {
            recognition.start();
            searchBox.value = '';  // Limpa o campo de pesquisa quando iniciar
            clearTimeout(timer);  // Limpa qualquer temporizador anterior
        });

        // Evento para detectar quando o usuário pressionar Enter no campo de pesquisa
        searchBox.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                alert("Você pesquisou por: " + searchBox.value);
                // Aqui você pode fazer algo como enviar a pesquisa para o servidor
            }
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
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;

    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
        .then(response => response.json())
        .then(data => {
            if (data.address) {
                document.getElementById("cep").value = data.address.postcode || "";
                document.getElementById("estado").value = data.address.state || "";
                document.getElementById("cidade").value = data.address.city || data.address.town || "";
                document.getElementById("rua").value = data.address.road || "";
                document.getElementById("numero").value = data.address.house_number || "";
                document.getElementById("bairro").value = data.address.suburb || "";
            } else {
                alert("Endereço não encontrado.");
            }
        })
        .catch(error => console.error("Erro ao obter endereço:", error));
}

function errorCallback(error) {
    console.error("Erro na geolocalização:", error.message);
}