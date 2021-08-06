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
          v-if="isShowChart"
          title="Выработка по операторам (объём)"
          ref="barChartCardVolume"
        >
          <BarChart
            urlApi="/api/charts/volumeOnOperator"
            ref="barChartVolume"
            :query="queryChart"
            stacked
            suffix="м³"
            horizontal
            :minuteUpdate="99"
            @toggle-loaded="$refs.barChartCardVolume.toggleLoaded()"
          />
        </ChartCard>
      </VCol>
      <VCol cols="6" lg="6" sm="12">
        <ChartCard
          v-if="isShowChart"
          title="Выработка по операторам (количество)"
          ref="barChartCardCount"
        >
          <BarChart
            urlApi="/api/charts/countOnOperator"
            ref="barChartCount"
            :query="queryChart"
            suffix="шт"
            stacked
            horizontal
            :minuteUpdate="99"
            @toggle-loaded="$refs.barChartCardCount.toggleLoaded()"
          />
        </ChartCard>
        <!-- <ChartCard
          ref="chartVolume"
          type="Bar"
          urlApi="/api/charts/volumeOnOperator"
          :query="queryChart"
          :intervalSecond="0"
          title="Выработка по операторам, объём"
        />
      </VCol>
      <VCol cols="6" lg="6" sm="12">
        <ChartCard
          ref="chartCount"
          type="Bar"
          urlApi="/api/charts/countOnOperator"
          :query="queryChart"
          :intervalSecond="0"
          title="Выработка по операторам, количество"
        /> -->
      </VCol>
    </v-row>
  </v-container>
</template>

<script>
import ChartCard from "@/components/charts/ChartCard";
import BarChart from "@/components/charts/BarChart";
export default {
  name: "report_operators_dashboard",
  components: { ChartCard, BarChart },

  data() {
    return {
      selectedOperator: [],
      loaded: false,
      toggleDuration: null,
      isShowChart: false,
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
        people: this.selectedOperator.map((operator) => operator.id),
        "period[start]": this.duration.start,
        "period[end]": this.duration.end,
      };
    },
  },
  watch: {
    dates: {
      handler(value) {
        this.isShowChart = false;
        this.$nextTick(async () => {
          await this.$refs.operatorTable.update();
          this.selectedOperator = [];
        });
      },
      deep: true,
    },
    selectedOperator() {
      this.isShowChart = false;
    },
  },
  mounted() {},
  methods: {
    async executeReport() {
      this.loaded = true;
      this.currentOperators = [];
      Object.assign(this.currentOperators, this.selectedOperator);
      this.isShowChart = true;
      this.$nextTick(() => {
        this.$refs.barChartVolume.setup();
        this.$refs.barChartCount.setup();
      });
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
