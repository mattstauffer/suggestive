<template>
    <select v-model="episode">
        <option disabled>
            - Schedule topic -
        </option>
        <option v-for="episode in episodes" v-bind:value="episode.id">
            Episode {{ episode.number }}: {{ episode.title }}
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
            'episode': function (val) {
                var data = {
                    'topic_id': val
                };

                this.$http.post('episodes/' + this.episode.id + '/scheduled-topics', data, function (data, status, request) {
                    // this.$dispatch('hey parent components, i just changed the status of topic #' + topic.id);
                }).error(function (data, status, request) {
                    console.log('error', data);
                });
            }
        }
    };
</script>
