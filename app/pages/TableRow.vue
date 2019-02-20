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
                count: 0,
            }
        },
        updated: function () {
            let count = parseInt($('#collapse-' + this.event.id + '>.panel-body.list-group-item-success').length)
            let panel = $(this.$refs.panel);

            if (this.count < count) {
                if (this.audio !== null) {
                    this.audio.play()
                }

                panel.removeClass('panel-default')
                panel.addClass('panel-success')
                panel.removeClass('panel-info')

                setTimeout(function () {
                    panel.removeClass('panel-default')
                    panel.removeClass('panel-success')
                    panel.addClass('panel-info')
                }, 5000)
            } else if (this.count > count) {
                panel.addClass('panel-default')
                panel.removeClass('panel-success')
                panel.removeClass('panel-info')
            } else if (this.count > count) {
                panel.removeClass('panel-default')
                panel.removeClass('panel-success')
                panel.addClass('panel-info')
            }

            this.count = count
        },
    }
</script>

<style scoped>

</style>