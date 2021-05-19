<script>
import { HorizontalBar } from "vue-chartjs";
import ChartDataLabels from "chartjs-plugin-datalabels";
export default {
  extends: HorizontalBar,
  props: {
    chartdata: {
      type: Object,
      default: null,
    },
    suffix: {
      type: String,
      default: "",
    },
  },
  watch: {},
  mounted() {
    const vm = this;
    let options = {
      plugins: {
        datalabels: {
          color: '#000',
          formatter: (value, context) => {
            return value + vm.suffix;
          },
          display: true,
          font: {
            // size: 20,
          },
        },
      },
      legend: {
        labels: {
          fontColor: this.colorFont,
        },
      },
      scales: {
        yAxes: [
          {
            ticks: {
              // fontSize: 20,
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
    this.renderChart(this.chartdata, options);
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
