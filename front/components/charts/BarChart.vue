<script>
import { Bar } from "vue-chartjs";
// import ChartDataLabels from "chartjs-plugin-datalabels";
export default {
  extends: Bar,
  props: {
    chartdata: {
      type: Object,
      default: null,
    },
  },
  watch: {},
  mounted() {
    this.options = {
      plugins: {
        datalabels: {
          display: false,
        },
      },
      legend: {
        position: "right",
        labels: {
          fontColor: this.colorFont,
        },
      },
      scales: {
        yAxes: [
          {
            ticks: {
              fontColor: this.colorFont,
            },
            gridLines: {
              color: this.colorScale,
            },
          },
        ],

        xAxes: [
          {
            ticks: {
              fontColor: this.colorFont,
            },
            gridLines: {
              color: this.colorScale,
            },
          },
        ],
      },
    };
    // this.addPlugin([ChartDataLabels]);
    this.renderChart(this.chartdata, this.options);
  },
  computed: {
    colorScale() {
      return this.$vuetify.theme.dark
        ? this.$vuetify.theme.themes.dark.secondary
        : this.$vuetify.theme.themes.light.greyLight;
    },
    colorFont() {
      return this.$vuetify.theme.dark ? "#fff" : "#000";
    },
  },
};
</script>
