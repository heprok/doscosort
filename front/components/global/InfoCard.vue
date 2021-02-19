<script>
  import InfoCard from 'tlc-front-components/src/InfoCard'

  export default {
    name: 'InfoCard',
    extends: InfoCard,
    data(){
      return {
        entryPointApi: this.$store.state.apiEntryPoint,
      } 
    },
  }
</script>
