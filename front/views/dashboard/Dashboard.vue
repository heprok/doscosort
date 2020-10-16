<template>
  <v-container id="dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12" sm="6" lg="3">
        <base-material-stats-card
          color="info"
          icon="mdi-twitter"
          title="Followers"
          value="+245"
          sub-icon="mdi-clock"
          sub-text="Just Updated"
        />
      </v-col>

      <v-col cols="12" sm="6" lg="3">
        <base-material-stats-card
          color="primary"
          icon="mdi-poll"
          title="Website Visits"
          value="75.521"
          sub-icon="mdi-tag"
          sub-text="Tracked from Google Analytics"
        />
      </v-col>

      <v-col cols="12" sm="6" lg="3">
        <base-material-stats-card
          color="success"
          icon="mdi-store"
          title="Revenue"
          value="$ 34,245"
          sub-icon="mdi-calendar"
          sub-text="Last 24 Hours"
        />
      </v-col>

      <v-col cols="12" sm="6" lg="3">
        <base-material-stats-card
          color="orange"
          icon="mdi-sofa"
          title="Bookings"
          value="184"
          sub-icon="mdi-alert"
          sub-icon-color="red"
          sub-text="Get More Space..."
        />
      </v-col>
      <v-col cols="6" lg="6" sm="12">

        <base-material-chart-card
          color="#000"
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
          </template>-->
        </base-material-chart-card>
      </v-col>

      <v-col cols="6" lg="6" sm="12">
        <!-- <base-material-chart-card
          :data="diffboardontimber.data"
          :options="diffboardontimber.options"
          color="#000"
          hover-reveal
          type="Bar"
          url="api/dashboard/report/diffboardontimber/chart"
        >-->
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
          <!-- 
          <p class="d-inline-flex font-weight-light ml-2 mt-1">
            <v-icon color="green" small>mdi-arrow-up</v-icon>
            <span class="green--text">55%</span>&nbsp;
            За последние 30 дней
          </p>-->

          <!-- <template v-slot:actions>
            <v-icon class="mr-1" small>mdi-clock-outline</v-icon>
            <span class="caption grey--text font-weight-light">updated 4 minutes ago</span>
          </template>-->
        </base-material-chart-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>

// import LineChart from '../../components/base/chart/report/dashboard/VolumeBoardChart.js';
// import LineChartCard from '../../components/base/ChartJsCard.vue';
export default {
  name: "DashboardDashboard",
  data() {
    return {
      loader: false,
    };
  },
  mounted() {
  },
  methods: {
    complete(index) {
      this.list[index] = !this.list[index];
    },
  },
};
</script>