<template>
    <router-link :to="{name: 'conversacion', params: {id: conversacion.conversacionId}}" class="list-group-item list-group-item-action rounded-0">
        <div class="media">
            <img src="https://www.pngfind.com/pngs/m/470-4703547_icon-user-icon-hd-png-download.png" alt="user" width="50" class="rounded-circle">
            <div class="media-body ml-4">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <h6 class="mb-0">{{ conversacion.email }}</h6><small class="small font-weight-bold">{{ date }}</small>
                </div>
                <p class="font-italic mb-0 text-small">{{ conversacion.contenido }}</p>
            </div>
        </div>
    </router-link>
</template>

<script>
    export default {
        props: {
            conversacion: Object
        },
        computed: {
            date() {
                return new Date(this.conversacion.creadoEl).toLocaleTimeString();
            }
        }
    }
</script>