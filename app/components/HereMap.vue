<template>
    <div id="map-wrapper">
        <div id="here-map"></div>
    </div>
</template>

<script>
    export default {
        name: 'here-map',
        props: ['driverOnRoute', 'search'],
        data() {
            return {
                currentPosition: null,
                userType: false, //false - user, true - driver
                driverRoute: null,
                mapCoords: {},
                map: {
                    pixelRatio: null,
                    defaultLayers: null, 
                    map: null,
                    behavior: null,
                    ui: null,
                },
                meMarker: null,
                transport: [],
            }
        },
        mounted() {
            const vh = this

            vh.mapCoords.leftUp = {x: 0, y: 0}
            vh.mapCoords.rightDown = {x: document.body.clientWidth, y: document.body.clientHeight}

            this.checkAuth() //проверяет водитель или нет

            if(vh.$root.sessionId) vh.userType = true

            vh.makeMap()
            vh.map.setZoom(16)

            vh.map.setCenter({ lat: 48.7010363, lng: 44.5178491 })

            navigator.geolocation.watchPosition((position) => {
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
                // this.map.setCenter({
                //     lat: this.currentPosition.latitude,
                //     lng: this.currentPosition.longitude,
                // })
                let icon = ''

                if(this.userType) {
                    icon = '<svg height="32px" fill="#2381D8" viewBox="0 -44 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg"><path d="m323.671875 194.949219 29.082031 29.082031c15.449219 15.453125 36 23.964844 57.851563 23.964844h81.394531c11.046875 0 20-8.957032 20-20v-6.0625c0-19.078125-3.644531-37.707032-10.828125-55.378906l-47.4375-116.667969c-12.316406-30.289063-41.40625-49.8632815-74.105469-49.8671878l-299.621094-.0195312c-.003906 0-.003906 0-.007812 0-21.367188 0-41.457031 8.320312-56.566406 23.429688-15.113282 15.109374-23.433594 35.199218-23.433594 56.570312v223.097656c0 43.582032 35.039062 79.113282 78.425781 79.957032 8.246094 23.300781 30.484375 40.042968 56.574219 40.042968 26.070312 0 48.304688-16.722656 56.558594-40h123.882812c8.253906 23.277344 30.484375 40 56.558594 40s48.304688-16.722656 56.558594-40h3.441406c37.964844 0 70.945312-26.984375 78.429688-64.15625 2.175781-10.832031-4.835938-21.375-15.664063-23.554687-10.828125-2.175781-21.375 4.835937-23.550781 15.664062-3.738282 18.566407-20.230469 32.046875-39.214844 32.046875h-3.441406c-8.253906-23.28125-30.484375-40-56.558594-40s-48.304688 16.71875-56.558594 40h-123.882812c-8.253906-23.28125-30.484375-40-56.558594-40-26.058594 0-48.277344 16.703125-56.546875 39.960938-21.339844-.820313-38.453125-18.425782-38.453125-39.960938v-108.007812zm-11.722656-39.996094-60.949219.03125v-114.972656l60.949219.003906zm-100.949219.050781-65 .03125v-115.03125l65 .003906zm205.679688-90.050781 47.4375 116.667969c3.464843 8.519531 5.796874 17.347656 6.980468 26.375h-60.492187c-11.167969 0-21.671875-4.351563-29.570313-12.25l-29.085937-29.085938v-126.644531l27.679687.003906c16.347656 0 30.890625 9.789063 37.050782 24.933594zm-376.679688 15.046875c0-10.683594 4.160156-20.730469 11.71875-28.285156 7.554688-7.554688 17.597656-11.714844 28.28125-11.714844h.003906l25.996094.003906v115.054688l-66 .03125zm332 263.097656c11.027344 0 20 8.96875 20 20 0 11.027344-8.972656 20-20 20s-20-8.972656-20-20c0-11.03125 8.972656-20 20-20zm-237 0c11.027344 0 20 8.96875 20 20 0 11.027344-8.972656 20-20 20s-20-8.972656-20-20c0-11.03125 8.972656-20 20-20zm0 0"/></svg>'
                } else {
                }
                
                if(this.meMarker) this.map.removeObject(this.meMarker)

                this.meMarker = this.map.addObject(new H.map.Marker({
                    lat: this.currentPosition.latitude,
                    lng: this.currentPosition.longitude,
                }, {icon: new H.map.Icon(icon)}
                ));
                //отправляю данные водителя который на маршруте
                //или рисую маршрутки
                if(this.driverOnRoute && this.userType) {
                        axios.post('/api/send-user-coords', {
                        lat: this.currentPosition.latitude,
                        lng: this.currentPosition.longitude,
                        time: new Date().getTime()
                    }).then(({data}) => {

                        console.log(data)
                    })
                } else {
                    // console.log(this.mapCoords)

                    this.mapCoords.leftUp.geoCoords = this.map.screenToGeo(this.mapCoords.leftUp.x, 
                        this.mapCoords.leftUp.y);
                    this.mapCoords.rightDown.geoCoords = this.map.screenToGeo(this.mapCoords.rightDown.x, 
                        this.mapCoords.rightDown.y);

                    // console.log(this.mapCoords)
                    axios.post('/api/get-transports/', {
                        filter: this.search,
                        leftUpLat: this.mapCoords.leftUp.geoCoords.lat,
                        leftUpLng: this.mapCoords.leftUp.geoCoords.lng,
                        rightDownLat: this.mapCoords.rightDown.geoCoords.lat,
                        rightDownLNG: this.mapCoords.rightDown.geoCoords.lng,
                    }).then((data) => {
                        //здесь рисовать машины
                        if(this.transport.length) this.map.removeObjects(this.transport)
                        // data.forEach((el) => {
                        //     let dot = new H.map.Marker({lat: el.lat,lng: el.lng,},
                        //         { icon: new H.map.Icon('/image/bus_othres.svg')  })
                        //     this.transport.push(dot)
                        //     this.map.addObject(dot)
                        // })
                    })
                }
            },
            checkAuth() {
                axios.get('/api/check-auth').then(({data}) => {
                    this.userType = data.status
                    // console.log(data.status)
                    if(this.userType) {
                        console.log('Хочу маршрут')
                        this.addRouteShapeToMap()
                        axios.get('/api/get-path').then(({data}) => {
                            console.log('Маршрут получен')
                            console.log(data.result)
                            data.result.forEach((el) => {
                                this.map.addObject(new H.map.Marker({
                                    lat: el.lat,
                                    lng: el.lng,
                                },
                                { icon: new H.map.Icon('<svg height="32px" fill="#B13C23" viewBox="0 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg"><path d="m432 0h-352c-44.113281 0-80 35.886719-80 80v20c0 11.046875 8.953125 20 20 20h20v372c0 11.046875 8.953125 20 20 20s20-8.953125 20-20v-60h352v60c0 11.046875 8.953125 20 20 20s20-8.953125 20-20v-80c0-11.046875-8.953125-20-20-20h-99v-40h20c11.046875 0 20-8.953125 20-20v-80c0-11.046875-8.953125-20-20-20h-233c-11.046875 0-20 8.953125-20 20v80c0 11.046875 8.953125 20 20 20h20v40h-80v-272h352v192c0 11.046875 8.953125 20 20 20s20-8.953125 20-20v-192h20c11.046875 0 20-8.953125 20-20v-20c0-44.113281-35.886719-80-80-80zm-272 272h193v40h-193zm40 80h113v40h-113zm-160-272c0-22.054688 17.945312-40 40-40h352c22.054688 0 40 17.945312 40 40zm0 0"/></svg>')  }
                                ));
                            })

                            this.driverRoute = data.path
                        })
                    }
                }).catch((error) => {
                    console.log(error)
                })

            },
            addRouteShapeToMap(route){
                var router = this.platform.getRoutingService(),
                    routeRequestParams = {
                    mode: 'fastest;car',
                    representation: 'display',
                    routeattributes : 'waypoints,summary,shape,legs',
                    maneuverattributes: 'direction,action',
                    waypoint0: '48.5700,44.4361', // Brandenburg Gate
                    waypoint1: '48.7846,44.5652'  // Friedrichstraße Railway Station
                    };


                router.calculateRoute(
                    routeRequestParams,
                    this.onRouteSuccess,
                    (error) => { console.log(error)}
                );
            },
            onRouteSuccess(result) {
                console.log(result)
                var route = result.response.route[0];

                var lineString = new H.geo.LineString(),
                    routeShape = route.shape,
                    polyline;

                routeShape.forEach(function(point) {
                    var parts = point.split(',');
                    lineString.pushLatLngAlt(parts[0], parts[1]);
                });

                polyline = new H.map.Polyline(lineString, {
                    style: {
                    lineWidth: 4,
                    strokeColor: 'rgba(0, 128, 255, 0.7)'
                    }
                });
                // Add the polyline to the map
                this.map.addObject(polyline);
                // And zoom to its bounding rectangle
                this.map.setViewBounds(polyline.getBounds(), true);
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
