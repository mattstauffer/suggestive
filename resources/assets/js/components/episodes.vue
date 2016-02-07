<template>
    <div>
        <a v-link="{ path: '/episodes/create' }" class="btn btn-primary add-button pull-right">
            Add episode
            <svg class="icon icon-plus" style=""><use xlink:href="#icon-plus"></use></svg>
        </a>
        <h2>Episodes</h2>

        <p v-show="episodes.length == 0">No episodes.</p>
        <div v-for="episode in episodes | orderBy 'number' -1" class="row" style="margin-bottom: 3rem;">
            <div class="col-xs-3 col-sm-2 episode__number--in-list bg-primary" style="text-align: right; border-radius: 0.25em;">
                {{ episode.number }}
            </div>
            <div class="col-xs-9 col-sm-10">
                <div class="panel panel-default episode episode--in-list">
                    <div class="panel-heading"><h3 class="episode__title">{{ episode.title }}</h3></div>
                    <span @click="deleteEpisode(episode)" class="episode__delete--in-list">x</span>
                </div>
            </div>
            <div class="col-xs-9 col-sm-10 col-xs-push-3 col-sm-push-2">
                <h3 style="margin-top: 0; font-size: 1em; font-weight: bold;">Topics</h3>
                <ul>
                    <li v-for="topic in episode.topics">
                        {{ topic.title }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            episodes: {
                sync: true
            }
        },
        methods: {
            deleteEpisode: function (episode) {
                if (! confirm("Are you sure?")) {
                    return;
                }

                this.$http.delete('episodes/' + episode.id, function (data, status, request) {
                    console.log('BALETED');
                    this.episodes.$remove(episode)
                }).error(function (data, status, request) {
                    console.log('error', data);
                });
            }
        }
    };
</script>
