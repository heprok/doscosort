<template>
  <v-container id="manual_species_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <crud-table
          title="Список пород"
          url-api="api/species"
          icon="mdi-pine-tree"
          :headers="headers"
        >
        </crud-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>



export default {
  name: "manual_species_dashboard",
  
  data() {
    return {
      headers: [
        {
          text: "id",
          align: "start",
          filterable: false,
          value: "id",
        },
        { text: "Название", value: "name" },
        { text: "Хвойное", value: "fir" },
        { text: "Включен", value: "enabled" },
      ],
    };
  },
  mounted() {},
  methods: {},
};
</script>