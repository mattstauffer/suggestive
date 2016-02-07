<style scoped>
</style>

<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <h2>Episode Planner</h2>
            <p>@todo: Show all accepted topics (and the inline ability to add new ones); allow an easy UI for creating next episode</p>
            <a class="btn btn-primary">Create new episode</a>
            <p>Button will disappear on click, show an input field (for naming it) with an add/cancel button</p>
            <p>show all scheduled topics and the easy ability to move them up into the episode bucket</p>
            <p>Show a quick-add scheduled topic adder so this can be used instead of Trello</p>
            <p>Allow for drag and drop reordering of topics, and a save button at the button</p>

            <h2>Episodes</h2>
            <p v-show="episodes.length == 0">No episodes.</p>
            <div v-for="episode in episodes | orderBy 'number' -1" class="panel panel-default episode episode--in-list">
                <div class="panel-heading"><h3 class="episode__title">{{ episode.number }}. {{ episode.title }}</h3></div>
            </div>

            <h2>Suggested Topics (for review)</h2>
            <p>@todo: Embed a small suggested topics review panel here</p>
            <a v-link="{ path: '/suggested-topics' }" class="btn btn-primary">Review all suggested topics</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            episodes: {
                sync: true
            }
        }
    };
</script>
