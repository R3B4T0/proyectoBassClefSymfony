import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import conversacion from "./modules/conversacion.js";
import usuario from "./modules/usuario.js";
export default new Vuex.Store({
    modules: {
        conversacion,
        usuario
    }
})
