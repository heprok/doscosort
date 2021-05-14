<template>
  <v-container id="report_operators_dashboard" fluid tag="section">
    <v-row class="card-info-operator">
      <v-col cols="6">
        <base-material-card title="Продолжительность">
          <DatePickerPlugin v-model="dates" />
          <VBtn
            block
            color="primary"
            @click="executeReport"
            :loading="loaded"
            :disabled="selectedOperator.length <= 0 || loaded"
            >Составить</VBtn
          >
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
    <!-- <v-row class="d-flex justify-space-around"> -->
    <transition-group
      class="d-flex flex-wrap justify-space-around my-16"
      name="scale-transition"
      tag="P"
    >
      <!-- <v-col cols="3" v-for="operator in selectedOperator" :key="operator.id">
          <VSheet>
            <OperatorInfoCard :operator="operator" />
          </VSheet> -->
      <!-- </v-col> -->
      <span v-for="operator in currentOperators" :key="operator.id">
        <OperatorInfoCard :duration="duration" :operator="operator" />
      </span>
    </transition-group>
    <!-- </v-row> -->
    <v-row>
      <VCol cols="6" lg="6" sm="12">
        <ChartCard
          ref="chartVolume"
          type="Bar"
          urlApi="/api/charts/volumeOnOperator"
          :query="queryChart"
          :intervalSecond='0'
          :subtitle="dates.start + ' по ' + dates.end"
          title="Выработка по операторам, объём"
        />
      </VCol>
      <VCol cols="6" lg="6" sm="12">
        <ChartCard
          ref="chartCount"
          type="Bar"
          urlApi="/api/charts/countOnOperator"
          :query="queryChart"
          :intervalSecond='0'
          :subtitle="dates.start + ' по ' + dates.end"
          title="Выработка по операторам, количество"
        />
      </VCol>
    </v-row>
  </v-container>
</template>

<script>
import ChartCard from "../../../../components/charts/ChartCard";
export default {
  name: "report_operators_dashboard",
  components: { ChartCard },

  data() {
    return {
      selectedOperator: [],
      loaded: false,
      toggleDuration: null,
      currentOperators: [],
      dates: {
        end: this.$moment().format("YYYY-MM-DD"),
        start: this.$moment().subtract(3, "months").format("YYYY-MM-DD"),
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
    duration() {
      return {
        start: this.$moment(this.dates.start).format("YYYY-MM-DDTHH:mm:ss"),
        end: this.$moment(this.dates.end).format("YYYY-MM-DDTHH:mm:ss"),
      };
    },
    queryChart() {
      return {
        operators: this.selectedOperator.map((operator) => operator.id),
        duration: this.duration,
      };
    },
  },
  watch: {
    dates: {
      handler(value) {
        this.$nextTick(async () => {
          await this.$refs.operatorTable.update();
          this.selectedOperator = [];
        });
      },
      deep: true,
    },
    selectedOperator() {},
  },
  mounted() {},
  methods: {
    async executeReport() {
      this.loaded = true;
      this.currentOperators = [];
      Object.assign(this.currentOperators, this.selectedOperator);
      await this.$refs.chartVolume.update();
      this.loaded = false;
    },
    clickRow(item) {
      this.selectedOperator.indexOf(item) == -1
        ? this.selectedOperator.push(item)
        : this.selectedOperator.splice(this.selectedOperator.indexOf(item), 1);
    },
  },
};
</script>

<style>
.card-info-operator {
  z-index: 999;
}
</style>
