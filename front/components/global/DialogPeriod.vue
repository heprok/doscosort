<template>
  <v-dialog v-model="value" persistent max-width="600px">
    <v-card>
      <v-card-title>Выбранный интервал</v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <DatePickerPlugin v-show="!isCurrentShift" v-model="period" />
        <VBtn
          class="pa-5"
          block
          :color="isCurrentShift ? 'secondary' : ''"
          @click="isCurrentShift = !isCurrentShift"
          >Текущая смена</VBtn
        >
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn color="primary darken-1" block @click="saveDialogPeriod">
          Применить
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "DialogPeriod",
  data: () => ({
    period: {},
    isCurrentShift: false,
  }),
  props: {
    selected: {
      type: Object,
      required: true,
    },
    value: {
      type: Boolean,
      required: true,
    },
  },
  mounted() {
    this.period = this.selected;
    console.log(this.selected)
  },
  methods: {
    saveDialogPeriod() {
      
      if ((!this.period.start || !this.period.end) && !tthis.isCurrentShift) {
        this.$snotify.error("Интервал не выбран!");
        return;
      }
      if (this.isCurrentShift) {
        this.period = { currentShift: true };
      }
      this.$emit("change", this.period);
    },
  },
};
</script>

<style>
</style>