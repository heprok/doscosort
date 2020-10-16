<template>
  <v-container id="manual_error_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <crud-table
          title="Список ошибок"
          url-api="api/errors"
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

import crudTable from "@/components/CRUDTable.vue";

export default {
  name: "manual_error_dashboard",
  components: { crudTable },
  data() {
    return {
      headers: [
        { text: "Код", value: "id" },
        { text: "Текст", value: "text" },
        { text: "Действия", value: "actions" },
      ],
    };
  },
  mounted() {},
  methods: {},
};
</script>