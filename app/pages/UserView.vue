<template>
    <div id="user-view" class="user-view">
        <here-map></here-map>
        <search></search> 
    </div>
</template>

<script>
    import HereMap from '../components/HereMap'
    import Search from '../components/Search'

    export default {
        name: 'user-view',
        components: {'here-map': HereMap, 'search': Search}
    }
</script>

<style>

</style>