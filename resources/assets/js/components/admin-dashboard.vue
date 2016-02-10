<style scoped>
.topic--in-list {
    cursor: pointer;
    margin-bottom: 0.25em;
}
    .topic--in-list:hover {
        transform: rotate(-0.5deg) scale(1.04);
    }

    .topic--in-list.panel-default:hover > .panel-heading {
        background: #fff;
    }
    .topic--in-list.panel-primary:hover > .panel-heading {
        background: #69A7DC;
    }
</style>

<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <h2>Episode Planner</h2>
            <div class="episode-planner" v-show="creating">
                <p v-show="acceptedTopics.length == 0">No accepted topics.</p>
                <p v-show="acceptedTopics.lenght != 0">Pick accepted topics to cover this episode:</p>
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Scheduled topics</h3>
                        <div
                            v-for="topic in acceptedTopicsSelected"
                            @click="toggleTopic(topic)"
                            class="panel topic topic--in-list panel-primary"
                            >
                            <div class="panel-heading"><h3 class="topic__title">{{ topic.title }}</h3></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h3>Available topics</h3>
                        <div class="form-inline">
                            <input class="form-control" type="text" placeholder="Add topic">
                            <a class="btn btn-primary" @click="addTopic">Add</a>
                        </div>
                        <div style="height: 20em; overflow-y: scroll; margin-top: 1em; padding-left: 0.5em; padding-right: 0.5em;">
                            <div
                                v-for="topic in acceptedTopicsNotSelected"
                                @click="toggleTopic(topic)"
                                class="panel topic topic--in-list panel-default"
                                >
                                <div class="panel-heading"><h3 class="topic__title">{{ topic.title }}</h3></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-inline" style="margin-top: 1em;">
                <a class="btn btn-primary" @click="startCreating" v-show="!creating">Create new episode</a>
                <input class="form-control" type="text" placeholder="Episode name" v-show="creating">
                <a class="btn btn-primary" @click="finishCreating" v-show="creating">Save episode</a>
                <a class="btn btn-default" @click="stopCreating" v-show="creating">Cancel</a>
            </div>
    
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
        data: function () {
            return {
                selected: [],
                creating: false
            };
        },
        ready: function () {
            // @todo: How do we handle this?
            for (var i = 0, len = this.acceptedTopics.length; i < len; i++) {
                var topic = this.acceptedTopics[i];
                this.selected[topic.id] = false;
            }
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
            },
            finishCreating: function () {
                alert('DO ITTTTT');
            },
            toggleTopic: function (topic) {
                this.selected.$set(topic.id, !this.selected[topic.id]);
            },
            topicIsSelected: function (topic) {
                return !!this.selected[topic.id];
            },
            addTopic: function () {
                alert('to do');
            }
        },
        computed: {
            acceptedTopicsSelected: function () {
                // @todo: Underscore map
                var vm = this;
                return this.acceptedTopics.filter(function (topic) {
                    return vm.topicIsSelected(topic);
                });
            },
            acceptedTopicsNotSelected: function () {
                // @todo: Underscore map
                var vm = this;
                return this.acceptedTopics.filter(function (topic) {
                    return ! vm.topicIsSelected(topic);
                });
            },
        }
    };
</script>
