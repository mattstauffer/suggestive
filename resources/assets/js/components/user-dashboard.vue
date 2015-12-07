<style>
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
</style>

<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <a v-link="{ path: '/add-topic' }" class="btn btn-primary pull-right">Add topic</a>
            <h2>Topics</h2>

            <div v-for="topic in topics" class="row">
                <div class="col-xs-2 col-sm-1">
                    <a @click.prevent="voteFor(topic)" class="btn btn-primary">UP</a>
                </div>
                <div class="col-xs-10 col-sm-11">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h3 class="topic-title">{{ topic.title }} ({{ topic.votes }})</h3></div>
                        <div class="panel-body">
                            Abc
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            topics: {
                sync: true
            }
        },
        ready: function () {
        },
        methods: {
            voteFor: function (topic) {
                this.$http.post('topics/' + topic.id + '/votes', [], function (data, status, request) {
                    // New vote
                    if (status == 200) {
                        topic.votes++;
                    }
                }).error(function (data, status, request) {
                    console.log('error');
                });
            }
        }
    };
</script>
