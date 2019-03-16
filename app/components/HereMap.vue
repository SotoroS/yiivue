<template>
    <div id="here-map"></div>
</template>

<script>
    export default {
        name: 'here-map',
        data() {
            return {
                currentPosition: null,
            }
        },
        mounted() {
            const vh = this

            setInterval(() => {
                navigator.geolocation.getCurrentPosition((position) => {
                            vh.currentPosition = position.coords
                    }
                )
            })

            var platform = new H.service.Platform({
                'app_id': '6qi8a5qmtadTCJfJe5lJ',
                'app_code': 'qutMUwqcoVmSFK8--6AWZA'
            });

            



        },
    }
</script>

<style>

</style>
