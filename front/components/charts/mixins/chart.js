export const chart = {
  data() {
    return {
      series: [],
      intervalUpdate: null,
      loading: false,
    }
  },
  props: {
    urlApi: {
      type: String,
      required: true
    },
    minuteUpdate: {
      type: Number,
      default: 5, // 5 минут
    },
    suffix: {
      type: String,
      default: '',
    },
    showDialogPeople: {
      type: Boolean,
      default: false,
    },
    showDialogPeriod: {
      type: Boolean,
      default: false,
    }
  },
  watch: {
    loading() {
      this.$emit('toggle-loaded');
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
    appendIconSelectPeople() {
      const self = this;
      this.$refs.chart.updateOptions({
          toolbar: {
            show: true,
            tools: {
              customIcons: [
                {
                  icon: '<span class="mdi mdi-18px mdi-account-group"></span>',
                  index: 1,
                  title: "Выбранные персонал",
                  class: "custom-icon",
                  click: () => {
                    self.$emit("open-menu-people");
                  },
                },
              ],
            }
          },
      })
    },
    appendIconSelectPeriod() {
      const self = this;
      this.$refs.chart.updateOptions({
        chart: {
          toolbar: {
            show: true,
            tools: {
              customIcons: [
                {
                  icon: '<span class="mdi mdi-18px mdi-calendar"></span>',
                  index: 1,
                  title: "Период",
                  class: "custom-icon",
                  click: () => {
                    self.$emit("open-menu-date");
                  },
                },
              ],
            }
          },
        }
      })
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
      }, this.minuteUpdate * 60 * 1000);
    },

  },
  beforeDestroy() {
    if (this.minuteUpdate) this.stopTimerUpdate();
    this.$refs.chart.destroy();
    this.$eventBus.$off("change-theme");
  },
  async mounted() {
    this.startTimerUpdate();
    await this.setup();
    if (this.showDialogPeople) this.appendIconSelectPeople();
    if (this.showDialogPeriod) this.appendIconSelectPeriod();
    this.$eventBus.$on("change-theme", () => {
      this.changeTheme();
    });
    this.changeTheme();
  },

}

