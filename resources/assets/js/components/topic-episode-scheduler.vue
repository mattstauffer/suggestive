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
    import Bus from '../bus';
    export default {
        props: ['topic', 'episodes'],
        data: function () {
            return {
                episode: null
            }
        },
        watch: {
            'topic.episode_id': function (val) {
                this.$http.post('episodes/' + val + '/scheduled-topics', { 'topic_id': this.topic.id })
                    .then(() => Bus.$emit('update-topic', this.topic))
                    .catch(err => console.log('error', err));
            }
        }
    };
</script>
