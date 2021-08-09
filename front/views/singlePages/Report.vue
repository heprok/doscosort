<template>
  <v-app id="inspire">
    <vue-snotify></vue-snotify>

    <v-main class="grey lighten-3">
      <v-container class="full-height" fluid>
        <v-data-table
          v-if="loading"
          :headers="headers"
          :items="items"
          class="elevation-1"
          show-group-by
        ></v-data-table>
        <div v-else class="preloader">
          <LoaderTlc />
        </div>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import Axios from "axios";
export default {
  components: {},
  data() {
    return {
      headers: [],
      items: [],
      loading: false,
    };
  },
  computed: {},
  async beforeMount() {
    const {data} = await Axios.get(
      "report/board_registry/2021-06-12T08:08:03+08:00...2021-07-26T19:04:14+08:00/people/4/api"
    );
    this.headers = data.columns.map((column, index) => {
      return {
        text: column.title,
        value: index.toString(),
        groupable: column.group ?? false,
      };
    });
    
    this.items = data.data.map((row) => {
      let item = {};
      row.forEach((value, index) => {
        item[index.toString()] = value;
      });
      return item;
    });
    console.log(this.headers, this.items)
    this.loading = true;
  },
  methods: {},
};
</script>
