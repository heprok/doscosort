<template>
  <v-container id="manual_action_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <crud-table
          title="Действие опертора"
          url-api="/action_operators"
          icon="mdi-gesture-double-tap"
          :headers="headers"
          isCheckPass
          is-crud
        >
        </crud-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>



export default {
  name: "manual_action_dashboard",
  
  data() {
    return {
      headers: [
        { text: "Код", value: "code" },
        { text: "Название", value: "name" },
        { text: "Действия", value: "actions", edited: false },
      ],
    };
  },
  mounted() {},
  methods: {},
};
</script>