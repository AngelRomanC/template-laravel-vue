<template>
  <div>
    <!-- Se asegura de que 'chartData' y 'options' no sean undefined antes de pasarlos al gráfico -->
    <Line :data="chartData ? chartData : defaultChartData" :options="options || defaultOptions" />
  </div>
</template>

<script setup>
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js';

// Registramos los elementos necesarios para gráficos de línea
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement
);

// Definimos las propiedades que recibirá el componente
const props = defineProps({
  chartData: {
    type: Object,
    required: false,
    default: () => ({ labels: [], datasets: [] }) // Definir valores predeterminados para 'data'
  },
  options: {
    type: Object,
    required: false,
    default: () => ({}) // Valor por defecto para options si no se pasa
  }
});

// Datos predeterminados para el gráfico en caso de que no se reciban
const defaultChartData = {
  labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago'], // Ejemplo de etiquetas
  datasets: [
    {
      label: 'Dataset 1',
      backgroundColor: '#42A5F5',
      borderColor: '#42A5F5',
      data: [65, 59, 80, 81, 56, 55, 40, 75],
      fill: false,
    },
    {
      label: 'Dataset 2',
      backgroundColor: '#66BB6A',
      borderColor: '#66BB6A',
      data: [28, 48, 40, 19, 86, 27, 90, 60],
      fill: false,
    },
  ],
};

const defaultOptions = {
  responsive: true,
  plugins: {
    title: {
      display: true,
      text: 'Gráfico de Línea',
    },
    tooltip: {
      mode: 'index',
      intersect: false,
    },
  },
  scales: {
    x: {
      beginAtZero: true,
    },
    y: {
      beginAtZero: true,
    },
  },
};
</script>
