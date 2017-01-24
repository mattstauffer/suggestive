<template>
    <select v-model="topic.episode_id">
        <option disabled>
            - Schedule topic -
        </option>
        <option v-for="episode in episodes" v-bind:value="episode.id">
            Episode {{ episode.number }}: {{ episode.title }} ({{ episode.id }})
        </option>
    </select>
</template>

<script>
    export default {
        props: {
            topic: {},
            episodes: {},
        },
        data: function () {
            return {
                episode: null
            }
        },
        watch: {
            'topic.episode_id': function (val) {
                var data = {
                    'topic_id': this.topic.id
                };

                this.$http.post('episodes/' + val + '/scheduled-topics', data)
                    .then(response => {
                        // this.$dispatch('hey parent components, i just changed the status of topic #' + topic.id);
                    }).catch(function (data, status, request) {
                        console.log('error', data);
                    });
            }
        }
    };
</script>
