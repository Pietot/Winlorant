function hsvToRgb(h, s, v, a) {
  const discreteHue = Math.floor(h * 100) / 100;
  const i = Math.floor(discreteHue * 6);
  const f = discreteHue * 6 - i;
  const p = v * (1 - s);
  const q = v * (1 - f * s);
  const t = v * (1 - (1 - f) * s);
  const mod = i % 6;

  let r, g, b;
  switch (mod) {
    case 0:
      [r, g, b] = [v, t, p];
      break;
    case 1:
      [r, g, b] = [q, v, p];
      break;
    case 2:
      [r, g, b] = [p, v, t];
      break;
    case 3:
      [r, g, b] = [p, q, v];
      break;
    case 4:
      [r, g, b] = [t, p, v];
      break;
    case 5:
      [r, g, b] = [v, p, q];
      break;
  }

  return `rgba(${Math.round(r * 255)}, ${Math.round(g * 255)}, ${Math.round(
    b * 255
  )}, ${a})`;
}

function whiten(color, factor) {
    // Vérifier si la couleur a un canal alpha (rgba)
    const isRgba = color.startsWith("rgba");
    
    // Extraire les composants de la couleur
    const components = color
      .slice(isRgba ? 5 : 4, -1)  // Supprime "rgba(" ou "rgb(" au début et ")" à la fin
      .split(",")                 // Sépare les valeurs par ","
      .map((x) => parseFloat(x.trim())); // Convertit chaque valeur en nombre
    
    // Séparer les composants RGB
    const [r, g, b] = components;
    
    // Calculer les nouvelles valeurs RGB en les éclaircissant
    const newR = Math.round(r + (255 - r) * factor);
    const newG = Math.round(g + (255 - g) * factor);
    const newB = Math.round(b + (255 - b) * factor);
  
    // Reconstruire la couleur en fonction de la présence ou non d'alpha
    if (isRgba) {
      const a = components[3]; // Canal alpha
      return `rgba(${newR}, ${newG}, ${newB}, ${a})`;
    } else {
      return `rgb(${newR}, ${newG}, ${newB})`;
    }
  }
  

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
        data: (function () {
          const data = [];
          for (let i = 0; i < 7; i++) {
            data.push(Math.round(Math.random() * 100) / 100 + OFFSET);
          }
          return data;
        })(),
        backgroundColor: function (context) {
          let value =
            context.raw !== null
              ? Math.round((context.raw - OFFSET) * 100) / 100
              : null;
          const alpha = 0.4;

          if (value === null) {
            return `rgba(128, 128, 128, ${alpha});`;
          }

          const hue = value / 2;
          const saturation = 1;
          const valueHSV = 1;

          return hsvToRgb(hue, saturation, valueHSV, alpha);
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
          const hue = value / 2;
          const saturation = 1;
          const valueHSV = 1;

          return hsvToRgb(hue, saturation, valueHSV, alpha);
        },
        borderWidth: 3,
        hoverBackgroundColor: function (context) {
          return context.dataset.borderColor(context);
        },
        hoverBorderColor: function (context) {
            return context.dataset.borderColor(context);
          },
        hoverBorderWidth: 3,
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
      hover: {
        mode: "nearest",
        intersect: true,
        animationDuration: 400,
      },
    },
  },
});
