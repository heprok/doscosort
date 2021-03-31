<template>
  <crud-table single-expand show-expand v-on="$listeners" v-bind="$attrs">
    <template
      v-for="slotName in Object.keys($scopedSlots)"
      #[slotName]="slotScope"
    >
      <slot :name="slotName" v-bind="slotScope" />
    </template>
    <template #[`item.printers`]="{ item }">
      <v-icon small class="mr-2" @click="editItemAction(item)">
        mdi-pencil
      </v-icon>
      <v-icon small @click="printerItemAction(item)"> mdi-printer </v-icon>
    </template>
  </crud-table>
</template>

<script>
import CRUDTable from "./CRUDTable.vue";
export default {
  components: { CRUDTable },
  name: "CRUDTablePackage",
  data() {
    return {
      entryPointApi: this.$store.state.apiEntryPoint,
    };
  },
  methods: {
    editItemAction(item) {
      console.log(item);
    },
    printerItemAction(item) {
      console.log(item);
    },
  },
};
</script>
