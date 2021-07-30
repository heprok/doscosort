export const chart = {
  data() {
    return {
      series: [],
      intervalUpdate: null,
    }
  },
  props: {
    urlApi: {
      type: String,
      required: true
    },
    secondUpdate: {
      type: Number,
      default: 1000 * 5, // 5 секунд
    },
    suffix: {
      type: String,
      default: '%', // 5 секунд
    },
  },
  methods: {
    appendData(appendData) {
      let append = [];
      appendData.forEach((item) => {
        append.push({
          data: item.data,
        });
      });
      this.$refs.chart.appendData(append);
    },
    updateSeries(datasets) {
      this.$refs.chart.updateSeries(datasets);
    },
    changeTheme() {
      const mode =
      localStorage.getItem("dark_theme") === "true" ? "dark" : "light";
      this.$refs.chart.updateOptions({
        theme: {
          mode: mode,
        },
        chart: {
          foreColor: mode === "dark" ? "#f6f7f8" : "#373d3f",
        },
        tooltip: {
          theme: mode,
        },
      });
    },
    stopTimerUpdate() {
      window.clearInterval(this.intervalUpdate);
    },
    startTimerUpdate() {
      this.stopTimerUpdate();
      this.intervalUpdate = window.setInterval(() => {
        this.setup();
      }, this.secondUpdate);
    },

  },
  beforeDestroy() {
    if (this.secondUpdate) this.stopTimerUpdate();
    this.stopTimerRefresh();
    this.$refs.chart.destroy();
    this.$eventBus.$off("change-theme");
  },
  async mounted() {
    this.startTimerUpdate();
    await this.setup();
    this.$eventBus.$on("change-theme", () => {
      this.changeTheme();
    });
    this.changeTheme();
  },

}

