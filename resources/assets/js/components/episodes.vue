<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <router-link to="/episodes/create" class="btn btn-primary add-button pull-right">
                Add episode
                <svg class="icon icon-plus" style=""><use xlink:href="#icon-plus"></use></svg>
            </router-link>
            <div class="row">
                <h2>Episodes</h2>

                <p v-show="episodes.length == 0">No episodes.</p>
            </div>
            <div v-for="episode in episodes" class="row" style="margin-bottom: 3rem;">
                <div class="col-xs-3 col-sm-1 episode__number--in-list bg-primary">
                    {{ episode.number }}
                </div>
                <div class="col-xs-9 col-sm-11">
                    <div class="panel panel-default episode episode--in-list">
                        <div class="panel-heading"><router-link tag="h3" :to="'/episodes/' + episode.number" class="episode__title" style="cursor: pointer">{{ episode.title }}</router-link></div>
                        <div class="panel-body">
                            <h3 style="margin-top: 0; font-size: 1em; font-weight: bold;">Topics</h3>
                            <ul>
                                <li v-for="topic in topicsForEpisode(episode)">
                                    {{ topic.title }}
                                </li>
                            </ul>
                        </div>
                        <span @click="deleteEpisode(episode)" class="episode__delete--in-list">x</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Bus from '../bus';
    export default {
        props: ['episodes', 'topics'],
        methods: {
            deleteEpisode: function (episode) {
                if (! confirm("Are you sure?")) {
                    return;
                }

                this.$http.delete('episodes/' + episode.id)
                .then(response => {
                    console.log('BALETED');
                    Bus.$emit('delete-episode', episode);
                }).catch(err => {
                    console.log('error', err);
                });
            },
            topicsForEpisode(episode){
                return this.topics.filter(t => {
                    return t.episode_id == episode.id;
                });
            }
        }
    };
</script>
