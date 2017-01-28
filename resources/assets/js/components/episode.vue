<template>
    <div class="row" v-if="episode">
        <div class="col-md-8 col-md-push-2">
            <router-link to="/episodes" type="button" class="btn btn-default" aria-label="All Episodes">
                <svg class="icon icon-back" style=""><use xlink:href="#icon-back"></use></svg>
                All Episodes
            </router-link>

            <h2 class="episode__title">Episode {{ episode.number }}: {{ episode.title }}</h2>
            <h3 style="margin-top: 0; font-size: 1em; font-weight: bold;">Topics</h3>
            <ul>
                <li v-for="topic in topicsForEpisode()">
                    {{ topic.title }}
                </li>
            </ul>

            <a @click="deleteEpisode(episode)">Delete this episode</a>
        </div>
    </div>
</template>

<script>
    import Bus from '../bus';
    export default {
        props: ['episodes', 'topics'],
        computed: {
            episode(){
                return this.episodes.find(e => {
                    return e.number.toString() === this.$route.params.episode_number;
                });
            }
        },
        methods: {
            deleteEpisode: function (episode) {
                if (! confirm("Are you sure?")) return;

                this.$http.delete('episodes/' + episode.id)
                    .then(response => {
                        Bus.$emit('delete-episode', episode);
                        this.$router.push("/episodes");
                    }).catch(err => console.log('error', err));
            },
            topicsForEpisode(){
                return this.topics.filter(t => t.episode_id == this.episode.id);
            }
        }
    };
</script>
