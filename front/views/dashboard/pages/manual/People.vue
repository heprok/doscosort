<template>
  <v-container id="manual_people_dashboard" fluid tag="section">
    <v-card>
      <v-tabs v-model="tab" background-color="primary" centered icons-and-text>
        <v-tabs-slider></v-tabs-slider>

        <v-tab href="#people">
          Персонал
          <v-icon>mdi-account-group</v-icon>
        </v-tab>

        <v-tab href="#duties">
          Должности
          <v-icon>mdi-account-box</v-icon>
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item value="people">
          <v-row>
            <v-col cols="12" v-if="people.loadingSelect">
              <crud-table
                title="Люди"
                url-api="/people"
                :itemsSelect="people.itemsSelect"
                icon="mdi-flag"
                :headers="people.headers"
                is-crud
                isCheckPass
              >
                <template #[`item.duty.name`]="{ item }">
                  {{ item.duty.map((duty) => duty.name).join(", ") }}
                </template>
              </crud-table>
            </v-col>
          </v-row>
        </v-tab-item>
        <v-tab-item value="duties">
          <v-row>
            <v-col cols="12">
              <crud-table
                title="Должности"
                url-api="/duties"
                icon="mdi-comment-question-outline"
                :headers="duties.headers"
                is-crud
                isCheckPass
              >
              </crud-table>
            </v-col>
          </v-row>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
    <v-row> </v-row>
  </v-container>
</template>

<script>
import Axios from "axios";

export default {
  name: "manual_people_dashboard",

  data() {
    return {
      tab: null,
      people: {
        headers: [
          { text: "Фамилия", value: "fam" },
          { text: "Имя", value: "nam" },
          { text: "Отчество", value: "pat" },
          {
            text: "Должности",
            value: "duty.name",
            type: "select",
            multiple: true,
            itemSelect: "duty",
          },
          { text: "Действия", value: "actions", edited: false },
        ],
        loadingSelect: false,
        itemsSelect: {
          duty: null,
        },
      },
      duties: {
        headers: [
          { text: "Код", value: "code" },
          { text: "Название", value: "name" },
          { text: "Действия", value: "actions", edited: false },
        ],
      },
    };
  },
  watch: {},
  computed: {},
  async mounted() {
    await this.getItemsDuties();
  },
  methods: {
    getItemsDuties() {
      this.people.itemsSelect.duty = {};
      Axios.get(this.$store.state.apiEntryPoint + "/duties")
        .then((response) => {
          let data = response.data["hydra:member"];
          this.people.itemsSelect.duty = data.map((duty) => {
            return {
              value: duty["@id"],
              text: duty.name,
            };
          });
          console.log(this.people.itemsSelect.duty);
          this.people.loadingSelect = true;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке должностей");
          console.log(err);
        });
      return null;
    },
  },
};
</script>