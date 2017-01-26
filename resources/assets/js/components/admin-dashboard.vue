<style scoped>

</style>

<template>
<div>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <div class="form-inline">
                <a v-link="{ path: '/episodes/create' }" class="btn btn-primary pull-right">
                    Create new episode
                    <svg class="icon icon-plus" style=""><use xlink:href="#icon-plus"></use></svg>
                </a>
            </div>

            <h2>Episodes</h2>

            <p v-show="episodes.length == 0">No episodes.</p>
            <div v-for="episode in episodes" class="panel panel-default episode episode--in-list">
                <div class="panel-heading"><h3 v-link="{ path: '/episodes/' + episode.number }" class="episode__title" style="cursor: pointer;">{{ episode.number }}. {{ episode.title }}</h3></div>
            </div>

            <hr>

        </div>
    </div>
    <suggested-topics></suggested-topics>
</div>
</template>

<script>
    export default {
        props: {
            episodes: {
                sync: true
            },
        },
        methods: {
            startCreating: function () {
                this.creating = true;
            },
            stopCreating: function () {
                this.creating = false;
            }
        }
    };
</script>
