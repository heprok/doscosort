<template>
  <v-stepper v-model="el">
    <v-stepper-header>
      <v-stepper-step :complete="el > 1" step="1" editable>
        Выбрать тип отчёта
      </v-stepper-step>

      <v-divider></v-divider>

      <v-stepper-step :complete="el > 2" step="2"> Параметры </v-stepper-step>
    </v-stepper-header>

    <v-stepper-items>
      <v-stepper-content step="1">
        <v-row justify="space-around">
          <v-btn color="primary" x-large @click="selectShift">
            За смену
          </v-btn>
          <v-btn color="primary" x-large @click="selectPeriod">
            За период
          </v-btn>
        </v-row>
        <v-divider class="my-4"></v-divider>
      </v-stepper-content>

      <v-stepper-content step="2">
        <div v-if="isTypeReportIsShift">
          <v-row>
            <v-col cols="4">
              <v-date-picker
                locale="ru-ru"
                :max="today"
                :first-day-of-week="1"
                v-model="date"
                full-width
              ></v-date-picker>
            </v-col>
            <v-col cols="8">
              <base-material-card
                color="success"
                icon="mdi-account-group"
                :title="'Смены на ' + date || 'выберите день...'"
                class="px-5 py-3"
              >
                <v-spacer></v-spacer>
                <v-data-table
                  :headers="headersTableShift"
                  :loading="loadingTableShifts"
                  loading-text="Загрузка... Ждите"
                  no-data-text="За данный период нет смен"
                  :items="shifts"
                  single-select
                  v-model="selectedShift"
                  item-key="startTime"
                  show-select
                  class="elevation-1"
                >
                </v-data-table>
              </base-material-card>
            </v-col>
            <v-col cols="12">
              <v-btn
                color="primary"
                :disabled="selectedShift.length == 0"
                @click="openReport"
                x-large
              >
                Составить
              </v-btn>
            </v-col>
          </v-row>
        </div>
        <div v-else>
          <v-row>
            <v-col cols="5">
              <v-date-picker
                :max="today"
                full-width
                locale="ru-ru"
                v-model="dates"
                range
                :first-day-of-week="1"
              >
              </v-date-picker>
            </v-col>
            <v-col cols="7">
              <v-text-field
                label="Выбран интервал"
                v-model="textInterval"
                readonly
                prepend-icon="mdi-calendar-range"
              >
              </v-text-field>
            </v-col>
            <v-col cols="12">
              <v-btn
                color="primary"
                :disabled="dates.length != 2"
                @click="openReport"
                x-large
              >
                Составить
              </v-btn>
            </v-col>
          </v-row>
        </div>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>
</template>

<script>
import Axios from "axios";
export default {
  name: "shiftDatePicker",
  data() {
    return {
      isTypeReportIsShift: true,
      selectIndex: {},
      selectedShift: [],
      textInterval: "",
      el: 1,
      pickerDate: null,
      date: "",
      dates: [],
      loadingTableShifts: true,
      shifts: [],
      headersTableShift: [
        { text: "ФИО", value: "people.fio" },
        { text: "Номер", value: "number" },
        { text: "Начало", value: "startTime" },
        { text: "Конец", value: "endTime" },
      ],
    };
  },
  props: {
    urlReport: {
      type: String,
      require: true
    }
  },
  computed: {
    today() {
      return new Date().toISOString().substr(0, 10);
    },
  },
  watch: {
    dates() {
      // if (this.dates.length == 0) return;
      if (this.dates[0] > this.dates[1]) {
        const tmp = this.dates[0];
        this.dates[0] = this.dates[1];
        this.dates[1] = tmp;
      }
      this.textInterval =
        "c " +
        (this.dates[0] || "[дата не выбрана]") +
        " до " +
        (this.dates[1] || "[дата не выбрана]");
      console.log(this.textInterval);
    },
    async date(value) {
      let start = value + "T00:00";
      let end = value + "T23:59";

      let config = {
        params: {
          startTimestampKey: start + "..." + end,
        },
      };

      let request = await Axios.get("api/shifts", config);
      //todo напиисать алерт при кол-ве 0
      this.shifts = request.data["hydra:member"];
      this.selectedShift = [];
      this.loadingTableShifts = false;
      return request;
    },
  },
  beforeMount() {
    this.date = this.today;
  },
  methods: {
    openReport() {
      let start = "";
      let stop = "";
      if (this.isTypeReportIsShift) {
        start = this.selectedShift[0].start;
        stop = this.selectedShift[0].stop;
      } else {
        start = this.dates[0];
        stop = this.dates[1];
      }
      window.open(this.urlReport + "/" + start + "..." + stop + '/pdf');
    },
    selectShift() {
      this.isTypeReportIsShift = true;
      this.el = 2;
    },
    selectPeriod() {
      this.isTypeReportIsShift = false;
      this.el = 2;
    },
  },
};
</script>

<style>
</style>