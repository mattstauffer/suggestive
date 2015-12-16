<template>
    <div>
        <suggest-topic-button verb="Add"></suggest-topic-button>
        <h2>Suggested Topics</h2>

        <p v-show="suggestedTopics.length == 0">No suggested topics.</p>
        <div v-for="topic in suggestedTopics" class="row">
            <div class="col-xs-3 col-sm-2" style="text-align: right">
                <div class="vote-count">
                    {{ topic.votes }}
                </div>
            </div>
            <div class="col-xs-9 col-sm-10">
                <div class="panel panel-default topic topic--in-list">
                    <div class="panel-heading"><h3 class="topic__title">{{ topic.title }}</h3></div>
                    <div class="panel-body">
                        {{ topic.description }}
                    </div>
                </div>
                <div class="btn-group">
                    <a @click.prevent="acceptTopic(topic)" class="btn btn-sm btn-primary">Accept</a>
                    <a @click.prevent="rejectTopic(topic)" class="btn btn-sm btn-danger">Reject</a>
                    <a @click.prevent="markTopicAsDuplicate(topic)" class="btn btn-sm btn-info">Mark as duplicate</a>
                </div>
                <br><br>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                suggestedTopics: []
            };
        },
        created: function () {
            this.$http.get('topics?unflagged=true', function (data, status, request) {
                this.suggestedTopics = data;
            }).error(function (data, status, request) {
                console.log('error', data);
            });
        },
        methods: {
            flagTopic: function (topic, status) {
                var self = this;

                this.$http.patch('topics/' + topic.id, {'status': status}, function (data, status, request) {
                    self.suggestedTopics.$remove(topic);
                }).error(function (data, status, request) {
                    console.log('error', data);
                });
            },
            acceptTopic: function (topic) {
                this.flagTopic(topic, 'accepted');
            },
            rejectTopic: function (topic) {
                this.flagTopic(topic, 'rejected');
            },
            markTopicAsDuplicate: function (topic) {
                this.flagTopic(topic, 'duplicate');
            },
        }
    };
</script>
