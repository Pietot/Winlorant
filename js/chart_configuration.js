async function getWinrateValues() {
  let winrateData = await getWinrateData();
  let winrateNullIndexs = verifyIndexNullValue(winrateData);

  winrateNullIndexs.forEach((index) => {
    winrateData[index] = 1;
  });

  if (verifyLowestWinrate(winrateData)) {
    const offset = 0.1;
    needPadding = true;
    winrateData = addOffset(winrateData, offset);
    return [winrateData, winrateNullIndexs, offset, needPadding];
  }
  return [winrateData, winrateNullIndexs, 0, false];
}

function verifyLowestWinrate(data) {
  for (let element of data) {
    if (element !== null && element < 0.1) {
      return true;
    }
  }
  return false;
}

function addOffset(data, offset = 0) {
  data.forEach((element, index) => {
    if (element !== null) {
      data[index] = element + offset;
    } else {
      data[index] = 1 + offset;
    }
  });
  return data;
}

function verifyIndexNullValue(data) {
  let nullIndexs = [];
  for (let index = 0; index < data.length; index++) {
    let element = data[index];
    if (element === null) {
      nullIndexs.push(index);
    }
  }
  return nullIndexs == 0 ? false : nullIndexs;
}

async function getWinrateData() {
  try {
    const response = await fetch("php/get_winrate_json.php");
    if (!response.ok) {
      throw new Error("Network response was not ok " + response.statusText);
    }
    const data = await response.json();
    let winrates = [];
    let days = [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
      "Sunday",
      "Global",
    ];

    days.forEach((day) => {
      if (data[day] === "No games played") {
        winrates.push(null);
      } else {
        winrates.push(data[day] / 100);
      }
    });
    return winrates;
  } catch (error) {
    console.error("There was a problem with the fetch operation:", error);
  }
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

const LABEL_COLORS = ["cyan", "red"];

const ctx = document.getElementById("myChart").getContext("2d");

(async function () {
  let [winrateData, winrateNullIndexs, offset, needPadding] =
    await getWinrateValues();
  new Chart(ctx, {
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
        "Global",
      ],
      datasets: [
        {
          data: winrateData,
          backgroundColor: function (context) {
            let value = winrateNullIndexs.includes(context.dataIndex)
              ? true
              : context.raw - offset;
            const alpha = 0.4;
            if (value === true) {
              return drawZebraStripes(context, "rgb(150, 150, 150)");
            }
            let index = Math.floor(value * 10);
            let rgb = COLORS[index].match(/\d+/g);
            return `rgba(${rgb[0]}, ${rgb[1]}, ${rgb[2]}, ${alpha})`;
          },
          borderColor: function (context) {
            let value = winrateNullIndexs.includes(context.dataIndex)
              ? true
              : context.raw - offset;

            if (value === true) {
              return "rgb(150, 150, 150)";
            }
            let index = Math.floor(value * 10);
            return COLORS[index];
          },
          borderWidth: 3,
          hoverBackgroundColor: function (context) {
            if (winrateNullIndexs.includes(context.dataIndex)) {
              return drawZebraStripes(context, "rgb(100, 100, 100)");
            }
            return context.dataset.borderColor(context);
          },
          hoverBorderColor: function (context) {
            if (winrateNullIndexs.includes(context.dataIndex)) {
              return "rgb(100, 100, 100)";
            }
            return context.dataset.borderColor(context);
          },
          hoverBorderWidth: function (context) {
            return winrateNullIndexs.includes(context.dataIndex) ? 3 : 0;
          },
          label: "Winrate",
        },
        {},
      ],
    },
    options: {
      responsive: true,
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
          labels: {
            generateLabels: function (chart) {
              const labels = chart.data.datasets.map(function (dataset, i) {
                return {
                  text: dataset.label,
                  fillStyle: LABEL_COLORS[i],
                  strokeStyle: LABEL_COLORS[i],
                  lineCap: dataset.borderCapStyle,
                  lineDash: dataset.borderDash,
                  lineDashOffset: dataset.borderDashOffset,
                  lineJoin: dataset.borderJoinStyle,
                  lineWidth: dataset.borderWidth,
                  hidden: chart.getDatasetMeta(i).hidden,
                  datasetIndex: i,
                  fontColor: "rgb(255, 255, 255)",
                };
              });
              return labels;
            },
            font: {
              size: 20,
            },
          },
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              let value = winrateNullIndexs.includes(context.dataIndex)
                ? true
                : Math.round((context.raw - offset) * 10000) / 100;
              return value !== true
                ? " Winrate: " + value + "%"
                : " No match played for this day.";
            },
          },
        },
      },
    },
  });
})();
