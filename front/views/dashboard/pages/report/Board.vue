<template>
  <v-container id="report_board_dashboard" fluid tag="section">
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
        <shift-date-picker urlReport="report/board" :filterSqlWhere="filters">
        </shift-date-picker>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_board_dashboard",

  data() {
    return {
      filters: ["thickness", "width", "species", "length", "pocket"],
      headers: [
        { text: "Причина", value: "cause.name" },
        { text: "Место", value: "place.name" },
        { text: "Начало", value: "startTime" },
        { text: "Конец", value: "endTime" },
        { text: "Продолжительность", value: "durationTime" },
      ],
    };
  },
  mounted() {},
  methods: {},
};
</script>
