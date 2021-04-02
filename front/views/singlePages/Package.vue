<template>
  <v-app id="inspire">
    <vue-snotify></vue-snotify>

    <v-main class="grey lighten-3">
      <v-container class="full-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8">
            <v-row>
              <v-col cols="12">
                <base-material-card>
                  <template v-slot:heading>
                    <div class="text-h4 font-weight-light">
                      Пакет № {{ pack.id }}
                    </div>
                    <div class="text-subtitle-1 font-weight-light">
                      Сформирован {{ pack.drecTime }}
                    </div>
                    <v-spacer />
                  </template>
                  <template v-slot:after-heading>
                    <v-dialog v-model="dialogQr" width="600">
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on">Сканировать QR</v-btn>
                      </template>

                      <base-material-card>
                        <template v-slot:heading>
                          <div class="text-h4 font-weight-light">
                            Сканирование QR code
                          </div>
                        </template>

                        <v-card-text>
                          <qrcode-stream @decode="onDecode" @init="onInit" />
                        </v-card-text>
                      </base-material-card>
                    </v-dialog>
                  </template>
                  <v-card-text>
                    <v-form
                      v-if="loadingPackage"
                      ref="form"
                      v-model="valid"
                      lazy-validation
                    >
                      <v-row>
                        <v-col md="6" sm="12">
                          <v-autocomplete
                            v-model="pack.species"
                            item-text="name"
                            item-value="@id"
                            outlined
                            :items="species"
                            :disabled="isSavePackage"
                            required
                            :rules="[(v) => !!v || 'Это поле обезательно']"
                            label="Порода"
                          />
                          <v-autocomplete
                            v-model="pack.qualities"
                            outlined
                            item-text="name"
                            item-value="name"
                            :items="qualities"
                            :disabled="isSavePackage"
                            required
                            :rules="[(v) => !!v || 'Это поле обезательно']"
                            label="Качество"
                          />
                          <v-text-field
                            v-model="pack.width"
                            label="Ширина"
                            :disabled="isSavePackage"
                            required
                            :rules="[
                              (v) => !!v || 'Это поле обезательно',
                              (v) => checkInteger(v) || 'Должно быть число',
                            ]"
                            outlined
                            suffix="мм"
                          />
                          <v-text-field
                            v-model="pack.thickness"
                            :disabled="isSavePackage"
                            label="Толщина"
                            required
                            :rules="[
                              (v) => !!v || 'Это поле обезательно',
                              (v) => checkInteger(v) || 'Должно быть число',
                            ]"
                            outlined
                            suffix="мм"
                          />
                          <v-checkbox
                            v-model="pack.dry"
                            outlined
                            :disabled="isSavePackage"
                            label="Сухой"
                          />
                          <base-material-card
                            icon="mdi-clipboard-text"
                            title="Доски в пакете"
                            class="px-5 py-3"
                          >
                            <v-simple-table dense>
                              <thead>
                                <tr>
                                  <th class="primary--text">Длина</th>
                                  <th class="primary--text">Кол-во, шт</th>
                                  <th v-if="volume" class="primary--text">
                                    Объём, м³
                                  </th>
                                </tr>
                              </thead>

                              <tbody>
                                <tr
                                  v-for="board in pack.boardsArray"
                                  :key="board.length"
                                >
                                  <td>{{ board.length }}</td>
                                  <td>{{ board.amount }}</td>
                                  <td v-if="volume">
                                    {{
                                      (
                                        (board.amount *
                                          board.length *
                                          pack.thickness *
                                          pack.width) /
                                        1e9
                                      ).toFixed(4)
                                    }}
                                  </td>
                                </tr>
                                <tr v-if="volume">
                                  <td class="text-right font-weight-bold">
                                    <b>Общий итог</b>
                                  </td>
                                  <td class="font-weight-bold">
                                    {{
                                      pack.boardsArray.reduce(
                                        (total, board) => total + board.amount,
                                        0,
                                      )
                                    }}
                                  </td>
                                  <td class="font-weight-bold">
                                    {{ volume.toFixed(4) }}
                                  </td>
                                </tr>
                              </tbody>
                            </v-simple-table>
                          </base-material-card>
                        </v-col>
                        <v-col md="6" sm="12">
                          <v-timeline dense>
                            <v-timeline-item
                              v-for="(move, index) in pack.packageMoves"
                              :key="index"
                              small
                            >
                              <div class="py-4">
                                <h2
                                  :class="`headline font-weight-light mb-4 primary--text`"
                                >
                                  {{ move.dst.name }}
                                  <span
                                    :class="`headline font-weight-bold secondary--text`"
                                    v-text="move.drecTime"
                                  />
                                </h2>
                                <div>
                                  {{ move.comment }}
                                </div>
                              </div>
                            </v-timeline-item>
                          </v-timeline>
                          <v-row
                            class="pa-10"
                            align="center"
                            justify="space-around"
                          >
                            <v-btn
                              block
                              large
                              :disabled="isSavePackage"
                              color="primary"
                              @click="savePackage"
                            >
                              Сохранить
                            </v-btn>
                            <v-dialog v-model="dialogLocation" max-width="500">
                              <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                  color="info"
                                  large
                                  block
                                  v-bind="attrs"
                                  v-on="on"
                                >
                                  Переместить
                                </v-btn>
                              </template>
                              <base-material-card>
                                <template v-slot:heading>
                                  <div class="text-h4 font-weight-light">
                                    Куда переместить пакет?
                                  </div>
                                </template>
                                <v-card-text>
                                  <v-row>
                                    <v-textarea
                                      outlined
                                      v-model="comment"
                                      label="Комментарий"
                                    />
                                  </v-row>
                                  <v-row
                                    v-for="location in packageLocation"
                                    :key="location.id"
                                  >
                                    <v-btn
                                      block
                                      color="primary"
                                      @click="sendLocation(location['@id'])"
                                    >
                                      {{ location.name }}
                                    </v-btn>
                                  </v-row>
                                </v-card-text>
                              </base-material-card>
                            </v-dialog>
                            <v-btn
                              block
                              large
                              color="primary"
                              @click="printTicket"
                            >
                              {{
                                isSavePackage
                                  ? 'Напечатать'
                                  : 'Сохранить и напечатать'
                              }}
                            </v-btn>
                          </v-row>
                        </v-col>
                      </v-row>
                    </v-form>
                  </v-card-text>
                </base-material-card>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import Axios from 'axios'
import { QrcodeStream } from 'vue-qrcode-reader'
export default {
  components: {
    QrcodeStream,
  },
  data: () => ({
    isSavePackage: false,
    dialogLocation: false,
    loadingPackage: false,
    error: '',
    comment: '',
    dialogQr: false,
    valid: true,
    pack: {},
    packageLocation: [],
    species: [],
    qualities: [],
  }),
  computed: {
    volume() {
      let sum = null
      if (
        this.checkInteger(this.pack.thickness) &&
        this.checkInteger(this.pack.width)
      ) {
        sum = this.pack.boardsArray.reduce(
          (total, board) =>
            total +
            (this.pack.width *
              this.pack.thickness *
              board.length *
              board.amount) /
              1e9,
          0,
        )
      }
      return sum
    },
  },
  beforeMount() {
    const packageId = this.getParameterByName('packageId')
    this.initSpecies()
    this.initQualities()
    this.initPackageLocation()
    if (packageId) this.initPackage(packageId)
  },
  methods: {
    async initPackageLocation() {
      const request = await Axios.get('api/package_locations')
      this.packageLocation = request.data['hydra:member']
    },
    async initSpecies() {
      const request = await Axios.get('api/species')
      this.species = request.data['hydra:member']
    },
    async initQualities() {
      const request = await Axios.get('api/qualities')
      this.qualities = request.data['hydra:member']
    },
    async initPackage(packageId) {
      let request
      this.loadingPackage = false
      try {
        request = await Axios.get('/api/packages/' + packageId)
        this.pack = request.data
        if (
          this.pack.species &&
          this.pack.qualities &&
          this.pack.width &&
          this.pack.thickness
        )
          this.isSavePackage = true
        this.loadingPackage = true
      } catch (error) {
        if (error.response.status == 404) {
          this.$snotify.error('Пакет не найден')
        } else {
          this.$snotify.error(
            error.response.status + '   ' + error.response.data,
          )
        }
      } finally {
        return request
      }
    },
    async onInit(promise) {
      try {
        await promise
      } catch (error) {
        if (error.name === 'NotAllowedError') {
          this.$snotify.error('ERROR: вы должны дать доступ к камере')
        } else if (error.name === 'NotFoundError') {
          this.$snotify.error('ERROR: на этом устройстве нет камеры')
        } else if (error.name === 'NotSupportedError') {
          this.$snotify.error(
            'ERROR: secure context required (HTTPS, localhost)',
          )
        } else if (error.name === 'NotReadableError') {
          this.$snotify.error('ERROR: камера уже используется?')
        } else if (error.name === 'OverconstrainedError') {
          this.$snotify.error('ERROR: установленные камеры не подходят')
        } else if (error.name === 'StreamApiNotSupportedError') {
          this.$snotify.error(
            'ERROR: Stream API не поддерживается в этом браузере',
          )
        }
      }
    },
    onDecode(decodeString) {
      const packageId = decodeString
      if (packageId) {
        this.initPackage(packageId)
        this.dialogQr = false
      }
    },
    getParameterByName(name, url = window.location.href) {
      name = name.replace(/[[\]]/g, '\\$&')
      var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)')
      var results = regex.exec(url)
      if (!results) return null
      if (!results[2]) return ''
      return decodeURIComponent(results[2].replace(/\+/g, ' '))
    },
    async savePackage() {
      if (this.$refs.form.validate()) {
        let request
        try {
          let object = {
              thickness: this.pack.thickness,
              width: this.pack.width,
              species: this.pack.species,
              qualities: this.pack.qualities,
              dry: this.pack.dry,
            }
            request = await Axios.put(this.pack['@id'], object)
          this.isSavePackage = true
          this.pack = request.data
          return true
        } catch (error) {
          this.$snotify.error(error.response.data)
        }
      } else {
        return false
      }
    },
    checkInteger(value) {
      return (
        !isNaN(value) &&
        ((x) => {
          return (x | 0) === x
        })(parseFloat(value))
      )
    },
    async printTicket() {
      if (this.isSavePackage || await this.savePackage()) {
        window.open('/ticket/' + this.pack.id)
      }
    },
    async sendLocation(idLocation) {
      this.dialogLocation = false
      const request = await Axios.post('api/package_moves', {
        src: this.pack.currentLocation['@id'],
        dst: idLocation,
        comment: this.comment,
        package: this.pack['@id'],
      })
      this.comment = ''
      this.initPackage(this.pack.id)
    },
  },
}
</script>
