<template>
    <div id="driver-view" class="driver-view">
        <div id="driver-header">
            <p>{driverFullName}</p>
            <p><span>{driverRoute}</span></p>
        </div>
        <div id="driver-clock">
            <p>{routeTime}</p>
        </div>
        <div id="map-filter">
            <here-map :driverOnRoute="driverOnRoute"></here-map>
        </div>
        <div id="drive-button">
            <p>{buttonText}</p>
        </div>
    </div>
</template>

<script>
    import HereMap from '../components/HereMap'

    export default {
        name: 'driver-view',
        components: {'here-map': HereMap},
        data(){
            return {
                driverFullName: "",
                driverRoute: "",
                buttonText: "",
                driverOnRoute: false,
                routeTimeMinutes: 0,
                routeTimeSeconds: 0
            }
        },
        computed: {
            routeTime() {
                return this.routeTimeMinutes + ":" + this.routeTimeSeconds;
            }
        },
        mounted() {
            axios.get('/api/get-user-info').then((data)=>{
                console.log(data);
                this.driverFullName = data.fullname;
                this.driverRoute = data.trackNumber;
            });
        }
    }/*get-user-info*/
</script>

<style>
    #driver-header {
        height: 50px;
        width: 100%;
        position: fixed;
        top: 0;
        font-size: 1em;
        font-family: 'Roboto', sans-serif;
    }

    
</style>