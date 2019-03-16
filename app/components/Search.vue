<template>
  <div id="search-container">
    <input
      type="text"
      placeholder="Найти транспорт, улицу..."
      v-model="inputText"
      @keyup="checkInput"
    >
    <img src="/images/search.svg">
  </div>
</template>

<script>
export default {
  data() {
    return {
      typingTimer: null,
      doneTypingInterval: 1000,
      inputText: ""
    };
  },
  methods: {
    checkInput() {
      clearTimeout(this.typingTimer);
      this.typingTimer = setTimeout(this.postInput, this.doneTypingInterval);
    },
    postInput() {
      if (this.inputText != "") alert(this.inputText);
    }
  }
};
</script>

<style>
#search-container {
  height: 45px;
  width: -moz-available;
  width: -webkit-fill-available;
  display: flex;
  align-items: center;
  position: absolute;
  z-index: 5;
  bottom: 0;
  margin: 5px;
  margin-top: 0;
  background-color: white;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
  border-radius: 10px;
  box-sizing: border-box;
}

input {
    height: 100%;
    width: 100%;
    margin: 0;
    padding: 0;
    padding-top: 2px;
    padding-left: 15px;
    padding-right: 45px;
    text-align: left;
    border: solid 1px #fff;
    border-radius: 10px;
    font-size: 1em;
    font-family: 'Roboto', sans-serif;
}

input:focus {
    border: none;
}

input::placeholder {
    color: #C0C0C0;
    padding-left: 15px;
}

img {
    height: 23px;
    width: 23px;
    position: absolute;
    z-index: 6;
    right: 10px;
    opacity: 0.55;
}
</style>



