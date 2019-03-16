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
        <div id="drive-button-wrapper">
            <div id="drive-button" @click="onButtonClick">
                <p :style="{color: textColor}">{{buttonText}}</p>
            </div>
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
                routeTimeSeconds: 0,
                routeInterval: null,
                textColor: '#000'
            }
        },
        computed: {
            routeTime() {
                return this.toGoodNumber(this.routeTimeMinutes) + ":" + this.toGoodNumber(this.routeTimeSeconds);
            }
        },
        methods: {
            toGoodNumber(num) {
                num = String(num);
                if(num.length < 2) num = "0" + num;
                return num;
            },
            onButtonClick() {
                this.driverOnRoute = !this.driverOnRoute;

                this.changeButtonText();

                let elButton = document.getElementById('drive-button');
                let rtp = document.getElementById('driver-clock');
                let map = document.getElementById('map-filter');

                if(this.driverOnRoute) {
                    let contWidth = document.getElementById('drive-button-wrapper').offsetWidth;
                    elButton.style.marginBottom = 5 + "px";
                    elButton.style.width = contWidth - 10 + "px";
                    elButton.style.marginLeft = 5 + "px";
                    elButton.style.marginRight = 5 + "px";

                    rtp.style.textShadow = "0 3px 6px rgba(0,0,0,0.55)";

                    map.style.filter = "contrast(1)";
                    map.style.pointerEvents = "unset";

                    this.routeTimeSeconds = 0;
                    this.routeTimeMinutes = 0;

                    this.routeInterval = setInterval(() => {
                        this.routeTimeSeconds += 1;
                        if(this.routeTimeSeconds > 59){
                            this.routeTimeSeconds = 0;
                            this.routeTimeMinutes += 1;
                        }
                    }, 1000);
                } else {
                    elButton.style.marginBottom = 115 + "px";
                    elButton.style.width = "255px";
                    elButton.style.marginLeft = "0";
                    elButton.style.marginRight = "0";

                    rtp.style.textShadow = "";

                    map.style.filter = "contrast(0.5)";
                    map.style.pointerEvents = "none";

                    clearInterval(this.routeInterval);
                }
            },
            changeButtonText() {
                if(this.driverOnRoute){ this.buttonText = "Закончить движение"; this.textColor = "#B13C23"}
                else { this.buttonText = "Начать движение"; this.textColor = "#23B164"}
            }
        },
        mounted() {
            axios.get('/api/get-user-info').then(({data})=>{
                this.driverFullName = data.fullname;
                this.driverRoute = data.trackNumber;
            });

            this.changeButtonText();
        }
    }
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
        width: 100%;
        position: absolute;
        z-index: 2;
        font-size: 4.5em;
        color: white;
        text-align: center;
        padding-top: 70px;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);

        transition: 0.4s ease-in-out;
    }

    #driver-clock p {
        padding: 0;
        margin: 0;
    }

    #map-filter {
        filter: contrast(0.5);
    }

    #drive-button-wrapper {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #drive-button {
        height: 55px;
        width: 255px;
        position: absolute;
        bottom: 0;
        margin-bottom: 115px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5em;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);

        transition: 0.4s ease-in-out;
    }

    #drive-button p {
        padding: 0;
        margin: 0;
    }

    #drive-button:hover {
        cursor: pointer;
    }
</style>