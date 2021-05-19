<template>
  <v-navigation-drawer
    id="core-navigation-drawer"
    v-model="drawer"
    :dark="barColor !== 'rgba(228, 226, 226, 1), rgba(255, 255, 255, 0.7)'"
    :expand-on-hover="expandOnHover"
    :right="$vuetify.rtl"
    :src="barImage"
    mobile-breakpoint="960"
    app
    width="260"
    v-bind="$attrs"
  >
    <template v-slot:img="props">
      <v-img :gradient="`to bottom, ${barColor}`" v-bind="props" />
    </template>

    <v-divider class="mb-1" />

    <v-list dense nav>
      <v-list-item>
        <!-- <v-list-item-avatar class="align-self-center" color="white" contain> -->
        <v-img :src="logotype" max-height="100" max-width="200" />
        <!-- </v-list-item-avatar> -->
        <!-- 
        <v-list-item-content>
          <v-list-item-title class="display-2" v-text="profile.title" />
        </v-list-item-content> -->
      </v-list-item>
    </v-list>

    <v-divider class="mb-2" />

    <v-list expand nav>
      <!-- Style cascading bug  -->
      <!-- https://github.com/vuetifyjs/vuetify/pull/8574 -->
      <div />

      <template v-for="(item, i) in computedItems">
        <base-item-group v-if="item.children" :key="`group-${i}`" :item="item">
          <!--  -->
        </base-item-group>

        <base-item v-else :key="`item-${i}`" :item="item" />
      </template>

      <!-- Style cascading bug  -->
      <!-- https://github.com/vuetifyjs/vuetify/pull/8574 -->
      <div />
    </v-list>
    <!-- 
    <template v-slot:append>
      <base-item
        :item="{
          title: $t('Settings'),
          icon: 'mdi-setting',
          to: '/settings',
        }"
      />
    </template> -->
  </v-navigation-drawer>
</template>

<script>
// Utilities
import { mapState } from "vuex";

export default {
  name: "DashboardCoreDrawer",

  props: {
    expandOnHover: {
      type: Boolean,
      default: false,
    },
  },

  data: () => ({
    items: [
      {
        icon: "mdi-view-dashboard",
        title: "Панель",
        to: "/",
      },
      // {
      //   icon: "mdi-package-variant",
      //   title: "Пакеты",
      //   to: "/package",
      // },
      {
        group: "/report",
        title: "Отчёты",
        icon: "mdi-file-chart",
        children: [
          {
            icon: "mdi-color-helper",
            title: "Пиломатериалы",
            to: "board",
          },
          {
            icon: "mdi-book-open",
            title: "Хронология пил-мат",
            to: "boardregistry",
          },
          {
            icon: "mdi-chevron-double-down",
            title: "Выгруженные карманы",
            to: "unload",
          },
          {
            icon: "mdi-account-multiple",
            title: "Операторы",
            to: "operators",
          },
          {
            icon: "mdi-filter-remove-outline",
            title: "Отбойник",
            to: "lastpocket",
          },
          {
            icon: "mdi-clock-fast",
            title: "Простои",
            to: "downtimes",
          },
          {
            icon: "mdi-cards-variant",
            title: "Остатки в карманах",
            to: "balancepocket",
          },
          {
            icon: "mdi-comment-alert-outline",
            title: "Аварии и сообщения",
            to: "alert",
          },
          {
            icon: "mdi-gesture-double-tap",
            title: "Действия",
            to: "action",
          },
        ],
      },
      {
        group: "/manual",
        title: "Справочники",
        icon: "mdi-book",
        children: [
          {
            icon: "mdi-pine-tree",
            title: "Список пород",
            to: "species",
          },
          {
            icon: "mdi-account-group",
            title: "Люди",
            to: "people",
          },
          {
            icon: "mdi-timetable",
            title: "Рабочее расписание",
            to: "workschedule",
          },
          {
            icon: "mdi-camera-timer",
            title: "Простои",
            to: "downtime",
          },
          {
            icon: "mdi-comment-remove-outline",
            title: "Ошибки",
            to: "error",
          },
          {
            icon: "mdi-gesture-double-tap",
            title: "Действия оператора",
            to: "action",
          },
          {
            icon: "mdi-format-line-spacing",
            title: "Стандартные длины",
            to: "standartlength",
          },
        ],
      },
    ],
  }),

  computed: {
    ...mapState(["barColor", "barImage"]),
    drawer: {
      get() {
        return this.$store.state.drawer;
      },
      set(val) {
        this.$store.commit("SET_DRAWER", val);
      },
    },
    computedItems() {
      return this.items.map(this.mapItem);
    },
    logotype() {
      const nameLogotype = "logotype283x103";
      return this.$vuetify.theme.dark
        ? "build/images/" + nameLogotype + "_black.png"
        : "build/images/" + nameLogotype + "_white.png";
    },
    profile() {
      return {
        avatar: false,
        title: this.$t("Технолеском"),
        // title: this.$t("avatar"),
      };
    },
  },

  methods: {
    mapItem(item) {
      return {
        ...item,
        children: item.children ? item.children.map(this.mapItem) : undefined,
        title: this.$t(item.title),
      };
    },
  },
};
</script>

<style lang="sass">
@import '~vuetify/src/styles/tools/_rtl.sass'

#core-navigation-drawer
  .v-list-group__header.v-list-item--active:before
    opacity: .24

    .v-list-item
      &__icon--text,
      &__icon:first-child
        justify-content: center
        text-align: center
        width: 20px

        +ltr()
        margin-right: 24px
        margin-left: 12px !important

        +rtl()
        margin-left: 24px
        margin-right: 12px !important

    .v-list--dense
      .v-list-item
        &__icon--text,
        &__icon:first-child
          margin-top: 10px

    .v-list-group--sub-group
      .v-list-item
        +ltr()
        padding-left: 8px

        +rtl()
        padding-right: 8px

      .v-list-group__header
        +ltr()
        padding-right: 0

        +rtl()
        padding-right: 0

        .v-list-item__icon--text
          margin-top: 19px
          order: 0

        .v-list-group__header__prepend-icon
          order: 2

          +ltr()
          margin-right: 8px

          +rtl()
          margin-left: 8px
</style>
