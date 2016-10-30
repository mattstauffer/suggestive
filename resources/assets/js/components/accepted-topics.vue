<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <suggest-topic-button verb="Add"></suggest-topic-button>
            <h2>Accepted Topics</h2>

            <p v-show="acceptedTopics.length == 0">No accepted topics.</p>
            <div v-for="topic in acceptedTopics" class="row">

                <div class="col-xs-9 col-sm-10">
                    <div class="panel panel-default topic topic--in-list">
                        <div class="panel-heading"><h3 class="topic__title">{{ topic.title }}</h3></div>
                        <div class="panel-body">
                            {{ topic.description }}
                        </div>
                        <div class="panel-footer">
                            <div class="btn-group">
                                Covered/will cover in episode:
                                <topic-episode-scheduler :topic="topic" :episodes="episodes"></topic-episode-scheduler>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            episodes: {
                sync: true
            }
        },
        data: function () {
            return {
                acceptedTopics: []
            };
        },
        created: function () {
            this.$http.get('topics?status=accepted', function (data, status, request) {
                this.acceptedTopics = data;
            }).error(function (data, status, request) {
                console.log('error', data);
            });
        },
        components: {
            'topic-episode-scheduler': require('./topic-episode-scheduler.vue')
        }
    };
</script>
