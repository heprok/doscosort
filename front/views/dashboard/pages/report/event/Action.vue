<template>
  <v-container id="report_event_action_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker :filterSqlWhere="filters" urlReport="report/event/action_operator">
        </shift-date-picker>
      </v-col>
      <v-col cols="12">
        <crud-table
          title="Действия оператора за сегодняшний день"
          url-api='/events'
          :query="query"
          icon="mdi-gesture-double-tap"
          :headers="headers"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_event_action_dashboard",
  data() {
    return {
      filters: ["event_type", "event_source"],
      headers: [
        { text: "Время", value: "startTime" },
        { text: "Действие", value: "text" },
      ],
    };
  },
  methods: {
  },
  computed: {
    query() {
      let periodDay = this.$store.getters.TIME_FOR_THE_DAY(this.date);

      return { drecTimestampKey: periodDay.start + "..." + periodDay.end, type: "a", source: "o" };
    },
  },
};
</script>