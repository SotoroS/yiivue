<template>
    <div id="driver-view" class="driver-view">
        <div id="driver-header">
            <p>{{driverFullName}}</p>
            <p><span>{{driverRoute}}</span></p>
        </div>
        <div id="driver-clock">
            <p>{{routeTime}}</p>
        </div>
        <div id="map-filter">
            <here-map :driverOnRoute="driverOnRoute"></here-map>
        </div>
        <div id="drive-button">
            <p>{{buttonText}}</p>
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
        methods: {
            toGoodNumber(num) {
                
            }
        },
        mounted() {
            axios.get('/api/get-user-info').then(({data})=>{
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
        z-index: 2;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        font-size: 1.2em;
        font-family: 'Roboto', sans-serif;
        background-color: white;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    }

    #driver-header p {
        margin: 0;
        padding: 0;
        padding-left: 15px;
        padding-top: 2px;
        color: #949494;
        left: 0;
    }

    #driver-header p span {
        padding-right: 10px;
        padding-top: 2px;
        font-weight: bold;
        font-size: 1.15em;
        color: #2381D8;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.1);
    }

    #driver-clock {
        position: absolute;
        z-index: 2;
        font-size: 4.5em;
        color: white;
        padding-top: 70px;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
    }
</style>