<template>
    <div id="user-view" class="user-view">
        <here-map :search="searchStr"></here-map>
        <search @search="search"></search> 
    </div>
</template>

<script>
    import HereMap from '../components/HereMap'
    import Search from '../components/Search'

    export default {
        name: 'user-view',
        components: {'here-map': HereMap, 'search': Search},
        data() {
            return {
                searchStr: ""
            }
        },
        methods: {
            search(o) {
                this.searchStr = o
            }
        }
    }
</script>

<style>
    .user-view {
        overflow: hidden;
    }
</style>