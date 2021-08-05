<template>
  <v-container id="dashboard" fluid tag="section">
    <v-row>
      <v-col
        cols="12"
        sm="6"
        lg="3"
        v-for="infoCard in infoCards"
        :key="infoCard.nameCard"
      >
        <InfoCard
          :color="infoCard.color"
          :icon="infoCard.icon"
          :sub-icon="infoCard.subIcon"
          :title="infoCard.nameCard"
          :subText="infoCard.subTitle"
          :urlApi="infoCard.urlApi"
          :durations="infoCard.duration"
        />
      </v-col>
      <v-col cols="6" lg="6" sm="12">
        <ChartCard
          title="Распределение качеств"
          @update-query="(query) => queryChartQualities = query"
          subtitle="Текущая смена"
          ref="chartCardQualitites"
        >
          <BarChart
            urlApi="/api/charts/qualtites/currentShift"
            @toggle-loaded="$refs.chartCardQualitites.toggleLoaded()"
            @open-menu-period="$refs.chartChartQualitites.openMenuPeriod()"
            @update-chart="$refs.chartCardQualitites.refreshUpdate()"
            :query="queryChartQualities"
            :minuteUpdate="5"
            showDialogPeriod
            horizontal
            suffix="%"
          />
        </ChartCard>
      </v-col>
      <v-col cols="6" lg="6" sm="12">
        <ChartCard
          @update-query="(query) => queryChartVolume = query"
          subtitle="За неделю"
          title="Выработка по операторам"
          ref="chartCardVolume"
        >
          <ColumnChart
            urlApi="/api/charts/volume/shifts"
            showDialogPeriod
            ref="chartVolume"
            showDialogPeople
            :query="queryChartVolume"
            :minuteUpdate="5"
            @toggle-loaded="$refs.chartCardVolume.toggleLoaded()"
            @open-menu-period="$refs.chartCardVolume.openMenuPeriod()"
            @open-menu-people="$refs.chartCardVolume.openMenuPeople()"
            @update-chart="$refs.chartCardVolume.refreshUpdate()"
          />
        </ChartCard>
      </v-col>
      <v-col cols="6" lg="6" sm="12">
        <ChartCard
          @update-query="(query) => queryAreaChartVolume = query"
          subtitle="За неделю"
          title="Выработка по операторам"
          ref="areaChartCardVolume"
        >
          <AreaChart
            urlApi="/api/charts/area/volume"
            showDialogPeriod
            ref="areaChartVolume"
            showDialogPeople
            :query="queryAreaChartVolume"
            :minuteUpdate="1"
            @toggle-loaded="$refs.areaChartCardVolume.toggleLoaded()"
            @open-menu-period="$refs.areaChartCardVolume.openMenuPeriod()"
            @open-menu-people="$refs.areaChartCardVolume.openMenuPeople()"
            @update-chart="$refs.areaChartCardVolume.refreshUpdate()"
          />
        </ChartCard>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import ChartCard from "../../components/charts/ChartCard";
import BarChart from "../../components/charts/BarChart.vue";
import ColumnChart from "../../components/charts/ColumnChart.vue";
import AreaChart from "../../components/charts/AreaChart.vue";
export default {
  name: "DashboardDashboard",
  components: { ChartCard, ColumnChart, BarChart, AreaChart },
  data() {
    return {
      loader: false,
      queryChartQualities: {},
      queryChartVolume: {},
      queryAreaChartVolume: {},
      infoCards: [
        {
          nameCard: "Кол-во пил-мат",
          color: "info",
          icon: "mdi-poll",
          urlApi: "/infocard/countBoard",
          duration: [
            {
              url: "/currentShift",
              title: "за смену",
            },
            {
              url: "/today",
              title: "за сутки",
            },
            {
              url: "/weekly",
              title: "за неделю",
            },
            {
              url: "/mountly",
              title: "за месяц",
            },
          ],
        },
        {
          nameCard: "Объем пил-мат",
          color: "info",
          icon: "mdi-poll",
          urlApi: "/infocard/volumeBoards",
          duration: [
            {
              url: "/currentShift",
              title: "за смену",
            },
            {
              url: "/today",
              title: "за сутки",
            },
            {
              url: "/weekly",
              title: "за неделю",
            },
            {
              url: "/mountly",
              title: "за месяц",
            },
          ],
        },
        {
          nameCard: "Текущая смена",
          color: "info",
          subIcon: "mdi-tag",
          icon: "mdi-account-hard-hat",
          urlApi: "/infocard/currentShift",
        },
        {
          nameCard: "Последний простой",
          color: "orange",
          icon: "mdi-clock-fast",
          subIcon: "mdi-camera-timer",
          urlApi: "/infocard/lastDowntime",
        },
        {
          nameCard: "Кол-во пил-мат ",
          subTitle: "ПФМ: Выгруженно пиломатериалов",
          color: "info",
          icon: "mdi-gradient",
          subIcon: "mdi-tag",
          urlApi: "/infocard/pfm/countBoard",
          duration: [
            {
              url: "/currentShift",
              title: "за смену",
            },
            {
              url: "/today",
              title: "за сутки",
            },
            {
              url: "/weekly",
              title: "за неделю",
            },
            {
              url: "/mountly",
              title: "за месяц",
            },
          ],
        },
        {
          nameCard: "Объём пил-мат ",
          subTitle: "ПФМ: Выгруженный объём пиломатериалов",
          color: "info",
          icon: "mdi-gradient",
          subIcon: "mdi-tag",
          urlApi: "/infocard/pfm/volumeBoard",
          duration: [
            {
              url: "/currentShift",
              title: "за смену",
            },
            {
              url: "/today",
              title: "за сутки",
            },
            {
              url: "/weekly",
              title: "за неделю",
            },
            {
              url: "/mountly",
              title: "за месяц",
            },
          ],
        },
        {
          nameCard: "Кол-во карманов",
          subTitle: "ПФМ: Выгруженно карманов",
          color: "info",
          icon: "mdi-gradient",
          subIcon: "mdi-tag",
          urlApi: "/infocard/pfm/countUnloadPocket",
          duration: [
            {
              url: "/currentShift",
              title: "за смену",
            },
            {
              url: "/today",
              title: "за сутки",
            },
            {
              url: "/weekly",
              title: "за неделю",
            },
            {
              url: "/mountly",
              title: "за месяц",
            },
          ],
        },
        {
          nameCard: "Скорость сортировки",
          color: "info",
          icon: "mdi-speedometer",
          urlApi: "/infocard/vars/PresetSpeed",
        },
      ],
    };
  },
  mounted() {},
  methods: {
    complete(index) {
      this.list[index] = !this.list[index];
    },
  },
};
</script>
