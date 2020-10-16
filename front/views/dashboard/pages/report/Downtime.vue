<template>
  <v-container id="report_downtimes_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12"> 
        <shift-date-picker
          urlReport="report/downtimes"
        >
        </shift-date-picker>
         </v-col>
    </v-row>
  </v-container>
</template>

<script>
import shiftDatePicker from "@/components/ShiftDatePicker.vue";

export default {
  name: "report_downtimes_dashboard",
  components: { shiftDatePicker },
  data() {
    return {
      headers: [
        { text: "Код", value: "id" },
        { text: "Название", value: "name" },
        { text: "Действия", value: "actions" },
      ],
    };
  },
  mounted() {},
  methods: {},
};
</script>