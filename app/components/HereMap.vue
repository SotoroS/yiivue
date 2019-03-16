<template>
    <div id="map-wrapper">
        <div id="here-map"></div>
    </div>
</template>

<script>
    export default {
        name: 'here-map',
        props: ['driverOnRoute'],
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

            this.checkAuth() //проверяет водитель или нет

            if(vh.$root.sessionId) vh.userType = true

            vh.makeMap()
            this.map.setZoom(16)

            navigator.geolocation.watchPosition((position) => {
                // console.log(position)
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
                //отправляю данные водителя который на маршруте
                if(this.driverOnRoute && this.userType) {
                        axios.post('/api/send-user-coords', {
                        lat: this.currentPosition.latitude,
                        lng: this.currentPosition.longitude,
                        time: new Date().getTime()
                    }).then(({data}) => {
                        console.log(data)
                    })
                }
            },
            checkAuth() {
                axios.get('/api/check-auth').then(({data}) => {
                    this.userType = data.status
                    console.log('Usertype', data)
                }).catch((error) => {
                    console.log(error)
                })
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
