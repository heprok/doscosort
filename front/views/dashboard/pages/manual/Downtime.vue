<template>
  <v-container id="manual_downtime_dashboard" fluid tag="section">
    <v-card>
      <v-tabs v-model="tab" background-color="primary" centered icons-and-text>
        <v-tabs-slider></v-tabs-slider>

        <v-tab href="#causes">
          Группы причин
          <v-icon>mdi-comment-question-outline</v-icon>
        </v-tab>

        <v-tab href="#location">
          Местоположения
          <v-icon>mdi-flag</v-icon>
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item value="location">
          <v-row>
            <v-col cols="6" v-if="place.loadingSelect">
              <crud-table
                title="Места простоя"
                url-api="/downtime_places"
                :itemsSelect="place.itemsSelect"
                icon="mdi-flag"
                :headers="place.headers"
                is-crud
                isCheckPass
              >
              </crud-table>
            </v-col>
            <v-col cols="6">
              <crud-table
                title="Локации простоя"
                url-api="/downtime_locations"
                icon="mdi-flag"
                :headers="location.headers"
                is-crud
                isCheckPass
              >
              </crud-table>
            </v-col>
          </v-row>
        </v-tab-item>
        <v-tab-item value="causes">
          <v-row>
            <v-col cols="6" v-if="cause.loadingSelect">
              <crud-table
                title="Причины простоя"
                url-api="/downtime_causes"
                :itemsSelect="cause.itemsSelect"
                icon="mdi-comment-question-outline"
                :headers="cause.headers"
                is-crud
                isCheckPass
              >
              </crud-table>
            </v-col>
            <v-col cols="6">
              <crud-table
                title="Группы причин"
                url-api="/downtime_groups"
                icon="mdi-comment-question-outline"
                :headers="group.headers"
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
  name: "manual_downtime_dashboard",

  data() {
    return {
      tab: null,
      cause: {
        headers: [
          { text: "Код", value: "code" },
          { text: "Имя", value: "name" },
          {
            text: "Группа",
            value: "groups.name",
            type: "select",
            itemSelect: "groups",
          },
          { text: "Действия", value: "actions", edited: false },
        ],
        loadingSelect: false,
        itemsSelect: {
          groups: null,
        },
      },
      place: {
        headers: [
          { text: "Код", value: "code" },
          { text: "Имя", value: "name" },
          {
            text: "Локация",
            value: "location.name",
            type: "select",
            itemSelect: "location",
          },
          { text: "Действия", value: "actions", edited: false },
        ],
        loadingSelect: false,
        itemsSelect: {
          location: null,
        },
      },
      location: {
        headers: [
          { text: "Код", value: "code" },
          { text: "Имя", value: "name" },
          { text: "Действия", value: "actions", edited: false },
        ],
      },
      group: {
        headers: [
          { text: "Код", value: "code" },
          { text: "Имя", value: "name" },
          { text: "Действия", value: "actions", edited: false },
        ],
      },
    };
  },
  watch: {},
  computed: {},
  async mounted() {
    await this.getItemsLocations();
    await this.getItemsGroups();
  },
  methods: {
    getItemsLocations() {
      this.place.itemsSelect.location = {};
      Axios.get(this.$store.state.apiEntryPoint + "/downtime_locations")
        .then((response) => {
          let data = response.data["hydra:member"];
          this.place.itemsSelect.location = data.map((location) => {
            return {
              value: location["@id"],
              text: location.name,
            };
          });
          this.place.loadingSelect = true;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке мест локаций");
          console.log(err);
        });
      return null;
    },
    getItemsGroups() {
      this.cause.itemsSelect.groups = {};
      Axios.get(this.$store.state.apiEntryPoint + "/downtime_groups")
        .then((response) => {
          let data = response.data["hydra:member"];
          console.log(data);
          this.cause.itemsSelect.groups = data.map((group) => {
            return {
              value: group["@id"],
              text: group.name,
            };
          });
          this.cause.loadingSelect = true;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке групп");
          console.log(err);
        });
      return null;
    },
  },
};
</script>