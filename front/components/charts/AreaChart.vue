<template>
  <apexchart type="area" ref="chart" :options="options" :series="series" />
</template>

<script>
import { chart } from "./mixins/chart";
import ru from "apexcharts/dist/locales/ru.json";
export default {
  name: "AreaChart",
  mixins: [chart],
  data() {
    const self = this;
    return {
      options: {
        chart: {
          locales: [ru],
          defaultLocale: "ru",
          zoom: {
            autoScaleYaxis: true,
          },
        },
        dataLabels: {
          enabled: false,
        },
        markers: {
          size: 0,
          style: "hollow",
        },
        xaxis: {
          type: "datetime",
          min: new Date("01 Mar 2012").getTime(),
          tickAmount: 6,
          labels: {
            datetimeUTC: false,
          },
        },
        tooltip: {
          x: {
            format: "dd MMM yyyy",
          },
        },
        fill: {
          type: "gradient",
          gradient: {
            shadeIntensity: 0.4,
            opacityFrom: 0.4,
            opacityTo: 0.2,
            stops: [0, 100],
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
        const response = await this.$axios.get(this.urlApi, {
          params: this.query,
        });
        if (response.status === 200) {
          const data = response.data;
          const values = data.datasets;
          this.$refs.chart.updateSeries(
            data.datasets.length === 0 ? [{}] : values
          );
          this.loading = true;
          this.$emit("update-chart");
        } else throw new Error("Значения не найдены");
      } catch (e) {
        this.loading = true;
        this.$snotify.error(
          "Значения не найдены, поменяйте интервал или измините список операторов"
        );
        this.$refs.chart.updateSeries([{}]);
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