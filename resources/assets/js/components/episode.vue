<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <button v-link="{ path: '/episodes/' }" type="button" class="btn btn-default" aria-label="All Episodes">
                <svg class="icon icon-back" style=""><use xlink:href="#icon-back"></use></svg>
                All Episodes
            </button>

            <h2 class="episode__title">Episode {{ episode.number }}: {{ episode.title }}</h2>
            <h3 style="margin-top: 0; font-size: 1em; font-weight: bold;">Topics</h3>
            <ul>
                <li v-for="topic in episode.topics">
                    {{ topic.title }}
                </li>
            </ul>

            <a @click="deleteEpisode(episode)">Delete this episode</a>
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
        data: function () {
            return {
                episode: {}
            };
        },
        created: function() {
            var vm = this;

            this.$http.get('episodes')
                .then(response => {
                    this.episode = _.find(this.episodes, function (episode) {
                        return episode.number == vm.$route.params.episode_number;;
                    });
                }).catch(function (data, status, request) {
                    console.log('error', request);
                });
        },
        methods: {
            deleteEpisode: function (episode) {
                if (! confirm("Are you sure?")) {
                    return;
                }

                this.$http.delete('episodes/' + episode.id)
                    .then(response => {
                        console.log('BALETED');
                        this.episodes.$remove(episode);
                    }).catch(err => {
                        console.log('error', err);
                    });
            }
        }
    };
</script>
