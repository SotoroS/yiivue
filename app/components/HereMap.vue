<template>
    <div id="here-map">sadasd</div>
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

            

        },
    }
</script>

<style>

</style>
