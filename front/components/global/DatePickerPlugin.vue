<template>
  <VDaterange
    v-model="range"
    :display-format="displayFormat"
    no-title
    :input-props="inputProps"
    :menu-props="menuProps"
    :presets="presets"
    v-on="$listeners"
    v-bind="$attrs"
  >
  </VDaterange>
</template>

<script>
import VDaterange from "daterange-picker-vuetify";

const displayFormat = "dd.MM.yyyy";

export default {
  name: "DatePickerPlugin",
  components: { VDaterange },
  mounted() {
    // let date = this.$moment().day('Monday').format('YYYY-MM-DD');
  },
  props: {
    value: {
      type: Object,
      default: {},
      required: true,
    },
  },
  mounted() {
    // this.range = this.value
  },
  data() {
    return {
      range: this.value,
      displayFormat: displayFormat,
      inputProps: { solo: true, style: { width: "240px" } },
      menuProps: { offsetY: true, closeOnContentClick: false },
      presets: [
        {
          label: "Сегодня",
          range: [
            this.$moment().format("YYYY-MM-DD"),
            this.$moment().add(1, "days").format("YYYY-MM-DD"),
          ],
        },
        {
          label: "Текущая неделя",
          range: [
            this.$moment().day("Monday").format("YYYY-MM-DD"),
            this.$moment().format("YYYY-MM-DD"),
          ],
        },
        {
          label: "Текущий месяц",
          range: [
            this.$moment().date(1).format("YYYY-MM-DD"),
            this.$moment().format("YYYY-MM-DD"),
          ],
        },
        {
          label: "Последние 7 дней",
          range: [
            this.$moment().subtract(7, "days").format("YYYY-MM-DD"),
            this.$moment().format("YYYY-MM-DD"),
          ],
        },
        {
          label: "Последние 30 дней",
          range: [
            this.$moment().subtract(30, "days").format("YYYY-MM-DD"),
            this.$moment().format("YYYY-MM-DD"),
          ],
        },
      ],
    };
  },
};
</script>
