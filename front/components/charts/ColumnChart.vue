<template>
  <apexchart type="bar" ref="chart" :options="options" :series="series" />
</template>

<script>
import { chart } from "./mixins/chart";
export default {
  name: "ColumnChart",
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
            columnWidth: "55%",
            endingShape: "rounded",
            horizontal: false,
            dataLabels: {
              orientation: 'vertical',
              position: "center", // top, center, bottom
            },
          },
        },
        stroke: {
          show: true,
          width: 2,
          // colors: ["transparent"],
        },
        legend: {
          fontSize: "18px",
        },
        tooltip: {
          y: {
            title: {
              formatter: function (val) {
                return val;
              },
            },
          },
        },
        fill: {
          opacity: 1,
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val;
          },
          offsetY: -20,
          labels: {
            rotate: -45,
          },
          style: {
            fontSize: "12px",
            colors: ["#304758"],
          },
        },
        xaxis: {
          position: "top",
          offsetY: 10,
          axisBorder: {
            show: false,
          },
          axisTicks: {
            show: false,
          },
          crosshairs: {
            fill: {
              type: "gradient",
              gradient: {
                colorFrom: "#D8E3F0",
                colorTo: "#BED1E6",
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              },
            },
          },
          tooltip: {
            enabled: true,
          },
          labels: {
            style: {
              fontSize: "18px",
            },
          },
          categories: [],
        },
        yaxis: {
          title: {
            text: "м³ (Объём)",
          },
          labels: {
            style: {
              fontSize: "18px",
            },
          },
        },
      },
    };
  },
  props: {},
  methods: {
    updateOptions(obj) {
      this.$refs.chart.updateOptions(obj);
    },
    async setup() {
      try {
        this.loading = false;
        const { data } = await this.$axios.get(this.urlApi, {params: this.query});
        const values = data.datasets;
        this.$refs.chart.updateOptions({
          xaxis: {
            categories: data.labels,
          },
        });
        this.$refs.chart.updateSeries(
          data.datasets.length === 0 ? [{}] : values
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