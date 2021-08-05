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
        },
        plotOptions: {
          bar: {
            barHeight: "80%",
            distributed: true,
            horizontal: self.horizontal,
            dataLabels: {
              position: "center",
            },
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
            return val + self.suffix;
          },
          offsetX: 0,
          dropShadow: {
            enabled: true,
          },
        },
        tooltip: {
          y: {
            title: {
              formatter: function () {
                return "";
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
  },
  methods: {
    async setup() {
      try {
        this.loading = false;
        const { data } = await this.$axios.get(this.urlApi, {params: this.query});
        const values = data.datasets.map((dataset) => dataset.data[0]);
        this.$refs.chart.updateOptions({
          xaxis: {
            categories: data.labels,
          },
        });
        this.$refs.chart.updateSeries(
          data.length === 0 ? [{}] : [{ data: values }]
        );
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