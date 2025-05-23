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