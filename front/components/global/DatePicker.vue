<template>
  <v-date-picker
    :max="today"
    full-width
    locale="ru-ru"
    v-model="dates"
    range
    v-on="$listeners"
    v-bind="$attrs"
    :first-day-of-week="1"
  >
  </v-date-picker>
</template>

<script>
export default {
  name: "datePicker",
  props: {
    value: {
      type: Array,
      required: true,
      default: () => [],
    },
  },
  data() {
    return {
      dates: [this.today, this.today],
      timeDefaultDay: {},
    };
  },
  watch: {
    async dates(val) {
      if (new Date(this.dates[0]) > new Date(this.dates[1])) {
        const tmp = this.dates[0];
        this.dates[0] = this.dates[1];
        this.dates[1] = tmp;
      } else if (new Date(this.dates[0]) == new Date(this.dates[1])) {
        const day = this.$store.getters.TIME_FOR_THE_DAY(this.dates[0]);
        this.dates = [day.start, day.end];
      }
      this.$emit("input", this.dates, this.textInterval);
    },
  },
  mounted() {
    this.timeDefaultDay = this.$store.state.timeForDay;
  },
  methods: {
  },
  computed: {
    today() {
      return new Date().toISOString().substr(0, 10);
    },
    textInterval() {
      // if (this.dates.length == 0) return;
      let result =
        "c " +
        (this.dates[0] || "[дата не выбрана]") +
        " " +
        this.timeDefaultDay +
        " до " +
        (this.dates[1] || "[дата не выбрана]") +
        " " +
        this.timeDefaultDay;

      return result;
    },
  },
};
</script>

<style>
</style>