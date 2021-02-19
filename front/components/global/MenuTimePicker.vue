<template>
  <v-menu
    ref="menu"
    v-model="menu"
    :close-on-content-click="false"
    :nudge-right="40"
    :return-value.sync="time"
    transition="scale-transition"
    offset-y
    max-width="290px"
    min-width="290px"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
        v-model="time"
        prepend-icon="mdi-clo ck-time-four-outline"
        readonly
        v-bind="attrs"
        v-on="on"
      ></v-text-field>
    </template>
    <v-time-picker
      v-if="menu"
      v-model="time"
      full-width
      format="24hr"
      use-seconds
      @click:second="$refs.menu.save(time); $emit('input', time)"
    ></v-time-picker>
  </v-menu>
</template>

<script>
export default {
  name: "MenuTimePicker",
  data() {
    return  {
      menu: null,
      time: this.value,
      // value: ""
    }
  },
  props: {
    value: {
      required: true
    }
  },
};
</script>

<style>
</style>