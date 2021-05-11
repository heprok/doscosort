<template>
  <v-container id="report_unload_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/unload" :filterSqlWhere="filters">
        </shift-date-picker>
      </v-col>
      <v-col cols="12">
        <v-card>
          <v-tabs
            v-model="tab"
            background-color="primary"
            centered
            icons-and-text
          >
            <v-tabs-slider></v-tabs-slider>

            <v-tab href="#tab-group">
              Группировка
              <v-icon>mdi-account-group</v-icon>
            </v-tab>

            <v-tab href="#tab-registry">
              Последние выгруженные карманы
              <v-icon>mdi-chevron-double-down</v-icon>
            </v-tab>
          </v-tabs>

          <v-tabs-items v-model="tab">
            <v-tab-item value="tab-group">
              <crud-table
                title="Выгруженные карманы за сегодняшний день"
                url-api="/unload/pocket/today"
                :query="query"
                show-expand
                single-expand
                item-key="id"
                icon="mdi-chevron-double-down"
                :headers="group.headers"
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
                        <tr
                          v-for="pocket in item.unloadsPocket"
                          :key="pocket.time"
                        >
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
            </v-tab-item>
            <v-tab-item value="tab-registry">
              <crud-table
                title="Выгруженные карманы за сегодняшний день"
                url-api="/unloads"
                :query="query"
                icon="mdi-chevron-double-down"
                :headers="registry.headers"
              />
            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  name: "report_unload_dashboard",

  data() {
    return {
      tab: null,
      filters: ["pocket"],
      group: {
        headers: [
          { text: "Качество", value: "qualities" },
          { text: "Сечение", value: "cut" },
          { text: "Кол-во выгруженный карманов", value: "unload_pocket" },
          { text: "Кол-во", value: "total_amount" },
          { text: "Объём", value: "total_volume" },
        ],
      },
      registry: {
        headers: [
          { text: "Время", value: "time" },
          { text: "№ кармана", value: "pocket" },
          { text: "Порода", value: "group.species.name" },
          { text: "Качество", value: "qualities" },
          { text: "Сечение", value: "group.cut" },
          { text: "Длина", value: "group.intervalLength" },
          { text: "Кол-во", value: "amount" },
          { text: "Объём", value: "volume" },
        ],
      },
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
