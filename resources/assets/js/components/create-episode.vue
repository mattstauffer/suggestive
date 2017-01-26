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

.topic-selector {
    background: #fafafa;
    border: 4px solid #ddd;
    border-radius: 0.25em;
    padding: 1em;
}
    .topic-selector__column-head {
        border-bottom: 1px solid #ddd;
        font-size: 1.5em;
        margin-top: 0;
        padding-bottom: 0.25em;
    }

    .topic-selector__available-topics {
        height: 20em;
        overflow-y: scroll;
        margin-left: -0.5em;
        margin-right: -0.5em;
        margin-bottom: 1em;
        margin-top: 1em;
        padding: 0.5em;
    }
</style>

<template>
    <div>
        <form @submit.prevent="createEpisode">
        <div class="row">
            <div class="col-md-8 col-md-push-2">
                <h2>Create an Episode</h2>

                <div class="form-group">
                    <label>Title</label>
                    <input type="text" v-model="title" class="form-control" length="255" autofocus ref="episodeTitleInput" required placeholder="The one about hats">
                </div>

                <div class="form-group">
                    <label>Number</label>
                    <input type="number" v-model="number" class="form-control" length="5" required placeholder="42">
                </div>

                <p v-show="acceptedTopics.length == 0">No accepted topics.</p>
                <p v-show="acceptedTopics.length != 0">Pick accepted topics to cover this episode:</p>

                <div class="row topic-selector">
                    <div class="col-sm-6">
                        <h3 class="topic-selector__column-head">Scheduled topics</h3>
                        <div
                            v-for="topic in acceptedTopicsSelected"
                            @click="toggleTopic(topic)"
                            class="panel topic topic--in-list panel-primary"
                            >
                            <div class="panel-heading"><h3 class="topic__title">{{ topic.title }}</h3></div>
                        </div>
                        <p v-show="acceptedTopicsSelected.length == 0">Select a few topics to cover!</p>
                    </div>
                    <div class="col-sm-6">
                        <h3 class="topic-selector__column-head">Available topics</h3>
                        <div class="topic-selector__available-topics">
                            <div
                                v-for="topic in acceptedTopicsNotSelected"
                                @click="toggleTopic(topic)"
                                class="panel topic topic--in-list panel-default"
                                >
                                <div class="panel-heading"><h3 class="topic__title">{{ topic.title }}</h3></div>
                            </div>
                        </div>
                        <div class="form-inline">
                            <input v-model="topicName" class="form-control" type="text" placeholder="Add topic">
                            <a class="btn btn-primary" @click="addTopic">Add</a>
                        </div>
                    </div>
                </div>

                <p>NEXT STEPS:<br><br>1) Make save new episode actually work.<br>2) Allow for drag and drop re-ordering of topic list on the new episode panel.<br>3) individual routes for each episode, where you can edit/delete/reorder and change topics/etc. </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-push-2">
                <div class="form-inline">
                    <input type="submit" class="btn btn-primary" value="Create Episode">
                </div>
            </div>
        </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['episodes'],
        data: function () {
            return {
                selected: [],
                title: '',
                number: '',
                topicName: '',
                acceptedTopics: []
            };
        },
        ready: function () {
            this.$http.get('topics?status=accepted', function (data, status, request) {
                this.acceptedTopics = data;

                // @todo: How do we handle this?
                for (var i = 0, len = this.acceptedTopics.length; i < len; i++) {
                    var topic = this.acceptedTopics[i];
                    this.selected[topic.id] = false;
                }
            }).catch(function (data, status, request) {
                console.log('error', data);
            });

            this.$refs.episodeTitleInput.focus();
        },
        methods: {
            createEpisode: function () {
                var vm = this;

                this.$http.post('episodes', { title: this.title, number: this.number })
                .then(response => {
                    vm.title = '';
                    vm.number = '';

                    vm.episodes.push(response.data);

                    vm.$router.push('/episodes');
                });
            },
            toggleTopic: function (topic) {
                this.selected.$set(topic.id, !this.selected[topic.id]);
            },
            topicIsSelected: function (topic) {
                return !!this.selected[topic.id];
            },
            addTopic: function () {
                var vm = this;

                this.$http.post('topics', { title: this.topicName })
                    .then(response => {
                        vm.topicName = '';
                        vm.acceptedTopics.push(response.data);
                        vm.selected.$set(response.data.id, true);
                    });
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
