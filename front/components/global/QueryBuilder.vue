<template>
  <v-card :loading="loading">
    <v-card-text>
      <tlc-query-builder
        :rules="rules"
        v-if="visible"
        v-model="query"
        :maxDepth="1"
        @input="$emit('input', $event)"
      />
    </v-card-text>
  </v-card>
</template>

<script>
import tlcQueryBuilder from "builder-query-vuetify";
import { RuleTypes } from "builder-query-vuetify/src/utilities.js";
import Axios from "axios";

export default {
  name: "defaultTlcQueryBuilder",
  components: { tlcQueryBuilder },
  props: {
    value: {
      required: true,
    },
    filters: {
      type: Array,
      required: true,
    },
  },
  methods: {
    getQuery() {
      return this.getSqlWhere();
    },

    getSqlWhere() {
      if (this.query.children == null) return "";
      let sqlQuery = "";
      let arrayValues = [];
      this.query.children.forEach((children) => {
        if (children.type == "rule") {
          if (!children.query.value || children.query.value == "") return;
          let query = {};
          // console.log(children.query)
          // console.log(Array.isArray(children.query.value) ? children.query.value.join() + ')' : ' false');
          // let value =
          //   children.query.id +
          //   " " +
          //   children.query.operator +
          //   " " +
          //   (Array.isArray(children.query.value)
          //     ? "(" + children.query.value.join() + ")"
          //     : children.query.value);
          // sqlQuery += value;
          query.id = children.query.id;
          query.nameTable = children.query.nameTable;
          query.logicalOperator = this.query.operator;
          query.operator = children.query.operator;
          query.value = Array.isArray(children.query.value)
            ? "('" + children.query.value.join("','") + "')"
            : children.query.value;
          arrayValues.push(query);
        }
        if (children.type == "group") {
          sqlQuery += "(";
          children.query.children.forEach((val) => {
            sqlQuery +=
              val.query.rule +
              val.query.operator +
              val.query.value +
              " " +
              children.query.operator +
              " ";
          });
          sqlQuery = sqlQuery.substring(0, sqlQuery.length - 4);
          sqlQuery += ")";
        }
        // sqlQuery += " " + this.query.operator + " ";
      });
      // sqlQuery = sqlQuery.substring(0, sqlQuery.length - 4);
      return arrayValues;
    },

    getRuleDowntimePlace() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "place",
        label: "Места простоя",
        nameTable: "d.",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/downtime_places")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((place) => {
            return {
              value: place.code,
              label: place.name,
            };
          });
          this.countLoadingRules++;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке мест простоев");
          console.log(err);
        });
      return rule;
    },
    getRuleDowntimeCause() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "cause",
        label: "Причины простоя",
        nameTable: "d.",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/downtime_causes")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((cause) => {
            return {
              value: cause.code,
              label: cause.name,
            };
          });
          this.countLoadingRules++;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке мест простоев");
          console.log(err);
        });
      return rule;
    },
    getRuleEventType() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "type",
        label: "Тип события",
        nameTable: "e.",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/event_types")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((type) => {
            return {
              value: type.id,
              label: type.name,
            };
          });
          this.countLoadingRules++;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке тип событий");
          console.log(err);
        });
      return rule;
    },
    getRuleEventSource() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "source",
        label: "Истотчник события",
        nameTable: "e.",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/event_sources")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((source) => {
            return {
              value: source.id,
              label: source.name,
            };
          });
          this.countLoadingRules++;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке тип событий");
          console.log(err);
        });
      return rule;
    },
    getRuleSpecies() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "s.id",
        label: "Породы",
        nameTable: "",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/species")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((specie) => {
            return {
              value: specie.id,
              label: specie.name,
            };
          });
          this.countLoadingRules++;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке пород");
          console.log(err);
        });
      return rule;
    },
    getRulePostav() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "p.id",
        label: "Постав",
        nameTable: "",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/postavs")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((postav) => {
            return {
              value: postav.id,
              label: postav.name,
            };
          });
          this.countLoadingRules++;
          // console.log(rule.options);
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке постава");
          console.log(err);
        });
      return rule;
    },
    getRuleDiam() {
      let rule = {
        type: RuleTypes.NUMBER,
        id: "diam",
        label: "Диаметр бревна, см. ",
        nameTable: "t.",
      };
      this.countLoadingRules++;

      return rule;
    },
    getRuleTimberPostavDiam() {
      let rule = {
        type: RuleTypes.NUMBER,
        id: "CAST(p.postav->'top' AS float)",
        label: "Диаметр постава, мм. ",
        nameTable: "",
      };
      this.countLoadingRules++;

      return rule;
    },
    getRuleBoardPostavDiam() {
      let rule = {
        type: RuleTypes.NUMBER,
        id: "get_int_into_by_key(p.postav, 'top')",
        label: "Диаметр постава, мм. ",
        nameTable: "",
      };
      this.countLoadingRules++;

      return rule;
    },
    getRuleLength() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "standard_length(t.length)",
        label: "Длина, мм.",
        nameTable: "",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/lengths")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((length) => {
            return {
              value: length.standard,
              label: length.standard,
            };
          });
          this.countLoadingRules++;
          // console.log(rule.options);
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке длин");
          console.log(err);
        });
      return rule;
    },
    getRuleCut() {
      let rule = {
        type: RuleTypes.TEXT,
        id: "cut",
        label: "Сечение",
        nameTable: "t.",
      };
      this.countLoadingRules++;

      return rule;
    },
  },
  mounted() {
    this.filters.forEach((filter) => {
      switch (filter) {
        case "downtime_place":
          this.rules.push(this.getRuleDowntimePlace());
          break;
        case "downtime_cause":
          this.rules.push(this.getRuleDowntimeCause());
          break;
        case "event_type":
          this.rules.push(this.getRuleEventType());
          break;
        case "event_source":
          this.rules.push(this.getRuleEventSource());
          break;
        case "postav":
          this.rules.push(this.getRulePostav());
          break;
        case "diam":
          this.rules.push(this.getRuleDiam());
          break;
        case "postav_board_diam":
          this.rules.push(this.getRuleBoardPostavDiam());
          break;
        case "postav_timber_diam":
          this.rules.push(this.getRuleTimberPostavDiam());
          break;
        case "cut":
          this.rules.push(this.getRuleCut());
          break;
        case "species":
          this.rules.push(this.getRuleSpecies());
          break;
        case "length":
          this.rules.push(this.getRuleLength());
          break;
        default:
          break;
      }
    });
  },
  watch: {
    countLoadingRules() {
      // conso
      if (this.rules.length === this.countLoadingRules) {
        this.visible = true;
        this.loading = false;
      } else {
        this.visible = false;
        this.loading = true;
      }
    },
  },
  computed: {},
  data() {
    return {
      query: this.value,
      loading: true,
      visible: false,
      rules: [],
      countLoadingRules: 0,
    };
  },
};
</script>

<style>
</style>