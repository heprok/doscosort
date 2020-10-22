<template>
  <v-container id="report_downtimes_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/downtimes"> </shift-date-picker>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_downtimes_dashboard",

  data() {
    return {};
  },
  mounted() {},
  methods: {},
};
</script>