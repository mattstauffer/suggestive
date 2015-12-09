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
        <h2>Topics</h2>

        <p v-show="unflaggedTopics.length == 0">No unflagged topics.</p>
        <div v-for="topic in unflaggedTopics" class="row">
            <div class="col-xs-3 col-sm-2" style="text-align: right">
                <div class="vote-count">
                    {{ topic.votes }}<br>
                    <a @click.prevent="acceptTopic(topic)">Accept</a><br>
                    <a @click.prevent="rejectTopic(topic)">Reject</a><br>
                    <a @click.prevent="markTopicAsDuplicate(topic)">Mark as duplicate</a><br>
                </div>
            </div>
            <div class="col-xs-9 col-sm-10">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="topic-title">{{ topic.title }}</h3></div>
                    <div class="panel-body">
                        {{ topic.description }}
                    </div>
                </div>
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
            acceptTopic: function (topic) {
                this.$http.patch('topics/' + topic.id, {'status': 'accepted'}, function (data, status, request) {
                    console.log('success!');
                }).error(function (data, status, request) {
                    console.log('error', data);
                });
            }
        }
    };
</script>
