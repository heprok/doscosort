<template>
  <v-container id="report_package_dashboard" fluid tag="section">
    <v-row>
      <v-col
        cols="12"
        sm="6"
        lg="4"
        v-for="infoCard in infoCards"
        :key="infoCard.nameCard"
      >
        <info-card
          :color="infoCard.color"
          :icon="infoCard.icon"
          :sub-icon="infoCard.subIcon"
          :title="infoCard.nameCard"
          :urlApi="infoCard.urlApi"
          :durations="infoCard.duration"
        />
      </v-col>
      <v-col cols="12">
        <crud-table-package
          title="Сформированные пакеты"
          url-api="/packages"
          item-key="drecTime"
          :expanded.sync="expanded"
          icon="mdi-package-variant-closed"
          :headers="headers"
          :item-class="rowClasses"
        >
          <template #[`item.dry`]="{ item }">
            <v-simple-checkbox v-model="item.dry" disabled />
          </template>

          <template v-slot:expanded-item="{ headers, item }">
            <td :colspan="4">
              <v-simple-table dense>
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-left">
                        Длина мм.
                      </th>
                      <th class="text-left">
                        Количество шт.
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(board, index) in item.boardsArray" :key="index">
                      <td>{{ board.length }}</td>
                      <td>{{ board.amount }}</td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </td>

            <td :colspan="3">
              <v-timeline dense>
                <v-timeline-item
                  small
                  v-for="(move, index) in item.packageMoves"
                  :key="index"
                >
                  <div class="py-4">
                    <h2
                      :class="`headline font-weight-light mb-4 primary--text`"
                    >
                      {{ move.dst.name }}
                      <span
                        :class="`headline font-weight-bold secondary--text`"
                        v-text="move.drecTime"
                      ></span>
                    </h2>
                    <div>
                      {{ move.comment }}
                    </div>
                  </div>
                </v-timeline-item>
              </v-timeline>
            </td>
          </template>
        </crud-table-package>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import CRUDTablePackage from "../../../../components/global/CRUDTablePackage.vue";
export default {
  components: { CRUDTablePackage },
  name: "report_package_dashboard",

  data() {
    return {
      expanded: [],
      infoCards: [],
      headers: [
        { text: "Время", value: "drecTime", edited: false },
        { text: "Порода", value: "species.name" },
        { text: "Сечение", value: "cut" },
        { text: "Качество", value: "qualities" },
        { text: "Дипазон длин", value: "rangeLengths" },
        { text: "Кол-во", value: "count", edited: false },
        { text: "Объём", value: "volume", edited: false },
        { text: "Сухая", value: "dry", type: "bool", edited: false },
        { text: "Действия", value: "printers", edited: false },
      ],
    };
  },
  mounted() {},
  methods: {
    rowClasses(item) {
      if (!item.volume) return "orange";
    },
    expandItem(item) {
      if (this.expanded.indexOf(item) === -1) {
        this.expanded = [];
        this.expanded.push(item);
      } else this.expanded = [];
    },
  },
};
</script>
<style>
.orange {
  background-color: orange;
}
</style>
