<template>
    <div class="col-5 px-0">
        <div class="bg-white">

            <div class="bg-gray px-4 py-2 bg-light">
                <p class="h5 mb-0 py-1">Reciente</p>
            </div>

            <div class="messages-box">
                <div class="list-group rounded-0">
                    <template v-for="(conversacion, index, key) in CONVERSACIONES">
                        <Conversacion :conversacion="conversacion" />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import Conversacion from "./Conversacion";
    export default {
        components: {Conversacion},
        computed: {
          ...mapGetters(["CONVERSACIONES", "HUBURL", "USERNAME"])
        },
        methods: {
            updateConversaciones(data) {
                this.$store.commit("UPDATE_CONVERSACIONES", data)
            }
        },
        mounted() {
            const vm = this;
            this.$store.dispatch("GET_CONVERSACIONES")
                .then(() => {
                    let url = new URL(this.HUBURL);
                    url.searchParams.append('topic', `/conversaciones/${this.USERNAME}`)
                    const eventSource = new EventSource(url, {
                        withCredentials: true
                    })
                    eventSource.onmessage = function (event) {
                        vm.updateConversaciones(JSON.parse(event.data))
                    }
                })
        }
    }
</script>