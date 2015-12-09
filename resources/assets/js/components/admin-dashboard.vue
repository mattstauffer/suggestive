<style>
    /* Copied from user-dashboard; consider extracting to .scss */
    .panel-heading {
        padding: 5px 10px;
    }
    .panel-body {
        padding: 10px;
    }
    .topic-title {
        font-size: 1.75rem;
        margin-bottom: 0;
        margin-top: 0;
    }
    /* End copied */

    .vote-count {
        background: #ddd;
        border-radius: 0.35em;
        display: inline-block;
        padding: 0.5em;
        text-align: center;
        width: 4.5rem;
    }
</style>

<template>
    <div>
        <suggest-topic-button verb="Add"></suggest-topic-button>
        <h2>Topics</h2>

        <p v-show="unflaggedTopics.length == 0">No unflagged topics.</p>
        <div v-for="topic in unflaggedTopics" class="row">
            <div class="col-xs-3 col-sm-2" style="text-align: right">
                <div class="vote-count">
                    {{ topic.votes }}
                </div>
            </div>
            <div class="col-xs-9 col-sm-10">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="topic-title">{{ topic.title }}</h3></div>
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
                unflaggedTopics: []
            };
        },
        created: function () {
            this.$http.get('topics?unflagged=true', function (data, status, request) {
                this.unflaggedTopics = data;
            }).error(function (data, status, request) {
                console.log('error', data);
            });
        },
        methods: {
            flagTopic: function (topic, status) {
                var self = this;

                this.$http.patch('topics/' + topic.id, {'status': status}, function (data, status, request) {
                    self.unflaggedTopics.$remove(topic);
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
