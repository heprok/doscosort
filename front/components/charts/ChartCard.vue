<template>
  <base-material-card
    class="v-card--material-chart"
    v-bind="$attrs"
    :color="$vuetify.theme.dark ? 'black' : 'white'"
    v-on="$listeners"
  >
    <template v-slot:heading>
      <slot />
    </template>

    <slot slot="reveal-actions" name="reveal-actions" />
    <h4 class="card-title font-weight-light mt-2 ml-2">
      {{ title }}
    </h4>

    <p class="d-inline-flex font-weight-light ml-2 mt-1">
      {{ subtitle }}
    </p>
    <template v-slot:actions>
      <v-icon class="mr-1" small> mdi-clock-outline </v-icon>
      <span class="caption grey--text font-weight-light"
        >обновлено {{ lastUpdateTime }} минут назад</span
      >
    </template>
  </base-material-card>
  <!-- <VSheet v-else>
    <LoaderTlc />
  </VSheet> -->
</template>

<script>
export default {
  name: "QualitiesBarChartCard",
  data: () => ({
    lastUpdateTime: -1,
  }),
  computed: {},
  props: {
    // urlApi: {
    //   type: String,
    //   required: true,
    // },
    title: {
      type: String,
      default: "",
    },
    subtitle: {
      type: String,
      default: "",
    },
  },
  methods: {
  },
};
</script>
<style lang="sass">
.v-card--material-chart
  position: relative
  p
    color: #95a5a6

  .v-card--material__heading
    max-height: 1200px
</style>