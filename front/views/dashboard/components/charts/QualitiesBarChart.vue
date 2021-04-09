<script>
import { HorizontalBar } from "vue-chartjs";
import ChartDataLabels from "chartjs-plugin-datalabels"
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
  mounted() {
    this.addPlugin([ChartDataLabels]);
    this.renderChart(this.chartdata, this.options);
  },
};
</script>
