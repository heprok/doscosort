<template>
  <v-container id="report_event_alert_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker
          :filterSqlWhere="filters"
          urlReport="report/event/alert"
        >
        </shift-date-picker>
      </v-col>
      <v-col cols="12">
        <crud-table
          title="Аварии и сообщения сегодняшний день"
          url-api="/events"
          :query="query"
          icon="mdi-comment-alert-outline"
          :headers="headers"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_event_alert_dashboard",
  data() {
    return {
      filters: ["event_type", "event_source"],
      headers: [
        { text: "Время", value: "startTime" },
        { text: "Сообщение", value: "text" },
        { text: "Источник", value: "source.name" },
        { text: "Тип", value: "type.name" },
      ],
    };
  },
  methods: {},

  computed: {
    query() {
      let periodDay = this.$store.getters.TIME_FOR_THE_DAY(this.date);
      return {
        drecTimestampKey: periodDay.start + "..." + periodDay.end,
        type: ["e", "m"],
        source: ["p", "s", "o"],
      };
    },
  },
};
</script>