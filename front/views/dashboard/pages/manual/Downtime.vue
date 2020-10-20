<template>
  <v-container id="manual_downtime_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="6">
        <crud-table
          title="Места простоя"
          url-api="api/downtime_causes"
          icon="mdi-flag"
          :headers="place.headers"
          is-crud
        >
        </crud-table>
      </v-col>
      <v-col cols="6">
        <crud-table
          title="Причины простоя"
          url-api="api/downtime_places"
          icon="mdi-comment-question-outline"
          :headers="cause.headers"
          is-crud
        >
        </crud-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>



export default {
  name: "manual_downtime_dashboard",
  
  data() {
    return {
      cause: {
        headers: [
          { text: "Имя", value: "name" },
          { text: "Действия", value: "actions", edited: false },
        ],
      },
      place: {
        headers: [
          { text: "Имя", value: "name" },
          { text: "Действия", value: "actions", edited: false },
        ],
      },
    };
  },
  mounted() {},
  methods: {},
};
</script>