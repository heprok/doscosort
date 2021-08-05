<template>
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
      <LoaderTlc v-show="!loading" />
    </template>
    <DialogPeople
      v-model="people.dialog"
      :selected="people.selected"
      @change="saveDialogPeople"
    />
    <DialogPeriod
      v-model="period.dialog"
      :selected="period.selected"
      @change="saveDialogPeriod"
    />
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
</template>

<script>
export default {
  name: "ChartCard",
  chartName: null,
  data() {
    return {
      people: {
        dialog: false,
        selected: [],
      },
      period: {
        dialog: false,
        selected: {},
      },
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
  },
  computed: {
    subtitle() {
      if (this.period.selected.isCurrentShift) return "Текущая смена";

      const start = this.$moment(this.period.selected.start);
      const end = this.$moment(this.period.selected.end);
      const duration = this.$moment.duration(end.diff(start));

      const months = end.diff(start, 'months', false);
      start.add(months, 'months');
      const days = end.diff(start, 'days', false);
      let result = months > 0 ? months  + ' ' + this.getNumEnding(months, ['месяц', 'месяца', 'месяцев']) + ' и ' : '';
      return result += days + ' ' + this.getNumEnding(days, ['день', 'дня', 'дней'])
      
    },
    query() {
      return {
        "period[start]": this.period.selected.start,
        "period[end]": this.period.selected.end,
        currentShift: this.period.selected.currentShift,
        people: this.people.selected.map((people) => people.id),
      };
    },
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
    openMenuPeriod() {
      this.period.dialog = true;
    },
    saveDialogPeriod(selectedPeriod) {
      this.period.selected = selectedPeriod;
      localStorage.setItem(
        "chart_" + this.chartName + "_period",
        JSON.stringify(this.period.selected)
      );
      this.$emit("update-query", this.query);
      this.period.dialog = false;
    },
    saveDialogPeople(selectedPeople) {
      this.people.selected = selectedPeople;
      localStorage.setItem(
        "chart_" + this.chartName + "_people",
        JSON.stringify(this.people.selected)
      );
      this.$emit("update-query", this.query);
      this.people.dialog = false;
    },
    openMenuPeople() {
      this.people.dialog = true;
    },
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
  beforeMount() {
    this.chartName = this.$options._parentVnode.data.ref;
    this.people.selected =
      JSON.parse(localStorage.getItem("chart_" + this.chartName + "_people")) ??
      [];
    this.period.selected = JSON.parse(
      localStorage.getItem("chart_" + this.chartName + "_period")
    ) ?? {
      start: this.$moment().date(1).format("YYYY-MM-DD"),
      end: this.$moment().format("YYYY-MM-DD"),
    };
  },
  mounted() {
    this.startTimerRefresh();
    this.$emit("update-query", this.query);
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