<template>
  <v-container id="report_unload_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/unload" :filterSqlWhere="filters">
        </shift-date-picker>
      </v-col>
      <v-col cols="12">
        <crud-table
          title="Выгруженные карманы за сегодняшний день"
          url-api="/unload/pocket/today"
          :query="query"
          show-expand
          single-expand
          item-key="id"
          icon="mdi-chevron-double-down"
          :headers="headers"
        >
          <template v-slot:expanded-item="{ headers, item }">
            <td :colspan="headers.length">
              <v-simple-table>
                <thead>
                  <tr>
                    <th>Время</th>
                    <th>№ кармана</th>
                    <th>Порода</th>
                    <th>Качество</th>
                    <th>Сечение</th>
                    <th>Длина</th>
                    <th>Кол-во</th>
                    <th>Объём</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="pocket in item.unloadsPocket" :key="pocket.time">
                    <td>{{ pocket.time }}</td>
                    <td>{{ pocket.pocket }}</td>
                    <td>{{ pocket.group.species.name }}</td>
                    <td>{{ pocket.qualities }}</td>
                    <td>{{ pocket.group.cut }}</td>
                    <td>{{ pocket.group.intervalLength }}</td>
                    <td>{{ pocket.amount }}</td>
                    <td>{{ pocket.volume }}</td>
                  </tr>
                </tbody>
              </v-simple-table>
            </td>
          </template>
        </crud-table>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  name: "report_unload_dashboard",

  data() {
    return {
      filters: ["pocket"],
      headers: [
        { text: "Качество", value: "qualities" },
        { text: "Сечение", value: "cut" },
        { text: "Кол-во выгруженный карманов", value: "unload_pocket" },
        { text: "Кол-во", value: "total_amount" },
        { text: "Объём", value: "total_volume" },
      ],
    };
  },
  mounted() {},
  methods: {},
  computed: {
    query() {
      let periodDay = this.$store.getters.TIME_FOR_THE_DAY(this.date);
      return { drecTimestampKey: periodDay.start + "..." + periodDay.end };
    },
  },
};
</script>
