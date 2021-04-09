<template>
  <base-material-card
    class="v-card--material-chart"
    v-bind="$attrs"
          v-if="loaded"
    v-on="$listeners"
  >
    <template v-slot:heading>
      <div class="container">
        <QualitiesBarChart
          :chartdata="chartdata"
          :options="options"
        />
      </div>
    </template>

    <slot slot="reveal-actions" name="reveal-actions" />

    <slot />

    <slot slot="actions" name="actions" />
  </base-material-card>
</template>

<script>
import Axios from "axios";
import QualitiesBarChart from "./QualitiesBarChart";
export default {
  name: "QualitiesBarChartCard",
  components: { QualitiesBarChart },
  data: () => ({
    loaded: false,
    options: null,
    chartdata: null,
  }),
  async mounted() {
    this.loaded = false;
    try {
      const { data } = await Axios.get("/api/charts/qualtites/currentShift");
      this.chartdata = data.chartdata;
      this.options = data.options;
      this.loaded = true;
    } catch (e) {
      console.error(e);
    }
  },
};
</script>

<style lang="sass">
.v-card--material-chart
  p
    color: #999

  .v-card--material__heading
    max-height: 1000px

    .ct-label
      color: inherit
      opacity: .7
      font-size: 0.975rem
      font-weight: 100

    .ct-grid
      stroke: rgba(255, 255, 255, 0.2)

    .ct-series-a .ct-point,
    .ct-series-a .ct-line,
    .ct-series-a .ct-bar,
    .ct-series-a .ct-slice-donut
        stroke: rgba(255,255,255,.8)

    .ct-series-a .ct-slice-pie,
    .ct-series-a .ct-area
        fill: rgba(255,255,255,.4)
</style>
