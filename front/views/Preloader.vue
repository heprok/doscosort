<template>
  <v-app>
    <v-img
      class="aa"
      gradient="to top right, rgba(100,115,201,.33), rgba(25,32,72,.7)"
    />
    <div class="preloader">
      <LoaderTlc />
    </div>
  </v-app>
</template>

<script>
export default {
  name: "Preloader",
  data: () => ({}),
  mounted() {
    // document.location.replace(
    //   "report/board/2021-06-18T08:16:11+08:00...2021-07-01T15:16:29+08:00/people/3/pdf?sqlWhere=[]"
    // );
  },
  watch: {},
  methods: {},
};
</script>

<style lang="sass">
.aa
    width: 100%
    height: 100vh
.preloader
    display: flex
    flex-direction: column
    justify-content: center
    align-items: center
    position: absolute
</style>