import Vue          from 'vue'
import Vuex         from 'vuex'

/*----------   Global   ----------*/
import actions      from './globals/actions'
// import getters      from './globals/getters'
import state        from './globals/state'
import mutations    from './globals/mutations'

/*----------   Other   ----------*/
import config       from '../config'

Vue.use(Vuex);

export default new Vuex.Store({
    state,
    actions,
    mutations,
    strict: config.debug
})