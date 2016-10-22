<style scoped>
    .vote-button, .vote-button__count {
        /* Cheat the column system; come to think of it, let's just make this whole thing Flexbox... */
        margin-right: -15px;
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

    .vote-button__count {
        background: #ddd;
        border-radius: 0 0 0.35em 0.35em;
        display: inline-block;
        margin-top: -0.5em;
        padding-bottom: 0.1em;
        padding-top: 0.5em;
        text-align: center;
        width: 4.5rem;
    }

    .current-filter {
        font-weight: bold;
    }

    .topic-row {
        background: #fff;
        border: 1px solid #ddd;
        padding-bottom: 1em;
        padding-top: 1em;
    }
        .topic-row:not(:last-child) {
            border-bottom: 0;
        }

    .filter-boxes {
        margin-top: 1em;
    }

        .filter-box {
            background: #fcfcfc;
            border: 1px solid #ddd;
            color: #333;
            display: inline-block;
            padding: 0.5em 1em;
        }

        .filter-box:not(:last-child) {
            border-right: 0;
        }

        .filter-box:hover {
            background: #eee;
            color: #000;
            text-decoration: none;
        }

        .filter-box.current-filter {
            background: #fff;
            font-weight: bold;
        }
</style>

<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <suggest-topic-inline></suggest-topic-inline>

            <div class="filter-boxes pull-right">
                <a v-for="filterOption in filters"  v-bind:class="{ 'current-filter': filterOption.filter == filter, 'filter-box': true }" @click="changeFilter(filterOption.filter)" style="cursor: pointer;">
                    {{ filterOption.label }}
                </a>
            </div>

            <h2>Topics</h2>

            <p v-show="filteredTopics.length == 0">No topics matching this filter.</p>
            <div v-for="topic in filteredTopics" class="row topic-row">
                <div class="col-xs-3 col-sm-2 col-md-1" style="text-align: right">
                    <a @click.prevent="voteFor(topic)" v-bind:class="[ 'btn', 'btn-primary', 'vote-button', topic.userVotedFor ? 'disabled' : '' ]">
                        <div class="clearfix">
                            <svg v-show="! topic.userVotedFor" class="icon icon-arrow-up" transition="expand"><use xlink:href="#icon-arrow-up"></use></svg>
                            <svg v-show="topic.userVotedFor" class="icon icon-checkmark" transition="expand"><use xlink:href="#icon-checkmark"></use></svg>
                        </div>
                    </a><br>
                    <div class="vote-button__count">
                        {{ topic.votes }}
                    </div>
                </div>
                <div class="col-xs-9 col-sm-10 col-md-11">
                    <div class="topic topic--in-list">
                        <div class="">
                            <h3 class="topic__title">
                                <a v-link="{ path: '/topics/' + topic.id }">{{ topic.title }}</a>
                                <small class="pull-right" style="text-align: right;">Status: {{ topic.status }}<br>
                                    ({{ topic.commentCount }}
                                    {{ topic.commentCount == 1 ? 'comment' : 'comments' }})
                                </small>
                            </h3>
                        </div>
                        <div class="">
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
        data: function () {
            return {
                filter: 'suggested',
                filters: [
                    {
                        "label": "All topics",
                        "filter": null
                    },
                    {
                        "label": "Suggested",
                        "filter": 'suggested'
                    },
                    {
                        "label": "Accepted",
                        "filter": 'accepted'
                    },
                    {
                        "label": "Rejected",
                        "filter": 'rejected'
                    }
                ]
            };
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
            },
            changeFilter: function (filter) {
                this.filter = filter;
            }
        },
        computed: {
            filteredTopics: function () {
                var vm = this;

                return _.filter(this.topics, function (topic) {
                    if (vm.filter === null) {
                        return true;
                    }

                    return topic.status === vm.filter;
                });
            }
        }
    };
</script>
