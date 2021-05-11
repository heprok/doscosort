<template>
  <v-container id="report_operators_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="6">
        <base-material-card title="Продолжительность">
          <!-- <VBtnToggle
            class="d-flex justify-space-between ma-6"
            v-model="toggleDuration"
            mandatory
          >
            <VBtn
              group
              tile
              value="week"
              color="secondary"
              @click="swapDuration()"
              >За неделю
            </VBtn>
            <VBtn
              group
              tile
              color="secondary"
              value="mount"
              @click="swapDuration()"
              >За месяц
            </VBtn>
            <VBtn
              group
              tile
              color="secondary"
              value="a half year"
              @click="swapDuration()"
              >За полгода
            </VBtn>
            <VBtn
              group
              tile
              color="secondary"
              value="year"
              @click="swapDuration()"
              >За год
            </VBtn>
          </VBtnToggle> -->
          <DatePickerPlugin v-model="dates" />
        </base-material-card>
      </v-col>
      <v-col cols="6">
        <crud-table
          title="Операторы"
          ref="operatorTable"
          url-api="/shift/peopleShiftOnDuration"
          hide-default-footer
          :query="query"
          item-key="id"
          show-select
          @click:row="clickRow"
          v-model="selectedOperator"
          icon="mdi-camera-timer"
          :headers="headers"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_operators_dashboard",

  data() {
    return {
      selectedOperator: [],
      toggleDuration: null,
      dates: {
        start: this.$moment().format("YYYY-MM-DD"),
        end: this.$moment().add(1, "days").format("YYYY-MM-DD"),
      },
      headers: [
        { text: "Фамилия", value: "fam" },
        { text: "Имя", value: "nam" },
        { text: "Отчество", value: "pat" },
      ],
    };
  },
  computed: {
    query() {
      return { start: this.dates.start, end: this.dates.end };
    },
  },
  watch: {
    dates: {
      handler(value) {
        this.$refs.operatorTable.update();
      },
      deep: true,
    },
    selectedOperator(){
      console.log(this.selectedOperator);
    }
  },
  mounted() {
    this.dates = this.$store.getters.TIME_FOR_THE_DAY(this.date);
  },
  methods: {
    clickRow(item) {
      this.selectedOperator.indexOf(item) == -1
        ? this.selectedOperator.push(item)
        : this.selectedOperator.splice(this.selectedOperator.indexOf(item), 1);
    },
  },
};
</script>
