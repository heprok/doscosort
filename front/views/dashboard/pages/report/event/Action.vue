<template>
  <v-container id="report_event_action_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/event/action_operator">
        </shift-date-picker>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_event_action_dashboard",

  data() {
    return {};
  },
  mounted() {},
  methods: {},
};
</script>