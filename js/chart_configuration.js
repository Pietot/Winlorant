const COLORS = [
  "rgb(255, 0, 0)",
  "rgb(255, 77, 0)",
  "rgb(255, 153, 0)",
  "rgb(255, 229, 0)",
  "rgb(204, 255, 0)",
  "rgb(128, 255, 0)",
  "rgb(51, 255, 0)",
  "rgb(0, 255, 25)",
  "rgb(0, 255, 102)",
  "rgb(0, 255, 179)",
  "rgb(0, 255, 255)",
];
const ctx = document.getElementById("myChart").getContext("2d");
const OFFSET = 0.1;

const myChart = new Chart(ctx, {
  type: "bar",
  data: {
    labels: [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
      "Sunday",
    ],
    datasets: [
      {
        data: [null, 0.2, 0.3, 0.4, 0.5, 0.6, 1.1],
        backgroundColor: function (context) {
          let value =
            context.raw !== null
              ? Math.round((context.raw - OFFSET) * 100) / 100
              : null;
          const alpha = 0.4;

          if (value === null) {
            return `rgba(128, 128, 128, ${alpha});`;
          }

          let index = Math.floor((value) * 10);
          let rgb = COLORS[index].match(/\d+/g);
          return `rgba(${rgb[0]}, ${rgb[1]}, ${rgb[2]}, ${alpha})`;
        },
        borderColor: function (context) {
          let value =
            context.raw !== null
              ? Math.round((context.raw - OFFSET) * 100) / 100
              : null;
          const alpha = 1;

          if (value === null) {
            return `rgba(128, 128, 128, ${alpha});`;
          }
          let index = Math.floor((value) * 10);
          return COLORS[index];
        },
        borderWidth: 3,
        hoverBackgroundColor: function (context) {
          return context.dataset.borderColor(context);
        },
        hoverBorderWidth: 0,
      },
    ],
  },
  options: {
    scales: {
      x: {
        grid: {
          color: function (context) {
            if (context.tick && context.tick.value === 0) {
              return "rgba(255, 255, 255, 0.5)";
            }
            return "rgba(255, 255, 255, 0.0)";
          },
        },
        ticks: {
          color: "rgba(255, 255, 255, 0.7)",
          font: {
            size: 20,
          },
        },
      },
      y: {
        beginAtZero: true,
        max: 1.1,
        min: 0,
        ticks: {
          callback: function (value) {
            if (value === 0) {
              return "";
            }
            return Math.round((value - 0.1) * 100) + "%";
          },
          stepSize: 0.1,
          color: "rgba(255, 255, 255, 0.7)",
          font: {
            size: 20,
          },
        },
        grid: {
          color: "rgba(255, 255, 255, 0.5)",
        },
      },
    },
    plugins: {
      legend: {
        display: false,
      },
      tooltip: {
        callbacks: {
          label: function (context) {
            if (context.raw === null) {
              return "No game played yet this day";
            }
            let value =
              context.raw !== null
                ? Math.round((context.raw - OFFSET) * 100) / 100
                : null;
            return " Winrate: " + Math.round(value * 100) + "%";
          },
        },
      },
    },
  },
});
