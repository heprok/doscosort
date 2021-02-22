<template>
  <v-container id="manual_error_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <crud-table
          title="Список ошибок"
          url-api="/errors"
          icon="mdi-account-group"
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
  name: "manual_error_dashboard",
  
  data() {
    return {
      headers: [
        { text: "Код", value: "id" , edited: false},
        { text: "Текст", value: "text" },
        { text: "Действия", value: "actions", edited: false },
      ],
    };
  },
  mounted() {},
  methods: {},
};
</script>