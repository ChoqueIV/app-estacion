// Configuración inicial del gráfico
const ctx = document.getElementById('chart').getContext('2d');
let chart;

// Función para crear gráficos dinámicos
function renderChart(data, label, title) {
  if (chart) chart.destroy(); // Destruir gráfico previo

  chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: label,  
      datasets: [{
        label: title,
        data: data,
        borderColor: '#4a90e2',
        borderWidth: 2,
        backgroundColor: 'rgba(74, 144, 226, 0.2)',
        pointBackgroundColor: '#4a90e2',
        tension: 0.2
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: true }
      },
      scales: {
        x: { title: { display: true, text: 'Tiempo' } },
        y: { title: { display: true, text: title } }
      }
    }
  });
}

// Datos de ejemplo para cada tipo
const dataSamples = {
  temperature: { data: [22, 25, 21, 23, 25], label: ['10:00', '11:00', '12:00', '13:00', '14:00'], title: 'Temperatura (°C)' },
  humidity: { data: [60, 62, 65, 63, 64], label: ['10:00', '11:00', '12:00', '13:00', '14:00'], title: 'Humedad (%)' },
  pressure: { data: [1010, 1012, 1015, 1013, 1014], label: ['10:00', '11:00', '12:00', '13:00', '14:00'], title: 'Presión Atmosférica (hPa)' },
  wind: { data: [5, 10, 15, 10, 8], label: ['10:00', '11:00', '12:00', '13:00', '14:00'], title: 'Velocidad del Viento (km/h)' },
  fireIndex: { data: [1.2, 1.5, 1.8, 2.0, 1.7], label: ['10:00', '11:00', '12:00', '13:00', '14:00'], title: 'Índice de Fuego' }
};

// Eventos para los botones
document.getElementById('btn-temperature').addEventListener('click', () => {
  const { data, label, title } = dataSamples.temperature;
  renderChart(data, label, title);
});

document.getElementById('btn-humidity').addEventListener('click', () => {
  const { data, label, title } = dataSamples.humidity;
  renderChart(data, label, title);
});

document.getElementById('btn-pressure').addEventListener('click', () => {
  const { data, label, title } = dataSamples.pressure;
  renderChart(data, label, title);
});

document.getElementById('btn-wind').addEventListener('click', () => {
  const { data, label, title } = dataSamples.wind;
  renderChart(data, label, title);
});

document.getElementById('btn-fire-index').addEventListener('click', () => {
  const { data, label, title } = dataSamples.fireIndex;
  renderChart(data, label, title);
});

// Inicializa con un gráfico predeterminado
renderChart(dataSamples.temperature.data, dataSamples.temperature.label, dataSamples.temperature.title);
