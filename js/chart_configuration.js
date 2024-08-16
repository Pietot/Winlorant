function verifyLowestWinrate(data) {
  for (let element of data) {
    if (element < 0.1) {
      return true;
    }
  }
  return false;
}

function addOffset(data) {
  needPadding = true;
  data.forEach((element, index) => {
    if (element !== true) {
      data[index] = element + offset;
    }
  });
  return data;
}

function getData() {
  return [true, 0.01, 0.3, 0.4, 0.5, 0.6, 1];
}

function drawZebraStripes(context, strokeStyle) {
  const canvas = document.createElement("canvas");
  const size = 50;
  canvas.width = size;
  canvas.height = size;
  const ctx = canvas.getContext("2d");

  ctx.fillStyle = "rgba(0, 0, 0, 0)";
  ctx.fillRect(0, 0, size, size);
  ctx.strokeStyle = strokeStyle;
  ctx.lineWidth = 2;
  ctx.beginPath();
  ctx.moveTo(0, 0);
  ctx.lineTo(size, size);
  ctx.moveTo(-size / 2, size / 2);
  ctx.lineTo(size / 2, size + size / 2);
  ctx.moveTo(size / 2, -size / 2);
  ctx.lineTo(size + size / 2, size / 2);
  ctx.stroke();
  return context.chart.ctx.createPattern(canvas, "repeat");
}

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

let offset = 0;
let needPadding = false;
const ctx = document.getElementById("myChart").getContext("2d");
const DATA = (function () {
  let data = getData();
  if (verifyLowestWinrate(data)) {
    offset = 0.1;
    needPadding = true;
    return addOffset(data);
  }
  console.log(data);
  return data;
})();

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
        data: DATA,
        backgroundColor: function (context) {
          let value =
            context.raw === true
              ? true
              : Math.round((context.raw - offset) * 100) / 100;
          const alpha = 0.4;

          if (value === true) {
            return drawZebraStripes(context, "rgb(150, 150, 150)");
          }
          let index = Math.floor(value * 10);
          let rgb = COLORS[index].match(/\d+/g);
          return `rgba(${rgb[0]}, ${rgb[1]}, ${rgb[2]}, ${alpha})`;
        },
        borderColor: function (context) {
          let value =
            context.raw === true
              ? true
              : Math.round((context.raw - offset) * 100) / 100;

          if (value === true) {
            return "rgb(150, 150, 150)";
          }
          let index = Math.floor(value * 10);
          return COLORS[index];
        },
        borderWidth: 3,
        hoverBackgroundColor: function (context) {
          if (context.raw === true) {
            return drawZebraStripes(context, "rgb(100, 100, 100)");
          }
          return context.dataset.borderColor(context);
        },
        hoverBorderColor: function (context) {
          if (context.raw === true) {
            return "rgb(100, 100, 100)";
          }
          return context.dataset.borderColor(context);
        },
        hoverBorderWidth: function (context) {
          return context.raw === true ? 3 : 0;
        },
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
        max: function () {
          return needPadding ? 1 + offset : 1;
        },
        min: 0,
        ticks: {
          callback: function (value) {
            if (value === 0 && needPadding) {
              return "";
            }
            return Math.round((value - offset) * 100) + "%";
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
            let value =
              context.raw === true
                ? true
                : Math.round((context.raw - offset) * 100);
            return value !== true
              ? " Winrate: " + Math.round(value) + "%"
              : " No match played for this day.";
          },
        },
      },
    },
  },
});
