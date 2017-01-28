<style scoped>

</style>

<template>
<div>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <div class="form-inline">
                <router-link to="/episodes/create" class="btn btn-primary pull-right">
                    Create new episode
                    <svg class="icon icon-plus" style=""><use xlink:href="#icon-plus"></use></svg>
                </router-link>
            </div>

            <h2>Episodes</h2>

            <p v-show="episodes.length == 0">No episodes.</p>
            <div v-for="episode in episodes" class="panel panel-default episode episode--in-list">
                <div class="panel-heading"><router-link tag="h3" :to="'/episodes/' + episode.number" class="episode__title" style="cursor: pointer;">{{ episode.number }}. {{ episode.title }}</router-link></div>
            </div>

            <hr>

        </div>
    </div>
    <suggested-topics :topics="topics"></suggested-topics>
</div>
</template>

<script>
    export default {
        props: ['episodes', 'topics'],
    };
</script>
