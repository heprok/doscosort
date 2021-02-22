<template>
  <v-container id="report_downtimes_dashboard" fluid tag="section">
    <v-row>
      <v-col
        cols="12"
        sm="6"
        lg="4"
        v-for="infoCard in infoCards"
        :key="infoCard.nameCard"
      >
        <info-card
          :color="infoCard.color"
          :icon="infoCard.icon"
          :sub-icon="infoCard.subIcon"
          :title="infoCard.nameCard"
          :urlApi="infoCard.urlApi"
          :durations="infoCard.duration"
        />
      </v-col>
      <v-col cols="12">
        <shift-date-picker
          urlReport="report/downtimes"
          :filterSqlWhere="filters"
        >
        </shift-date-picker>
      </v-col>
      <v-col cols="12">
        <crud-table
          title="Простои за сегодняшний день"
          url-api="/downtimes"
          :query="query"
          icon="mdi-camera-timer"
          :headers="headers"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_downtimes_dashboard",
  data() {
    return {
      filters: ["downtime_cause", "downtime_place"],
      infoCards: [
        {
          urlApi: "/infocard/lastDowntime",
          color: "orange",
          icon: "mdi-sofa",
          nameCard: "Последний простой",
          subIcon: "mdi-clock",
        },
        {
          nameCard: "Сумарный простой за сегодня",
          color: "orange",
          icon: "mdi-camera-timer",
          urlApi: "/infocard/totalDowntime",
          subIcon: "mdi-clock",
          duration: [
            {
              url: "/currentShift",
              title: "за смену",
            },
            {
              url: "/today",
              title: "за сутки",
            },
            {
              url: "/weekly",
              title: "за неделю",
            },
            {
              url: "/mountly",
              title: "за месяц",
            },
          ],
        },
      ],
      headers: [
        { text: "Причина", value: "cause.name" },
        { text: "Место", value: "place.name" },
        { text: "Начало", value: "startTime" },
        { text: "Конец", value: "endTime" },
        { text: "Продолжительность", value: "durationTime" },
      ],
    };
  },
  computed: {
    query() {
      let periodDay = this.$store.getters.TIME_FOR_THE_DAY(this.date);
      return { drecTimestampKey: periodDay.start + "..." + periodDay.end };
    },
  },
  methods: {},
};
</script>