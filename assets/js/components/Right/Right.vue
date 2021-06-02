<template>
    <div class="col-7 px-0">
        <div class="px-4 py-5 chat-box bg-white" ref="messagesBody">
            <template v-for="(mensaje, index, key) in MENSAJES">
                <Mensaje :mensaje="mensaje"/>
            </template>
        </div>

        <Input/>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import Mensaje from "./Mensaje";
    import Input from "./Input";
    export default {
        data: () => ({
            eventSource: null
        }),
        components: {Mensaje, Input},
        computed: {
            ...mapGetters(["HUBURL"]),
            MENSAJES() {
                return this.$store.getters.MENSAJES(this.$route.params.id);
            }
        },
        methods: {
            scrollDown() {
                this.$refs.mensajesBody.scrollTop = this.$refs.mensajesBody.scrollHeight;
            },
            addMensaje(data) {
                this.$store.commit("ADD_MENSAJE", {
                    conversacionId: this.$route.params.id,
                    payload: data
                })
            }
        },
        mounted() {
            const vm = this;
            this.$store.dispatch("GET_MENSAJES", this.$route.params.id)
                .then(() => {
                    this.scrollDown();
                    if (this.eventSource === null) {
                        let url = new URL(this.HUBURL);
                        url.searchParams.append('topic', `/conversaciones/${this.$route.params.id}`)
                        this.eventSource = new EventSource(url, {
                            withCredentials: true
                        })
                        this.eventSource.onmessage = function (event) {
                            vm.addMensaje(JSON.parse(event.data))
                        }
                    }
                })
        },
        watch: {
            MENSAJES: function (val) {
                this.$nextTick(() => {
                    this.scrollDown();
                })
            }
        },
        beforeDestroy() {
            if (this.eventSource instanceof EventSource) {
                this.eventSource.close();
            }
        }
    }
</script>