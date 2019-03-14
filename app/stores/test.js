const Test = {
    state: {
        answer: null,
    },
    getters: {
        getAnswer: state => state.answer
    },
    mutations: {
        ADD_ANSWER: (state, data) => {
            state.answer = data
        }
    },
    actions: {
        socket_chat_message(context, data) {
            context.commit('ADD_ANSWER', data)
        }
    },
}

export default Test 