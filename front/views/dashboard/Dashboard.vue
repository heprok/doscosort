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
        >
        <BarChartApex urlApi="/api/charts/qualtites/currentShiftApex" suffix="%" ref="chart" />
        </ChartCard>
      </v-col>
      <!-- <v-col cols="6" lg="6" sm="12">
        <ChartCard
          type="Bar"
          urlApi="/api/charts/volume/shifts/weekly"
          subtitle="За неделю"
          title="Выработка по операторам"
        />
      </v-col> -->
    </v-row>
    <!-- <v-row>
      <v-col cols="6" lg="6" sm="12">

          <base-material-chart-card
          color="#000"
          data="[2, 3, 4]"
          hover-reveal
          type="Line"
          url="api/dashboard/report/volumeboard/chart"
        >
          <template v-slot:reveal-actions>
            <v-tooltip bottom>
              <template v-slot:activator="{ attrs, on }">
                <v-btn v-bind="attrs" color="info" icon v-on="on">
                  <v-icon color="info">mdi-refresh</v-icon>
                </v-btn>
              </template>

              <span>Refresh</span>
            </v-tooltip>

            <v-tooltip bottom>
              <template v-slot:activator="{ attrs, on }">
                <v-btn v-bind="attrs" light icon v-on="on">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </template>

              <span>Change Date</span>
            </v-tooltip>
          </template>

          <h4 class="card-title font-weight-light mt-2 ml-2">Объем досок</h4>

          <p class="d-inline-flex font-weight-light ml-2 mt-1">За последние 30 дней</p>
          <template v-slot:actions>
            <v-icon
              class="mr-1"
              small
            >
              mdi-clock-outline
            </v-icon>
            <span class="caption grey--text font-weight-light">updated 10 minutes ago</span>
          </template>
        </base-material-chart-card>
      </v-col>

      <v-col cols="6" lg="6" sm="12">
        <base-material-chart-card
          color="#000"
          hover-reveal
          type="Bar"
          url="api/dashboard/report/diffboardontimber/chart"
        >
          <template v-slot:reveal-actions>
            <v-tooltip bottom>
              <template v-slot:activator="{ attrs, on }">
                <v-btn v-bind="attrs" color="info" icon v-on="on">
                  <v-icon color="info">mdi-refresh</v-icon>
                </v-btn>
              </template>

              <span>Refresh</span>
            </v-tooltip>

            <v-tooltip bottom>
              <template v-slot:activator="{ attrs, on }">
                <v-btn v-bind="attrs" light icon v-on="on">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </template>

              <span>Change Date</span>
            </v-tooltip>
          </template>

          <h4
            class="card-title font-weight-light mt-2 ml-2"
          >Соотношение между выпеленных досок с сравнением с бревном</h4>
        </base-material-chart-card>
      </v-col>
    </v-row> -->
  </v-container>
</template>

<script>
// import LineChart from '../../components/base/chart/report/dashboard/VolumeBoardChart.js';
// import LineChartCard from '../../components/base/ChartJsCard.vue';
import ChartCard from "../../components/charts/ChartCard";
import BarChartApex from "../../components/charts/BarChartApex.vue"
export default {
  name: "DashboardDashboard",
  components: { ChartCard, BarChartApex },
  data() {
    return {
      loader: false,
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
