const labels = [
  'Jan',
  'Feb',
  'Mar',
  'Apr',
  'May',
  'Jun',
  'Jul',
  'Aug',
  'Sep',
  'Oct',
  'Nov',
  'Dec'
];

const data = {
  labels: labels,
  datasets: [{
    data: [65, 59, 80, 81, 56, 55, 40, 70, 30, 45, 15, 80],
    fill: true,
    backgroundColor: ['#3047E025'],
    pointBackgroundColor: ['#3047E0'],
    borderWidth: 1,
    borderColor: ['#3047E0'],
    lineTension: .4,
  }]
};

const config = {
  type: 'line',
  data: data,
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
          mode: 'index',
          intersect: false
        },
    },
  }
};

const myChart = new Chart(
  document.getElementById('myChart'),
  config
);
