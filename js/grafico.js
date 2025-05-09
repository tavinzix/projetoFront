const ctx = document.getElementById('graficoVendas');
new Chart(ctx, {
    type: 'line', //tipo do gráfico
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'], //label
        datasets: [{
            label: 'Vendas (R$)', //texto do destaque
            data: [2500, 3200, 2800, 4500, 5000, 4100], //valores do grafico
            borderColor: '#FF6B00', 
            backgroundColor: 'rgba(255,107,0,0.1)',
            fill: true, //preenchimento do grafico
            tension: 0.4 //curva do grafico
        }]
    },
    options: {
        responsive: true, //responsivo
        plugins: {
            legend: { display: false } //legenda
        },
        scales: {
            y: {
                beginAtZero: true, //começar em 0
                ticks: {
                    callback: value => `R$ ${value}` // formatar label y
                }
            }
        }
    }
});