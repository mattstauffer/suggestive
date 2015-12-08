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
    .vote-button {
        height: 4rem;
        overflow: hidden;
        position: relative;
        transition: all 0.5s ease;
        width: 4.5rem;
    }
    .vote-button.disabled {
        background: #bbb;
        border-color: #bbb;
        opacity: 1;
    }
    .vote-button .icon {
        height: 1.5em;
        left: 1.1rem;
        position: absolute;
        top: 0.6rem;
        width: 1.5em;
    }

    .vote-count {
        background: #ddd;
        border-radius: 0 0 0.35em 0.35em;
        display: inline-block;
        margin-top: -0.5em;
        padding-bottom: 0.1em;
        padding-top: 0.5em;
        text-align: center;
        width: 4.5rem;
    }
</style>

<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <a v-link="{ path: '/add-topic' }" class="btn btn-primary add-button pull-right">
                <svg class="icon icon-plus" style=""><use xlink:href="#icon-plus"></use></svg>
                Add topic</a>
            <h2>Topics</h2>

            <p v-show="topics.length == 0">No topics yet.</p>
            <div v-for="topic in topics" class="row">
                <div class="col-xs-3 col-sm-2" style="text-align: right">
                    <a @click.prevent="voteFor(topic)" v-bind:class="[ 'btn', 'btn-primary', 'vote-button', topic.userVotedFor ? 'disabled' : '' ]">
                        <div class="clearfix">
                            <svg v-show="! topic.userVotedFor" class="icon icon-arrow-up" transition="expand"><use xlink:href="#icon-arrow-up"></use></svg>
                            <svg v-show="topic.userVotedFor" class="icon icon-checkmark" transition="expand"><use xlink:href="#icon-checkmark"></use></svg>
                        </div>
                    </a><br>
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

                    topic.userVotedFor = true;
                }).error(function (data, status, request) {
                    console.log('error', data, status);
                });
            }
        }
    };
</script>
