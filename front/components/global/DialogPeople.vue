<template>
  <v-dialog v-model="value" persistent max-width="800px">
    <v-card>
      <v-card-title>Выбранный персонал</v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-data-table
          v-model="people.selected"
          :headers="people.headers"
          :items="people.list"
          show-select
          v-bind="$attrs"
          @click:row="clickRow"
          :items-per-page="5"
          class="elevation-1"
        />
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn color="primary darken-1" block @click="saveDialogPeople">
          Применить
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "DialogPeople",
  data: () => ({
    people: {
      selected: [],
      list: [],
      headers: [
        {
          text: "Имя",
          value: "nam",
        },
        {
          text: "Фамилия",
          value: "fam",
        },
        {
          text: "Отчество",
          value: "pat",
        },
      ],
    },
  }),
  props: {
    selected: {
      type: Array,
      required: true,
    },
    value: {
      type: Boolean,
      required: true,
    },
  },
  async mounted() {
    const { data } = await this.$axios.get("/api/people");
    this.people.list = data["hydra:member"];
    this.people.selected = this.selected;
  },
  methods: {
    async saveDialogPeople() {
      if (this.people.selected.length == 0) {
        this.$snotify.error("Человек не выбран!");
        return;
      }
      this.$emit("change", this.people.selected);
    },
    clickRow(item) {
      const isSingleItem = this.$attrs.hasOwnProperty("single-select");
      if (isSingleItem) {
        this.people.selected = [item];
        return;
      }

      let findItem = this.people.selected.find((selectedItem) => {
        return selectedItem.id === item.id;
      });

      this.people.selected.indexOf(findItem ?? item) == -1
        ? this.people.selected.push(item)
        : this.people.selected.splice(
            this.people.selected.indexOf(findItem),
            1
          );
    },
  },
};
</script>

<style>
</style>