<template>
  <v-container id="report_registry_board_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/board_registry" :filterSqlWhere="filters">
        </shift-date-picker>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_registry_board_dashboard",

  data() {
    return {
      filters: ['thickness', 'width', 'species', 'length', 'pocket' ],

    };
  },
  mounted() {},
  methods: {},
};
</script>