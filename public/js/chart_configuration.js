async function getWinrateValues(act) {
  let winrateData = await getWinrateData(act);
  let winrateNullIndexs = verifyIndexNullValue(winrateData);

  if (winrateNullIndexs.length > 0) {
    winrateNullIndexs.forEach((index) => {
      winrateData[index] = 1;
    });
  }

  if (verifyLowestWinrate(winrateData)) {
    return [winrateData, winrateNullIndexs, true];
  }
  return [winrateData, winrateNullIndexs, false];
}

async function getHeadshotValues(act) {
  let headshotData = await getHeadshotData(act);
  let headshotNullIndexs = verifyIndexNullValue(headshotData);

  if (headshotNullIndexs.length > 0) {
    headshotNullIndexs.forEach((index) => {
      headshotData[index] = 1;
    });
  }

  if (verifyLowestWinrate(headshotData)) {
    return [headshotData, headshotNullIndexs, true];
  }
  return [headshotData, headshotNullIndexs, false];
}

async function getMapWinrateValues(act) {
  let mapWinrateData = await getMapWinrateData(act);
  if (verifyLowestWinrate(Object.values(mapWinrateData))) {
    return [mapWinrateData, true];
  }
  return [mapWinrateData, false];
}

function verifyLowestWinrate(data) {
  for (let element of data) {
    if (element < 0.1) {
      return true;
    }
  }
  return false;
}

function addOffset(data, offset = 0.05) {
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
  return nullIndexs == 0 ? [] : nullIndexs;
}

async function getWinrateData(act = null) {
  try {
    const response = await fetch(`../src/php/get_winrate_json.php?act=${act}`);
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
        winrates.push(data[day]);
      }
    });
    return winrates;
  } catch (error) {
    console.error("There was a problem with the fetch operation:", error);
  }
}

async function getHeadshotData(act = null) {
  try {
    const response = await fetch(`../src/php/get_headshot_json.php?act=${act}`);
    if (!response.ok) {
      throw new Error("Network response was not ok " + response.statusText);
    }
    const data = await response.json();
    let headshots = [];
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
        headshots.push(null);
      } else {
        headshots.push(data[day]);
      }
    });
    return headshots;
  } catch (error) {
    console.error("There was a problem with the fetch operation:", error);
  }
}

async function getMapWinrateData(act = null) {
  try {
    const response = await fetch(
      `../src/php/get_map_winrate_json.php?act=${act}`
    );
    if (!response.ok) {
      throw new Error("Network response was not ok " + response.statusText);
    }
    const data = await response.json();
    return data;
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

const ctxDaily = document.getElementById("dailyChart").getContext("2d");
const ctxMap = document.getElementById("mapChart").getContext("2d");

async function updateChart(act = null) {
  if (Chart.getChart(ctxDaily)) {
    Chart.getChart(ctxDaily).destroy();
  }
  if (Chart.getChart(ctxMap)) {
    Chart.getChart(ctxMap).destroy();
  }
  (async function () {
    let offset = 0;
    let needPadding = false;
    let [winrateData, winrateNullIndexs, winrateNeedPadding] =
      await getWinrateValues(act);
    let [headshotData, headshotNullIndexs, headshotNeedPadding] =
      await getHeadshotValues(act);
    if (winrateNeedPadding || headshotNeedPadding) {
      winrateData = addOffset(winrateData);
      headshotData = addOffset(headshotData);
      offset = 0.05;
      needPadding = true;
    }
    new Chart(ctxDaily, {
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
            label: "Winrate",
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
          },
          {
            label: "Headshots",
            data: headshotData,
            backgroundColor: function (context) {
              let value = headshotNullIndexs.includes(context.dataIndex)
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
              let value = headshotNullIndexs.includes(context.dataIndex)
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
              if (headshotNullIndexs.includes(context.dataIndex)) {
                return drawZebraStripes(context, "rgb(100, 100, 100)");
              }
              return context.dataset.borderColor(context);
            },
            hoverBorderColor: function (context) {
              if (headshotNullIndexs.includes(context.dataIndex)) {
                return "rgb(100, 100, 100)";
              }
              return context.dataset.borderColor(context);
            },
            hoverBorderWidth: function (context) {
              return headshotNullIndexs.includes(context.dataIndex) ? 3 : 0;
            },
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
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
            border: {
              display: function () {
                if (needPadding) {
                  return false;
                }
                return true;
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
              callback: function (value, index) {
                if (
                  (value === 0 && needPadding) ||
                  (index % 2 === 0 && needPadding) ||
                  (index % 2 === 1 && !needPadding)
                ) {
                  return "";
                }
                return Math.round((value - offset) * 100) + "%";
              },
              stepSize: 0.05,
              color: "rgba(255, 255, 255, 0.7)",
              font: {
                size: 20,
              },
            },
            grid: {
              color: function (context) {
                if (
                  (context.index % 2 === 0 && needPadding) ||
                  (context.index % 2 === 1 && !needPadding)
                ) {
                  return "rgba(255, 255, 255, 0.0)";
                }
                return "rgba(255, 255, 255, 0.5)";
              },
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
                    textDecoration: chart.getDatasetMeta(i).hidden
                      ? "line-through"
                      : "none",
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
                  : Math.round((context.raw - offset) * 100);

                label =
                  context.dataset.label === "Winrate"
                    ? " Winrate: "
                    : " Headshots: ";
                return value !== true
                  ? label + value + "%"
                  : " No match played for this day.";
              },
            },
          },
        },
      },
    });
  })();

  (async function () {
    let offset = 0;
    let [mapWinrateData, needPadding] = await getMapWinrateValues(act);

    if (needPadding) {
      Object.keys(mapWinrateData).forEach(
        (key) => (mapWinrateData[key] += 0.05)
      );
      offset = 0.05;
    }

    new Chart(ctxMap, {
      type: "bar",
      data: {
        labels: Object.keys(mapWinrateData),
        datasets: [
          {
            label: "Winrate",
            data: Object.values(mapWinrateData),
            backgroundColor: function (context) {
              const alpha = 0.4;
              let value = context.raw - offset;
              let index = Math.floor(value * 10);
              let rgb = COLORS[index].match(/\d+/g);
              return `rgba(${rgb[0]}, ${rgb[1]}, ${rgb[2]}, ${alpha})`;
            },
            borderColor: function (context) {
              let value = context.raw - offset;
              let index = Math.floor(value * 10);
              return COLORS[index];
            },
            borderWidth: 3,
            hoverBackgroundColor: function (context) {
              return context.dataset.borderColor(context);
            },
            hoverBorderColor: function (context) {
              return context.dataset.borderColor(context);
            },
            hoverBorderWidth: 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
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
            border: {
              display: function () {
                if (needPadding) {
                  return false;
                }
                return true;
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
              callback: function (value, index) {
                if (
                  (value === 0 && needPadding) ||
                  (index % 2 === 0 && needPadding) ||
                  (index % 2 === 1 && !needPadding)
                ) {
                  return "";
                }
                return Math.round((value - offset) * 100) + "%";
              },
              stepSize: 0.05,
              color: "rgba(255, 255, 255, 0.7)",
              font: {
                size: 20,
              },
            },
            grid: {
              color: function (context) {
                if (
                  (context.index % 2 === 0 && needPadding) ||
                  (context.index % 2 === 1 && !needPadding)
                ) {
                  return "rgba(255, 255, 255, 0.0)";
                }
                return "rgba(255, 255, 255, 0.5)";
              },
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
                let value = Math.round((context.raw - offset) * 100);
                return " Winrate: " + value + "%";
              },
            },
          },
        },
      },
    });
  })();
}

updateChart();
