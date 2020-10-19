import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    barColor: 'rgba(0, 0, 0, .3), rgba(0, 0, 0, .7)',
    barImage: 'build/images/sidebar.jpg',
    drawer: null,
    isAdmin: false,
  },
  mutations: {
    SET_BAR_IMAGE (state, payload) {
      state.barImage = payload
    },
    SET_DRAWER (state, payload) {
      state.drawer = payload
    },
    SET_ADMIN: (state, payload ) => {
      state.isAdmin = payload;
    },
  },
  getters: {
    IS_ADMIN: state => {
      return state.isAdmin;
    }
  },
  actions: {

  },
})
