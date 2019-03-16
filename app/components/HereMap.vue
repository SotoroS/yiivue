<template>
    <div id="here-map">sadasd</div>
</template>

<script>
    export default {
        name: 'here-map',
        mounted() {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    alert('Последний раз вас засекали здесь: ' +
                        position.coords.latitude + ", " + position.coords.longitude);
                }
            );
        },
    }
</script>

<style>

</style>
