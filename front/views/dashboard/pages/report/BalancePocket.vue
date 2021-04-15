<template>
  <v-container id="report_balance_pocket_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker
          urlReport="report/unload/balancePocket"
          :filterSqlWhere="filters"
        >
        </shift-date-picker>
      </v-col>
      <v-col cols="12">
        <crud-table
          title="Текущее состоние карманов"
          url-api="/unload/balancepockets"
          disable-pagination
          hide-default-footer
          icon="mdi-cards-variant"
          :headers="headers"
        >
        </crud-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_balance_pocket_dashboard",

  data() {
    return {
      filters: [],
      headers: [
        { text: "№ кармана", value: "pocket" },
        { text: "Порода", value: "name_species" },
        { text: "Качество", value: "quality_1_name" },
        { text: "Сечение", value: "cut" },
        { text: "Длина", value: "length" },
        { text: "Кол-во", value: "count_boards" },
        { text: "Объём", value: "volume_boards" },
      ],
    };
  },
  mounted() {},
  methods: {},
};
</script>
