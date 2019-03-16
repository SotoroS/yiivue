<template>
    <div id="search-container">
        <input type="text" placeholder="Найти транспорт, улицу..."
            >
        <img >
    </div>
</template>

<script>
export default {
    data() {
        return {
            typingTimer: null,
            doneTypingInterval: 5000
        }
    },
    methods: {
        postInput() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneTypingInterval, typingTimer);
        }
    }
}
</script>

<style lang="sass" scoped>

</style>



