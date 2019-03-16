<template>
    <div id="search-container">
        <input type="text" placeholder="Найти транспорт, улицу..."
            v-model="inputText" @keyup="checkInput">
        <img >
    </div>
</template>

<script>
export default {
    data() {
        return {
            typingTimer: null,
            doneTypingInterval: 1000,
            inputText: ""
        }
    },
    methods: {
        checkInput() {
            clearTimeout(this.typingTimer);
            this.typingTimer = setTimeout(this.postInput, this.doneTypingInterval);
        },
        postInput() {
            if(this.inputText != "")
                alert(this.inputText);
        }
    }
}
</script>

<style>
    
</style>



