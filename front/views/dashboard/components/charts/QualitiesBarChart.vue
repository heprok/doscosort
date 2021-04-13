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
    options: {
      type: Object,
      default: null,
    },
  },
  watch: {
  },
  mounted() {
    this.options.scales = {
      yAxes: [{
        gridLines: {
          color: this.colorScale,
        },
      }],
      
      xAxes:[ {
        gridLines: {
          color: this.colorScale
        }
      }],
    }
    this.addPlugin([ChartDataLabels]);
    this.renderChart(this.chartdata, this.options);
  },
  computed: {
    colorScale() {
      return this.$vuetify.theme.dark ? this.$vuetify.theme.themes.dark.secondary : this.$vuetify.theme.themes.light.greyLight
    }
  }
};
</script>
