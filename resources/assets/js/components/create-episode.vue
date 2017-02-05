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
                            v-for="topic in selectedTopics"
                            @click="toggleTopic(topic)"
                            class="panel topic topic--in-list panel-primary"
                            >
                            <div class="panel-heading"><h3 class="topic__title">{{ topic.title }}</h3></div>
                        </div>
                        <p v-show="selectedTopics.length == 0">Select a few topics to cover!</p>
                    </div>
                    <div class="col-sm-6">
                        <h3 class="topic-selector__column-head">Available topics</h3>
                        <div class="topic-selector__available-topics">
                            <div
                                v-for="topic in availableTopics"
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
    import Bus from '../bus';
    import Topics from '../topics';

    export default {
        props: ['episodes', 'topics'],
        data: function () {
            return {
                selected: [],
                title: '',
                number: '',
                topicName: ''
            };
        },
        mounted() {
            this.$refs.episodeTitleInput.focus();
        },
        methods: {
            createEpisode: function () {
                this.$http.post('episodes', { title: this.title, number: this.number })
                    .then(({data}) => {
                        this.title = '';
                        this.number = '';
                        Bus.$emit('add-episode', data);
                        this.assignTopic(data.id, this.selectedTopics);
                        this.$router.push('/episodes');
                    });
            },
            assignTopic(episodeId, topics){
                let topicIds = topics.map(t => t.id );

                this.$http.post('episodes/' + episodeId + '/scheduled-topics', {topic_id: topicIds})
                    .then(() => {
                        topics.map(topic => {
                            topic.episode_id = episodeId;
                            return topic;
                        }).forEach(topic => Bus.$emit('update-topic', topic));
                    }).catch(err => console.log('error', err));
            },
            toggleTopic: function (topic) {
                Vue.set(this.selected, topic.id, !this.selected[topic.id]);
            },
            topicIsSelected: function (topic) {
                return !!this.selected[topic.id];
            },
            addTopic: function () {
                Topics.add({ title: this.topicName })
                    .then(({data}) => {
                        this.topicName = '';
                        Vue.set(this.selected, data.id, !this.selected[data.id]);
                    });
            }
        },
        computed: {
            selectedTopics: function () {
                return this.acceptedTopics.filter(topic => this.topicIsSelected(topic));
            },
            availableTopics: function () {
                return this.acceptedTopics.filter(topic => ! this.topicIsSelected(topic));
            },
            acceptedTopics(){
                return this.topics.filter(topic => topic.status === 'accepted' && !topic.episode_id);
            }
        }
    };
</script>
