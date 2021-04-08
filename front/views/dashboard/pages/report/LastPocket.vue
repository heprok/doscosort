<template>
  <v-container id="report_last_pocket_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/unload/lastPocket" :filterSqlWhere="filters">
        </shift-date-picker>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_last_pocket_dashboard",

  data() {
    return {
      filters: [/* 'thickness', 'width', 'species', 'length' */ ],

    };
  },
  mounted() {},
  methods: {},
};
</script>