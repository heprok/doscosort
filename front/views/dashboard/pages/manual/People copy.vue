<template>
  <v-container id="manual_people_dashboard" fluid tag="section">
    <v-card>
      <v-tabs
        v-model="tab"
        background-color="primary accent-4"
        fixed-tabs
        dark
        icons-and-text
      >
        <v-tabs-slider></v-tabs-slider>

        <v-tab href="#tab-people">
          Персонал
          <v-icon>mdi-account-group</v-icon>
        </v-tab>

        <v-tab href="#tab-duty">
          Должности
          <v-icon>mdi-account-box</v-icon>
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item value="tab-people">
          <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
              <v-card-title class="headline">
                Вы уверены, что хотите удалить?
              </v-card-title>
              <v-card-actions>
                <v-spacer />
                <v-btn color="blue darken-1" text @click="closeDeleteDialog">
                  Нет
                </v-btn>
                <v-btn
                  color="blue darken-1"
                  :loading="loadingBtn"
                  text
                  @click="deleteItemConfirm"
                >
                  Да
                </v-btn>
                <v-spacer />
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogCheckPassword" persistent width="500">
            <base-material-card icon="mdi-key" title="Потвердите права">
              <v-card-title />
              <v-card-text>
                <v-row align-content="center">
                  <v-text-field
                    v-model="password"
                    label="Пароль"
                    type="password"
                  />
                </v-row>
              </v-card-text>
              <v-card-actions>
                <v-row>
                  <v-col>
                    <v-btn
                      width="160"
                      color="error"
                      class="mr-0"
                      @click="closeDialogCheckPassword"
                    >
                      Отмена
                    </v-btn>
                  </v-col>
                  <v-col class="d-flex justify-end">
                    <v-btn
                      width="160"
                      color="primary"
                      @click="confirmDialogCheckPassword"
                    >
                      Потвердить
                    </v-btn>
                  </v-col>
                </v-row>
              </v-card-actions>
            </base-material-card>
          </v-dialog>
          <v-card flat>
            <v-data-table
              :headers="people.headers"
              :search="people.search"
              :loading="people.loading"
              loading-text="Загрузка... Ждите"
              :items="people.items"
              class="elevation-1"
            >
              <template v-slot:[`item.actions`]="{ item }">
                <v-icon small class="mr-2" @click="editItemAction(item)">
                  mdi-pencil
                </v-icon>
                <v-icon small @click="deleteItemAction(item)">
                  mdi-delete
                </v-icon>
              </template>
              <template v-slot:no-data>
                <v-btn color="primary" @click="updatePeople"> Обновить </v-btn>
              </template>
              <template #top>
                <v-toolbar flat>
                  <v-toolbar-title>
                    <v-row>
                      <v-btn
                        color="primary"
                        icon
                        large
                        :loading="loadingBtn"
                        @click="updatePeople"
                      >
                        <v-icon>mdi-cached</v-icon>
                      </v-btn>
                      <v-spacer />
                      <v-dialog v-model="people.dialogAdded" max-width="700px">
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            color="primary"
                            large
                            dark
                            icon
                            v-bind="attrs"
                            v-on="on"
                          >
                            <v-icon>mdi-plus</v-icon>
                          </v-btn>
                        </template>
                        <v-card>
                          <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                          </v-card-title>

                          <v-card-text>
                            <v-container>
                              <v-row>
                                <v-col cols="12" md="4">
                                  <v-text-field
                                    v-model="people.editedItem.nam"
                                    label="Имя"
                                    required
                                  ></v-text-field>
                                </v-col>

                                <v-col cols="12" md="4">
                                  <v-text-field
                                    v-model="people.editedItem.fam"
                                    label="Фамилия"
                                  ></v-text-field>
                                </v-col>

                                <v-col cols="12" md="4">
                                  <v-text-field
                                    v-model="people.editedItem.pat"
                                    label="Отчество"
                                  ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                  <v-select
                                    v-model="people.editedItem.duty"
                                    :items="duties.list"
                                    item-text="name"
                                    item-value="@id"
                                    attach
                                    chips
                                    label="Должности"
                                    multiple
                                  >
                                  </v-select>
                                </v-col>
                              </v-row>
                            </v-container>
                          </v-card-text>

                          <v-card-actions>
                            <v-spacer />
                            <v-btn
                              color="blue darken-1"
                              text
                              @click="closeAddedDialog"
                            >
                              Отмена
                            </v-btn>
                            <v-btn
                              color="blue darken-1"
                              :loading="loadingBtn"
                              text
                              @click="save"
                            >
                              Сохранить
                            </v-btn>
                          </v-card-actions>
                        </v-card>
                      </v-dialog>
                    </v-row>
                  </v-toolbar-title>
                </v-toolbar>
              </template>
            </v-data-table>
          </v-card>
        </v-tab-item>
        <v-tab-item value="tab-duty">
          <v-card flat>
            <crud-table
              title="Список должностей"
              :url-api="duties.urlApi"
              icon="mdi-account-box"
              :headers="duty.headers"
              isCheckPass
              is-crud
            />
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
  </v-container>
</template>

<script>
import Axios from "axios";
import crypto from "crypto";
export default {
  name: "manual_people_dashboard",
  components: {},

  data() {
    return {
      tab: null,
      text: "",
      dialogDelete: false,
      editedIndex: -1,
      dialogCheckPassword: false,
      editedItem: {},
      loadingBtn: false,
      password: "",
      selectModel: [],
      defaultItem: {},
      duties: {
        urlApi: "/duties",
        list: [],
        model: [],
      },
      people: {
        urlApi: "/people",
        loading: false,
        search: "",
        dialogAdded: false,
        editedItem: {},
        items: [],
        headers: [
          { text: "Фамилия", value: "fam" },
          { text: "Имя", value: "nam" },
          { text: "Отчество", value: "pat" },
          { text: "Должности", value: "dutiesString" },
          { text: "Действия", value: "actions", edited: false },
        ],
      },
      duty: {
        headers: [
          { text: "Код", value: "code" },
          { text: "Название", value: "name" },
          { text: "Действия", value: "actions", edited: false },
        ],
      },
    };
  },
  computed: {
    formTitle() {
      return this.people.editedIndex === -1 ? "Добавление" : "Редактирование";
    },
  },
  watch: {
    dialogAdded(val) {
      val || this.closeAddedDialog();
    },
    dialogDelete(val) {
      val || this.closeDeleteDialog();
    },
  },
  mounted() {
    this.updatePeople();
    Axios.get(this.$store.state.apiEntryPoint + this.duties.urlApi).then(
      (response) => {
        this.duties.list = response.data["hydra:member"];
      }
    );
  },
  methods: {
    async updatePeople() {
      this.loadingBtn = true;
      this.people.items = [];
      const config = {
        params: this.query,
      };
      const request = await Axios.get(
        this.$store.state.apiEntryPoint + this.people.urlApi,
        config
      );
      this.people.items = request.data["hydra:member"];
      this.loadingBtn = false;
      return request;
    },
    async deleteItem() {
      const request = await Axios.delete(this.people.editedItem["@id"]);
      return request;
    },
    async editItem() {
      const request = await Axios.put(
        this.people.editedItem["@id"],
        this.people.editedItem
      );
      return request;
    },
    async addItem() {
      const request = await Axios.post(
        this.$store.state.apiEntryPoint + this.people.urlApi,
        this.people.editedItem
      );
      return request;
    },
    editItemAction(item) {
      this.loadingBtn = false;
      this.people.editedIndex = this.people.items.indexOf(item);
      this.people.editedItem = Object.assign({}, item);
      this.people.dialogAdded = true;
    },
    async deleteItemConfirm() {
      if (this.checkPassword()) {
        this.loadingBtn = true;
        await this.deleteItem();
        await this.updatePeople();
        this.loadingBtn = false;
        this.people.editedIndex = -1;
        this.people.editedItem = {};
        this.closeDeleteDialog();
      }
    },
    deleteItemAction(item) {
      this.loadingBtn = false;
      this.people.editedIndex = this.people.items.indexOf(item);
      this.people.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },
    closeAddedDialog() {
      this.people.dialogAdded = false;
      this.$nextTick(() => {
        this.people.editedItem = Object.assign({}, this.defaultItem);
        this.people.editedIndex = -1;
      });
    },
    checkPassword() {
      if (this.$store.state.isAdmin) return true;

      this.dialogCheckPassword = true;
    },
    closeDialogCheckPassword() {
      this.dialogCheckPassword = false;
      this.password = "";
    },
    confirmDialogCheckPassword() {
      if (
        // TODO удалить
        // tls-pass
        crypto.createHash("sha512").update(this.password).digest("hex") ==
        "396d55a413d2c368e78ecefd2e818a79b09236e067ddb649a6cd24d85e3a25585bdb2f787c20a78e7b0ec8fc0ba348fc65a2fe85f674eaa67de678c6f8ade11d"
      ) {
        this.$store.commit("SET_ADMIN", true);
        this.closeDialogCheckPassword();
      } else {
        // console.log(this.$snotify);
        this.$snotify.error("Неверный пароль пароль");
        this.$store.commit("SET_ADMIN", false);
      }
    },
    closeDeleteDialog() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.people.editedIndex = -1;
      });
    },

    async save() {
      this.loadingBtn = true;
      if (this.checkPassword()) {
        if (this.people.editedIndex > -1) {
          Object.assign(
            this.people.items[this.people.editedIndex],
            this.people.editedItem
          );
          await this.editItem();
        } else {
          await this.addItem();
        }
        await this.updatePeople();
        this.closeAddedDialog();
      }
      this.loadingBtn = false;
    },
  },
};
</script>
