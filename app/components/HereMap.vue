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
                userType: false, //false - user, true - driver
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
                axios.get('/api/test').then((data) => {
                    console.log(data)
                })
            }, 3000)

            if(vh.$root.sessionId) vh.userType = true

            vh.makeMap()
            this.map.setZoom(16)

            navigator.geolocation.watchPosition((position) => {
                console.log(position)
                vh.currentPosition = position.coords
                vh.setCenter()
            },(error) => {
                console.log(error)
            })


        },
        methods: {
            makeMap() {
                this.platform = new H.service.Platform({
                    'app_id': '6qi8a5qmtadTCJfJe5lJ',
                    'app_code': 'qutMUwqcoVmSFK8--6AWZA',
                    useHTTPS: true,
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
            },
            setCenter() {
                this.map.setCenter({
                    lat: this.currentPosition.latitude,
                    lng: this.currentPosition.longitude,
                })
                this.map.addObject(new H.map.Marker({
                    lat: this.currentPosition.latitude,
                    lng: this.currentPosition.longitude,
                }));
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
