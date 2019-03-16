<template>
    <div id="map-wrapper">
        <div id="here-map"></div>
    </div>
</template>

<script>
    export default {
        name: 'here-map',
        data() {
            return {
                currentPosition: null,
                map: {
                    pixelRatio: null,
                    defaultLayers: null, 
                    map: null,
                    behavior: null,
                    ui: null,
                },
            }
        },
        mounted() {
            const vh = this

            setInterval(() => {
                navigator.geolocation.getCurrentPosition((position) => {
                            vh.currentPosition = position.coords
                    }
                )
            }, 500)

            vh.makeMap()







        },
        methods: {
            makeMap() {
                this.platform = new H.service.Platform({
                    'app_id': '6qi8a5qmtadTCJfJe5lJ',
                    'app_code': 'qutMUwqcoVmSFK8--6AWZA'
                })

                this.pixelRatio = window.devicePixelRatio || 1;
                this.defaultLayers = this.platform.createDefaultLayers({
                    tileSize: this.pixelRatio === 1 ? 256 : 512,
                    ppi: this.pixelRatio === 1 ? undefined : 320
                })

                this.map = new H.Map(document.getElementById('here-map'),
                this.defaultLayers.normal.map, {pixelRatio: this.pixelRatio})

                this.behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(this.map))

                this.ui = H.ui.UI.createDefault(this.map, this.defaultLayers)
            }
        }
    }
</script>

<style>
 #map-wrapper {
     width: 100wh;
     height: 100vh;
 }

 #here-map {
     width: 100%;
     height: 100%;
 }
</style>
