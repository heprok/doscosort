<template>
  <v-container id="report_unload_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/unload" :filterSqlWhere="filters">
        </shift-date-picker>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_unload_dashboard",

  data() {
    return {
      filters: ['pocket'],

    };
  },
  mounted() {},
  methods: {},
};
</script>