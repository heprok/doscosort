<template>
  <BaseMaterialCard
    width="400"
    class="card-info-operator"
    v-if="!loading"
    :title="operator.fio"
  >
    <VSimpleTable>
      <tbody>
        <tr>
          <td><p class="font-weight-regular mb-0">Объём</p></td>
          <td>{{ volume }} м³</td>
        </tr>
        <tr>
          <td><p class="font-weight-regular mb-0">Кол-во</p></td>
          <td>{{ count }} шт.</td>
        </tr>
        <tr>
          <td><p class="font-weight-regular mb-0">Часов отработано</p></td>
          <td>{{ timeWork }} ч.</td>
        </tr>
        <tr>
          <td><p class="font-weight-regular mb-0">Кол-во смен</p></td>
          <td>{{ countShift }}</td>
        </tr>
        <tr>
          <td>
            <p class="font-weight-regular mb-0">
              Время простоя
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon color="primary" dark v-bind="attrs" v-on="on">
                    mdi-help
                  </v-icon>
                </template>
                <span>Время указано без технологических простоев</span>
              </v-tooltip>
            </p>
          </td>
          <td>{{ timeDowntime }}</td>
        </tr>
      </tbody>
    </VSimpleTable>
  </BaseMaterialCard>
  <LoaderTlc v-else />
</template>

<script>
import Axios from "axios";
export default {
  name: "OperatorInfoCard",
  data() {
    return {
      entryPointApi: this.$store.state.apiEntryPoint,
      volume: 0.0,
      count: 0,
      countShift: 0,
      timeDowntime: "00:00:00",
      timeWork: 0,
      precent: 0,
      loading: true,
    };
  },
  props: {
    duration: {
      type: Object,
      required: true,
    },
    operator: {
      type: Object,
      default: () => ({
        fio: "Кравчук О.В.",
        id: 0,
        name: "Олег",
        pat: "Кравчук",
        fam: "Владимирович",
      }),
      //   required: true
    },
  },
  methods: {
    async update() {
      this.loading = true;
      let request = null;
      const config = {
        params: this.duration,
      };
      try {
        request = await Axios.get(
          this.entryPointApi +
            "/peoples/" +
            this.operator.id +
            "/infoForDuration",
          config
        );
        let data = request.data;
        this.volume = data.volume;
        this.count = data.count;
        this.countShift = data.countShift;
        this.timeWork = data.timeWork;
        this.timeDowntime = data.timeDowntime;
      } catch (err) {
        console.log(err);
        let data = err.response.data;
        this.color = "error";
        this.value = data.value;
      } finally {
        this.loading = false;
        return request;
      }
    },
  },
  async beforeMount() {
    this.loading = true;
    await this.update();
    // this.operator.loading = false
    // setTimeout(() => { console.log("мир"); }, 3000);
    // this.operator.loading = true;
    this.loading = false;
  },
};
</script>

<style>
</style>