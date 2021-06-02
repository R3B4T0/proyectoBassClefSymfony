import Vue from 'vue';

export default {
    state: {
        conversaciones: [],
        hubUrl: null
    },
    getters: {
        CONVERSACIONES: state => {
            return state.conversaciones.sort((a, b) => {
                return a.creadoEl < b.creadoEl;
            })
        },
        MENSAJES: state => conversacionId => {
            return state.conversacionesId.find(i => i.conversacionId === conversacionId).mensajes
        },
        HUBURL: state => state.hubUrl
    },
    mutations: {
        SET_CONVERSACIONES: (state, payload) => {
            state.conversaciones = payload
        },
        SET_MENSAJES: (state, {conversacionId, payload}) => {
            Vue.set(
                state.conversaciones.find(i => i.conversacionId === conversacionId),
                'mensajes',
                payload
            )
        },
        ADD_MENSAJE: (state, {conversacionId, payload}) => {
            state.conversaciones.find(i => i.conversacionId === conversacionId).mensajes.push(payload)
        },
        SET_CONVERSACION_ULTIMO_MENSAJE: (state, {conversacionId, payload}) => {
            let rs = state.conversaciones.find(i => i.conversacionId === conversacionId);
            rs.contenido = payload.contenido;
            rs.creadoEl = payload.creadoEl;
        },
        SET_HUBURL: (state, payload) => state.hubUrl = payload,
        UPDATE_CONVERSACIONES: (state, payload) => {
            let rs = state.conversaciones.find(i => i.conversacionId === payload.conversacion.id);
            rs.contenido = payload.contenido;
            rs.creadoEl = payload.creadoEl;
        }
    },
    actions: {
        GET_CONVERSACIONES: ({commit}) => {
            return fetch(`/conversaciones`)
                .then(result => {
                    const hubUrl = result.headers.get('Link').match(/<([^>]+)>;\s+rel=(?:mercure|"[^"]*mercure[^"]*")/)[1]
                    commit("SET_HUBURL", hubUrl)
                    return result.json()
                })
                .then((result) => {
                    commit("SET_CONVERSACIONES", result)
                })
        },
        GET_MENSAJES: ({commit, getters}, conversacionId) => {
            if (getters.MENSAJES(conversacionId) === undefined) {
                return fetch(`/mensajes/${conversacionId}`)
                    .then(result => result.json())
                    .then((result) => {
                        commit("SET_MENSAJES", {conversacionId, payload: result})
                    });
            }

        },
        POST_MENSAJE: ({commit}, {conversacionId, contenido}) => {
            let formData = new FormData();
            formData.append('contenido', contenido);

            return fetch(`/mensajes/${conversacionId}`, {
                method: "POST",
                body: formData
            })
                .then(result => result.json())
                .then((result) => {
                    commit("ADD_MENSAJE", {conversacionId, payload: result})
                    commit("SET_CONVERSACION_ULTIMO_MENSAJE", {conversacionId, payload: result})
                })
        }
    }
}