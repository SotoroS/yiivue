<template>
    <div id="driver-view" class="driver-view">
        <div id="driver-header">
            <p>{driverFullName}</p>
            <p>{driverRoute}</p>
        </div>
        <div id="driver-clock">
            <p>{routeTime}</p>
        </div>
        <div id="map-filter">
            <here-map></here-map>
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
                driverFirstName: "",
                driverSecondName: "",
                driverRoute: "",
                buttonText: "",
                routeTimeMinutes: 0,
                routeTimeSeconds: 0
            }
        },
        computed: {
            driverFullName() {
                return this.driverFirstName + " " + this.driverSecondName;
            },
            routeTime() {
                return this.routeTimeMinutes + ":" + this.routeTimeSeconds;
            }
        }
    }
</script>

<style>

</style>