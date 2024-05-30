document.addEventListener("DOMContentLoaded", function () {
  var ctx = document.getElementById("topPostersChart").getContext("2d");
  var chartData = {
    labels: JSON.parse(document.getElementById("chartData").dataset.names),
    datasets: [
      {
        label: "投稿数",
        data: JSON.parse(document.getElementById("chartData").dataset.counts),
        backgroundColor: "rgba(54, 162, 235, 0.2)",
        borderColor: "rgba(54, 162, 235, 1)",
        borderWidth: 1,
      },
    ],
  };
  var chartOptions = {
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  };
  var chart = new Chart(ctx, {
    type: "bar",
    data: chartData,
    options: chartOptions,
  });
});
