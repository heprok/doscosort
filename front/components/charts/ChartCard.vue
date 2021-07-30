<template>
  <div>
    <base-material-card
      class="v-card--material-chart"
      v-bind="$attrs"
      :color="$vuetify.theme.dark ? 'black' : 'white'"
      v-on="$listeners"
    >
      <template v-slot:heading>
        <div v-show="loading"> 
        <slot />
        </div>
          <LoaderTlc  v-show="!loading"/>
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
        <span class="caption grey--text font-weight-light">{{
          textRefresh
        }}</span>
      </template>
    </base-material-card>
  </div>
</template>

<script>
export default {
  name: "QualitiesBarChartCard",
  data() {
    return {
      lastUpdateTime: 0,
      intervalRefresh: null,
      loading: false,
    };
  },
  props: {
    title: {
      type: String,
      default: "",
    },
    subtitle: {
      type: String,
      default: "",
    },
  },
  computed: {
    textRefresh() {
      if (this.lastUpdateTime == 1) {
        return "Обновлено минуту назад";
      }
      if (this.lastUpdateTime == 0) {
        return "Обновлено сейчас";
      }
      return (
        "Обновлено " +
        this.lastUpdateTime +
        this.getNumEnding(this.lastUpdateTime, [
          " минута",
          " минуты",
          " минут",
        ]) +
        " назад"
      );
    },
  },
  methods: {
    toggleLoaded() {
      this.loading = !this.loading;
    },
    refreshUpdate() {
      this.lastUpdateTime = 0;
    },
    getNumEnding(n, text_forms) {
      n = Math.abs(n) % 100;
      var n1 = n % 10;
      if (n > 10 && n < 20) {
        return text_forms[2];
      }
      if (n1 > 1 && n1 < 5) {
        return text_forms[1];
      }
      if (n1 == 1) {
        return text_forms[0];
      }
      return text_forms[2];
    },
    stopTimerRefresh() {
      window.clearInterval(this.intervalRefresh);
    },
    startTimerRefresh() {
      this.stopTimerRefresh();
      this.intervalRefresh = window.setInterval(() => {
        this.lastUpdateTime++;
      }, 1000 * 60);
    },
  },
  mounted() {
    this.startTimerRefresh();
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