<style scoped>

</style>

<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <h2>Episode Planner</h2>

            <div v-show="creating">
                <create-episode-form :accepted-topics="acceptedTopics"></create-episode-form>
            </div>

            <div class="form-inline" style="margin-top: 1em;">
                <a class="btn btn-primary pull-right" @click="startCreating" v-show="!creating">
                    Create new episode
                    <svg class="icon icon-plus" style=""><use xlink:href="#icon-plus"></use></svg>
                </a>
            </div>

            <hr style="clear: both;">

            <h2>Janky to-be-improved Episodes List</h2>
            <p v-show="episodes.length == 0">No episodes.</p>
            <div v-for="episode in episodes | orderBy 'number' -1" class="panel panel-default episode episode--in-list">
                <div class="panel-heading"><h3 v-link="{ path: '/episodes/' + episode.number }" class="episode__title" style="cursor: pointer;">{{ episode.number }}. {{ episode.title }}</h3></div>
            </div>

            <hr>

            <suggested-topics></suggested-topics>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                creating: false,
                topicName: '',
            };
        },
        ready: function () {
        },
        props: {
            episodes: {
                sync: true
            },
            acceptedTopics: {
                sync: true
            }
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
