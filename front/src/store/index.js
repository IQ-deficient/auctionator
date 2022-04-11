import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        search_category: "",
    },
    mutations: {
        setSearchCategory(state, payload) {
            state.search_category = payload
        }
    },
    actions: {},
    modules: {},
    getters: {
        getSearchCategory(state) {
            return state.search_category
        }
    }
})
