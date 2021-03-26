<template>
  <crud-table single-expand show-expand v-on="$listeners" v-bind="$attrs">
    <template
      v-for="slotName in Object.keys($scopedSlots)"
      #[slotName]="slotScope"
    >
      <slot :name="slotName" v-bind="slotScope" />
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
  }
};
</script>
