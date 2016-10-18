<template>
    <div>
        <div class="row">
            <a v-link="{ path: '/episodes/' }">&lt;- Back</a>

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

            this.$http.get('episodes', function (data, status, request) {
                this.episode = _.find(this.episodes, function (episode) {
                    return episode.number == vm.$route.params.episode_number;;
                });
            }).error(function (data, status, request) {
                console.log('error', request);
            });
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
