<template>
  <v-container id="report_unload_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/unload" :filterSqlWhere="filters">
        </shift-date-picker>
      </v-col>
      <v-col cols="12">
        <crud-table
          title="Выгруженные карманы за сегодняшний день"
          url-api="/unloads"
          :query="query"
          icon="mdi-chevron-double-down"
          :headers="headers"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_unload_dashboard",

  data() {
    return {
      filters: ["pocket"],
      headers: [
        { text: "Время", value: "time" },
        { text: "№ кармана", value: "pocket" },
        { text: "Порода", value: "group.species.name" },
        { text: "Качество", value: "qualities" },
        { text: "Сечение", value: "group.cut" },
        { text: "Длина", value: "group.intervalLength" },
        { text: "Кол-во", value: "amount" },
        { text: "Объём", value: "volume" },
      ],
    };
  },
  mounted() {},
  methods: {},
  computed: {
    query() {
      let periodDay = this.$store.getters.TIME_FOR_THE_DAY(this.date);
      return { drecTimestampKey: periodDay.start + "..." + periodDay.end };
    },
  },
};
</script>