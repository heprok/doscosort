<template>
  <v-container id="manual_people_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <crud-table
          title="Список людей"
          url-api="api/people"
          icon="mdi-account-group"
          :headers="headers"
          is-crud
        >
        </crud-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>



export default {
  name: "manual_people_dashboard",
  
  data() {
    return {
      headers: [
        { text: "Фамилия", value: "fam" },
        { text: "Имя", value: "nam" },
        { text: "Отчество", value: "pat" },
        { text: "Действия", value: 'actions', edited: false }
      ],
    };
  },
  mounted() {},
  methods: {},
};
</script>