<template>
    <tr>
        <td>{{ event.league.name }}</td>
        <td class="text-center">{{ event.ss }}</td>
        <td>
            <div class="panel-group" id="accordion">
                <div class="panel panel-default" ref="panel">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion"
                               v-bind:href="'#collapse-' + event.id">
                                <div class="inline">
                                    <div class="player-box">
                                        <div class="ball-box" v-if="event.games">
                                            <span class="ball"
                                                  v-if="event.home.name == event.games[0].serve"></span>
                                        </div>
                                        {{ event.home.name }}
                                        <br>
                                        <div class="ball-box" v-if="event.games">
                                            <span class="ball"
                                                  v-if="event.away.name == event.games[0].serve"></span>
                                        </div>
                                        {{ event.away.name }}
                                    </div>
                                </div>
                                <div class="inline" v-if="event.games">
                                    <div class="points-col" v-for="point in event.games[0].points">
                                        <div class="point-box">{{ point[0] }}</div>
                                        <div class="point-box">{{ point[1] }}</div>
                                    </div>
                                </div>
                            </a>
                        </h6>
                    </div>
                    <div v-bind:id="'collapse-' + event.id" class="panel-collapse collapse">
                        <div v-for="(game, index) in event.games"
                             v-bind:class="'panel-body ' + ((game.select == true)?'list-group-item-success':'')">
                            <div class="col inline">
                                <div class="player-box">
                                    <div class="ball-box" v-if="event.games">
                                        <span v-if="event.home.name == game.serve" class="ball"></span>
                                    </div>
                                    {{ event.home.name }}
                                    <br>
                                    <div class="ball-box" v-if="event.games">
                                        <span v-if="event.away.name == game.serve" class="ball"></span>
                                    </div>
                                    {{ event.away.name }}
                                </div>
                            </div>
                            <div class="col inline" v-if="event.games">
                                <div v-for="point in game.points" class="col points-col">
                                    <div class="point-box">{{ point[0] }}</div>
                                    <div class="point-box">{{ point[1] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "TableRow",
        props: ['event', 'audio'],
        data() {
            return {
                // Количество элементов последовательности
                count: 0,
                open: false,
            }
        },
        updated: function () {
            // Получаем количество элементов последовательности
            let count = parseInt($('#collapse-' + this.event.id + '>.panel-body.list-group-item-success').length)
            // Ссылка на панель
            let panel = $(this.$refs.panel);

            // Если количество элементов последовательности изменилось,
            // то оповестить об этом
            if (this.count != count) {
                // Проигрываем звук уведомления
                if (this.audio !== null) {
                    this.audio.play()
                }

                // Добавляем подсветку зеленым цветом - success
                panel.removeClass('panel-default')
                panel.addClass('panel-success')
                panel.removeClass('panel-info')

                // Убираем подсветку подсветку зеленым цветом - success,
                // и добавляем голубую подсветку - info или
                // синию подсветку - primary, в случае, если
                // панель не была еще открыта
                setTimeout(function () {
                    panel.removeClass('panel-default')
                    panel.removeClass('panel-success')

                    // Добавляем голубую подсветку, если панель не была открыта,
                    // иначе синию
                    if (this.open) panel.addClass('panel-info')
                    else panel.addClass('panel-primary')
                }, 5000)
            }

            this.count = count
        },
    }
</script>

<style scoped>

</style>