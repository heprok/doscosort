<template>
  <div v-if="loaded && chartdata.datasets.length == 0"></div>
  <base-material-card
    class="v-card--material-chart"
    v-bind="$attrs"
    :color="$vuetify.theme.dark ? 'black' : 'grey'"
    v-else-if="loaded"
    v-on="$listeners"
  >
    <template v-slot:heading>
      <HBarChart
        v-if="type === 'HBar'"
        :suffix="suffix"
        :chartdata="chartdata"
        :options="options"
      />
      <BarChart
        v-else-if="type === 'Bar'"
        :suffix="suffix"
        :chartdata="chartdata"
        :options="options"
      />
    </template>

    <slot slot="reveal-actions" name="reveal-actions" />
    <h4 class="card-title font-weight-light mt-2 ml-2">
      {{ title }}
    </h4>

    <p class="d-inline-flex font-weight-light ml-2 mt-1">
      {{ subtitle }}
    </p>
    <template v-slot:actions>
      <v-icon class="mr-1" small> mdi-clock-outline </v-icon>
      <span class="caption grey--text font-weight-light"
        >обновлено {{ lastUpdateTime }} минут назад</span
      >
    </template>
  </base-material-card>
  <VSheet v-else>
    <LoaderTlc />
  </VSheet>
</template>

<script>
import Axios from "axios";
import HBarChart from "./HBarChart";
import BarChart from "./BarChart";
export default {
  name: "QualitiesBarChartCard",
  components: { HBarChart, BarChart },
  data: () => ({
    loaded: false,
    options: null,
    chartdata: {
      datasets: []
    },
    interval: null,
    height: null,
    lastUpdateTime: -1,
  }),
  computed: {},
  props: {
    urlApi: {
      type: String,
      required: true,
    },
    query: {
      type: Object,
      default: () => {},
    },
    suffix: {
      type: String,
      default: "",
    },
    type: {
      type: String,
      required: true,
    },
    intervalSecond: {
      type: Number,
      default: 1000 * 60 * 5, // 5 минут
    },
    title: {
      type: String,
      default: "",
    },
    subtitle: {
      type: String,
      default: "",
    },
  },
  methods: {
    stopTimerRefresh() {
      if (this.interval) {
        window.clearInterval(this.interval);
      }
    },
    startTimerRefresh() {
      this.stopTimerRefresh();
      this.interval = window.setInterval(() => {
        this.update();
      }, this.intervalSecond);
    },
    async update() {
      const config = {
        params: this.query,
      };
      this.loaded = false;
      try {
        const { data } = await Axios.get(this.urlApi, config);
        this.chartdata = data.chartdata;
        this.options = data.options;
        this.height = data.chartdata.labels.length * 80;
        // console.log(this.height, data.chartdata.labels);
        this.loaded = true;
        this.lastUpdateTime = 0;
        // this.$refs.chart.reset();
      } catch (e) {
        console.error(e);
      }
    },
  },
  beforeDestroy() {
    if (this.intervalSecond) this.stopTimerRefresh();
  },
  async mounted() {
    // this.update();
    // console.log(this.intervalSecond);
    if (this.intervalSecond) {
      setInterval(
        () => (this.lastUpdateTime = this.lastUpdateTime + 1),
        1000 * 60 * 1
      );
      this.startTimerRefresh();
    }
    await this.update();
  },
};
</script>

<style lang="sass">
.v-card--material-chart
  position: relative
  p
    color: #95a5a6

  .v-card--material__heading
    max-height: 1200px
</style>
