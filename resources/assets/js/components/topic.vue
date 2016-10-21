<template>
    <div class="col-md-8 col-md-push-2" v-cloak>
        <h2>Viewing Topic</h2>

        <div class="row">
            <div class="col-xs-3 col-sm-2" style="text-align: right">
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
            <div class="col-xs-9 col-sm-10">
                <div class="panel panel-default topic topic--in-list">
                    <div class="panel-heading">
                        <h3 class="topic__title">
                            <a v-link="{ path: '/topics/' + topic.id }">{{ topic.title }}</a>
                        </h3>
                    </div>
                    <div class="panel-body">
                        {{ topic.description }}
                    </div>
                </div>
            </div>
            <div class="col-xs-9 col-sm-10" v-if="! loadingComments">
                <h3>Comments</h3>

                <div class="media" v-for="comment in comments">
                    <div class="media-left">
                        <img class="media-object" :src="comment.user.avatar" alt="{{ comment.user.name }}">
                    </div>

                    <div class="media-body">
                        <h4 class="media-heading">
                            {{ comment.user.name }} said,
                            {{ comment.created_at }}
                        </h4>
                        <p>{{ comment.body }}</p>
                    </div>
                </div>

                <p class="text-muted" v-if="comments.length < 1">
                    There are no comments yet. Why not post the first?
                </p>

                <br><br><form method="post" @submit.prevent="postComment">
                    <div class="form-group">
                        <textarea v-model="newComment.body" rows="3" class="form-control" placeholder="Enter a comment"></textarea>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Post Comment">
                </form>
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
                topic: {},
                comments: [],
                loadingComments: true,
                newComment: {}
            };
        },
        created: function() {
            var vm = this;

            this.$http.get('topics', function(data, status, request) {
                this.topic = _.find(this.topics, function (topic) {
                    return topic.id == vm.$route.params.topic_id;
                });

                this.storeComments();
            }).error(function(data, status, request) {
                console.log('error', request);
            });
        },
        methods: {
            storeComments: function() {
                var url = 'topics/' + this.topic.id + '/comments';

                this.$http.get(url, function(data, status, request) {
                    this.comments = data.map(comment => {
                        comment.created_at = moment.utc(comment.created_at.date).fromNow();

                        return comment;
                    });

                    this.loadingComments = false;
                }).error(function(data, status, request) {
                    console.log('error', request);
                });
            },

            postComment: function() {
                var url = 'topics/' + this.topic.id + '/comments';

                this.$http.post(url, this.newComment, function(data, status, request) {
                    data.created_at = moment(data.created_at.date).fromNow();

                    this.comments.push(data);
                    this.newComment = {};
                }).error(function(data, status, request) {
                    if (request.status == 422) {
                        alert('Please fill in all fields.');
                    }

                    console.log('error', request);
                });
            }
        }
    };
</script>

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

    .media-object {
        max-width: 50px;
    }
</style>
