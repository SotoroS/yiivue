<template>
    <div class="site-index">
        <router-link to="/about">about</router-link>
        <router-link to="/queue">queue</router-link>
        <div class="input-group">
            <span class="input-group-addon" id="input-count">Начальное количество элементов последовательности</span>
            <input v-model="count" type="number" class="form-control" placeholder="2" aria-describedby="input-count">
        </div>
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                Мониторинг by <a href="https://vk.com/fokin_danil" target="_blank">sotoros</a>
                <span class="pull-right">Время составления - {{ date }}</span>
            </div>
            <table class="table table-condensed table-bordered">
                <thead>
                <tr>
                    <th width="20%">Лига</th>
                    <th class="text-center" width="10%">Счет</th>
                    <th>Подающий | Игроки | Очки</th>
                </tr>
                </thead>
                <tbody>
                <table-row v-for="event in events" v-bind:event="event" v-bind:audio="audio"
                           v-bind:key="event.id"></table-row>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import TableRow from './TableRow'

    export default {
        components: {
            'table-row': TableRow,
        },
        data() {
            return {
                events: [],
                audio: null,
                count: 2,
                date: null,
            }
        },
        mounted: function () {
            this.audio = new Audio('sound.wav')
            axios
                .get('/api/event', {
                    params: {
                        count: this.count - 1
                    }
                })
                .then(response => {
                    this.date = response.data.date
                    this.events = response.data.events
                })
            setInterval(() => {
                axios
                    .get('/api/event', {
                        params: {
                            count: this.count - 1
                        }
                    })
                    .then(response => {
                        this.date = response.data.date
                        this.events = response.data.events
                    })
            }, 5000)
        },
    }
</script>
