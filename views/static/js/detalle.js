const ctx = document.getElementById('chart').getContext('2d');
let chart;

function renderChart(data, labels, title) {
  if (chart) {
    chart.destroy();
  }

  chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
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
        x: { title: { display: true, text: 'Fecha' } },
        y: { title: { display: true, text: title } }
      }
    }
  });
}


function getChipIdFromUrl() {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get('chipid');
}

const chipid = getChipIdFromUrl();

async function loadWeatherData(chipid) {
  const response = await fetch(`https://mattprofe.com.ar/proyectos/app-estacion/datos.php?chipid=${chipid}&cant=6`);
  const data = await response.json();
  console.log(data); 

  return data;
}

async function updateChart(type) {
  const data = await loadWeatherData(chipid);

  console.log(data);

  let chartData = [];
  let chartLabels = [];
  let chartTitle = '';

  switch(type) {
    case 'temperature':
      chartData = data.map(entry => {
        const temperature = parseFloat(entry.temperatura);
        return isNaN(temperature) ? null : temperature;
      }).filter(value => value !== null);
      chartLabels = data.map(entry => entry.fecha);
      chartTitle = 'Temperatura (°C)';
      break;
    case 'humidity':
      chartData = data.map(entry => {
        const humidity = parseFloat(entry.humedad);
        return isNaN(humidity) ? null : humidity;
      }).filter(value => value !== null);
      chartLabels = data.map(entry => entry.fecha);
      chartTitle = 'Humedad (%)';
      break;
    case 'pressure':
      chartData = data.map(entry => {
        const pressure = parseFloat(entry.presion);
        return isNaN(pressure) ? null : pressure;
      }).filter(value => value !== null);
      chartLabels = data.map(entry => entry.fecha);
      chartTitle = 'Presión Atmosférica (hPa)';
      break;
    case 'wind':
      chartData = data.map(entry => {
        const wind = parseFloat(entry.viento);
        return isNaN(wind) ? null : wind;
      }).filter(value => value !== null);
      chartLabels = data.map(entry => entry.fecha);
      chartTitle = 'Velocidad del Viento (km/h)';
      break;
    case 'fire-index':
      chartData = data.map(entry => {
        const fireIndex = parseFloat(entry.ffmc);
        return isNaN(fireIndex) ? null : fireIndex;
      }).filter(value => value !== null);
      chartLabels = data.map(entry => entry.fecha);
      chartTitle = 'Índice de Fuego (FFMC)';
      break;
    default:
      chartData = [];
      chartLabels = [];
      chartTitle = 'Datos no disponibles';
  }

  renderChart(chartData, chartLabels, chartTitle);
}

updateChart('temperature');

document.getElementById('btn-temperature').addEventListener('click', () => updateChart('temperature'));
document.getElementById('btn-humidity').addEventListener('click', () => updateChart('humidity'));
document.getElementById('btn-pressure').addEventListener('click', () => updateChart('pressure'));
document.getElementById('btn-wind').addEventListener('click', () => updateChart('wind'));
document.getElementById('btn-fire-index').addEventListener('click', () => updateChart('fire-index'));
