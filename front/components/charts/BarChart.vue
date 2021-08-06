<template>
  <apexchart type="bar" ref="chart" :options="options" :series="series" />
</template>

<script>
import { chart } from "./mixins/chart";
export default {
  name: "BarChart",
  mixins: [chart],
  data() {
    const self = this;
    return {
      options: {
        chart: {
          type: "bar",
          stacked: self.stacked,
          stackType: "100%",
        },
        plotOptions: {
          bar: {
            barHeight: self.barHeight + '%',
            horizontal: self.horizontal,
          },
        },
        legend: {
          fontSize: "18px",
        },
        dataLabels: {
          enabled: true,
          textAnchor: "start",
          style: {
            colors: ["#304758"],
            fontSize: "14px",
          },
          formatter: function (val, opt) {
            return val == 0 ? '' : Math.round(val) + '%';
          },
          offsetX: 0,
          dropShadow: {
            enabled: true,
          },
        },
        tooltip: {
          tooltip: {
            y: {
              formatter: function (val) {
                return val + self.suffix;
              },
            },
          },
        },
        xaxis: {
          labels: {
            style: {
              fontSize: "18px",
            },
          },
          categories: [],
        },
        yaxis: {
          labels: {
            style: {
              fontSize: "18px",
            },
          },
        },
      },
    };
  },
  props: {
    horizontal: {
      type: Boolean,
      default: false,
    },
    stacked: {
      type: Boolean,
      default: false,
    },
    barHeight: {
      type: String,
      default: "80%",
    },
  },
  methods: {
    async setup() {
      try {
        this.loading = false;
        const { data } = await this.$axios.get(this.urlApi, {
          params: this.query,
        });
        const values = data.datasets;
        this.$refs.chart.updateOptions({
          xaxis: {
            categories: data.labels,
          },
        });
        this.$refs.chart.updateSeries(values);
        this.loading = true;
        this.$emit("update-chart");
      } catch (e) {
        console.error(e);
      }
    },
  },
};
</script>

<style lang="sass">
.apexcharts-menu
  background-color: #292929!important
  border: 0px
.apexcharts-tooltip
  color: #999
  background: rgba(30, 30, 30, 0.8)
</style>