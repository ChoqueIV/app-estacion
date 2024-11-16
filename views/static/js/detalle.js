// Configuración inicial del gráfico
const ctx = document.getElementById('chart').getContext('2d');
let chart;

// Función para crear gráficos dinámicos
function renderChart(data, labels, title) {
  if (chart) {
    chart.destroy(); // Destruir gráfico previo
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


// Función para obtener el chipid de los parámetros de la URL
function getChipIdFromUrl() {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get('chipid'); // Obtiene el valor del parámetro 'chipid'
}

// Usamos esta función para asignar el valor de chipid
const chipid = getChipIdFromUrl();

// Ahora, `chipid` estará disponible y puedes usarlo en la llamada a `loadWeatherData`


// Función para cargar los datos meteorológicos
async function loadWeatherData(chipid) {
  const response = await fetch(`https://mattprofe.com.ar/proyectos/app-estacion/datos.php?chipid=${chipid}&cant=6`);
  const data = await response.json();
  console.log(data); // Verifica que la propiedad 'ffmc' esté presente en los datos

  return data;
}

// Función para manejar la carga de diferentes tipos de datos meteorológicos
// Función para manejar la carga de diferentes tipos de datos meteorológicos
async function updateChart(type) {
  const data = await loadWeatherData(chipid); // Cargamos los datos con el chipid proporcionado

  console.log(data); // Para revisar los datos devueltos y ver si el campo `ffmc` está presente

  let chartData = [];
  let chartLabels = [];
  let chartTitle = '';

  // Extraer datos según el tipo de gráfico
  switch(type) {
    case 'temperature':
      chartData = data.map(entry => {
        const temperature = parseFloat(entry.temperatura);
        return isNaN(temperature) ? null : temperature;  // Evitar valores inválidos
      }).filter(value => value !== null);  // Filtrar valores inválidos
      chartLabels = data.map(entry => entry.fecha); // Usamos las fechas como etiquetas
      chartTitle = 'Temperatura (°C)';
      break;
    case 'humidity':
      chartData = data.map(entry => {
        const humidity = parseFloat(entry.humedad);
        return isNaN(humidity) ? null : humidity;  // Evitar valores inválidos
      }).filter(value => value !== null);  // Filtrar valores inválidos
      chartLabels = data.map(entry => entry.fecha);
      chartTitle = 'Humedad (%)';
      break;
    case 'pressure':
      chartData = data.map(entry => {
        const pressure = parseFloat(entry.presion);
        return isNaN(pressure) ? null : pressure;  // Evitar valores inválidos
      }).filter(value => value !== null);  // Filtrar valores inválidos
      chartLabels = data.map(entry => entry.fecha);
      chartTitle = 'Presión Atmosférica (hPa)';
      break;
    case 'wind':
      chartData = data.map(entry => {
        const wind = parseFloat(entry.viento);
        return isNaN(wind) ? null : wind;  // Evitar valores inválidos
      }).filter(value => value !== null);  // Filtrar valores inválidos
      chartLabels = data.map(entry => entry.fecha);
      chartTitle = 'Velocidad del Viento (km/h)';
      break;
    case 'fire-index':
      chartData = data.map(entry => {
        const fireIndex = parseFloat(entry.ffmc);  // Verifica que `ffmc` sea la propiedad correcta
        return isNaN(fireIndex) ? null : fireIndex;  // Evitar valores inválidos
      }).filter(value => value !== null);  // Filtrar valores inválidos
      chartLabels = data.map(entry => entry.fecha);
      chartTitle = 'Índice de Fuego (FFMC)';
      break;
    default:
      chartData = [];
      chartLabels = [];
      chartTitle = 'Datos no disponibles';
  }

  // Llamamos a la función renderChart con los datos formateados
  renderChart(chartData, chartLabels, chartTitle);
}



// Llama al gráfico inicial, por ejemplo, de temperatura
updateChart('temperature');

// Asegúrate de que estos eventos de los botones están en tu código
document.getElementById('btn-temperature').addEventListener('click', () => updateChart('temperature'));
document.getElementById('btn-humidity').addEventListener('click', () => updateChart('humidity'));
document.getElementById('btn-pressure').addEventListener('click', () => updateChart('pressure'));
document.getElementById('btn-wind').addEventListener('click', () => updateChart('wind'));
document.getElementById('btn-fire-index').addEventListener('click', () => updateChart('fire-index'));
