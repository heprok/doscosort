<template>
  <v-app-bar id="app-bar" absolute app color="transparent" flat height="75">
    <v-btn class="mr-3" elevation="1" fab small @click="setDrawer(!drawer)">
      <v-icon v-if="value"> mdi-view-quilt </v-icon>

      <v-icon v-else> mdi-dots-vertical </v-icon>
    </v-btn>

    <v-toolbar-title
      class="hidden-sm-and-down font-weight-light"
      v-text="$route.name"
    />
    <v-spacer />

    <summary-stats-card/> 
    <v-switch
      v-model="$vuetify.theme.dark"
      @change="switchTheme"
      class="ma-0 pa-0"
      color="secondary"
      hide-details
      append-icon="mdi-brightness-4"
    />
  </v-app-bar>
</template>

<script>
// Components
// import { VHover, VListItem } from "vuetify/lib";
import SummaryStatsCard from "../../../../components/SummaryStatsCard"
// Utilities
import { mapState, mapMutations } from "vuex";

export default {
  name: "DashboardCoreAppBar",

  components: {
    SummaryStatsCard,
    // AppBarItem: {
    //   render(h) {
    //     return h(VHover, {
    //       scopedSlots: {
    //         default: ({ hover }) => {
    //           return h(
    //             VListItem,
    //             {
    //               attrs: this.$attrs,
    //               class: {
    //                 "black--text": !hover,
    //                 "white--text secondary elevation-12": hover,
    //               },
    //               props: {
    //                 activeClass: "",
    //                 dark: hover,
    //                 link: true,
    //                 ...this.$attrs,
    //               },
    //             },
    //             this.$slots.default
    //           );
    //         },
    //       },
    //     });
    //   },
    // },
  },

  props: {
    value: {
      type: Boolean,
      default: false,
    },
  },
  data: () => ({
    // notifications: [
    //   "Mike John Responded to your email",
    //   "You have 5 new tasks",
    //   "You're now friends with Andrew",
    //   "Another Notification",
    //   "Another one",
    // ],
  }),

  computed: {
    ...mapState(["drawer"]),
  },
  mounted() {
    this.$vuetify.theme.dark = localStorage.getItem("dark_theme") === 'true';
  },
  methods: {
    switchTheme(){
      localStorage.setItem("dark_theme", this.$vuetify.theme.dark);
    },
    ...mapMutations({
      setDrawer: "SET_DRAWER",
    }),
  },
};
</script>
