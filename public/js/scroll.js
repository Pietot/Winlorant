const dailyChart = document.getElementById("dailyChart");

dailyChart.addEventListener("mousemove", function (event) {
  const rect = this.getBoundingClientRect();
  const cursorY = event.clientY;
  const top5Percent = rect.top + rect.height * 0.05;

  if (cursorY > top5Percent) {
    this.style.cursor = "pointer";
  } else {
    this.style.cursor = "default";
  }
});

function setChartsHeight() {
  height = window.innerHeight * 0.9 + "px";
  const dailyChart = document.getElementById("dailyChart");
  dailyChart.style.height = height;
  const mapChart = document.getElementById("mapChart");
  mapChart.style.height = height;
}

setChartsHeight();

window.addEventListener("resize", setChartsHeight);


dailyChart.addEventListener("click", function (event) {
  const element = this;
  const rect = element.getBoundingClientRect();

  const clickY = event.clientY;
  const top5Percent = rect.top + rect.height * 0.05;

  if (clickY < top5Percent) {
    return;
  }

  const viewportHeight = window.innerHeight;
  const viewportWidth = window.innerWidth;

  const isCenteredVertically =
    rect.top + rect.height / 2 >= viewportHeight / 2 - 10 &&
    rect.top + rect.height / 2 <= viewportHeight / 2 + 10;

  if (isCenteredVertically) {
    document.getElementById("mapChart").scrollIntoView({
      behavior: "smooth",
      block: "center",
      inline: "center",
    });
  } else {
    element.scrollIntoView({
      behavior: "smooth",
      block: "center",
      inline: "center",
    });
  }
});

document.getElementById("mapChart").addEventListener("click", function () {
  const element = this;
  const rect = element.getBoundingClientRect();
  const viewportHeight = window.innerHeight;

  const isCenteredVertically =
    rect.top + rect.height / 2 >= viewportHeight / 2 - 10 &&
    rect.top + rect.height / 2 <= viewportHeight / 2 + 10;

  if (isCenteredVertically) {
    document.getElementById("dailyChart").scrollIntoView({
      behavior: "smooth",
      block: "center",
      inline: "center",
    });
  } else {
    element.scrollIntoView({
      behavior: "smooth",
      block: "center",
      inline: "center",
    });
  }
});
