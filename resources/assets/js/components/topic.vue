<template>
    <div class="col-md-8 col-md-push-2" v-cloak>
        <button v-link="{ path: '/' }" type="button" class="btn btn-default" aria-label="All Topics">
            <svg class="icon icon-back" style=""><use xlink:href="#icon-back"></use></svg>
            All Topics
        </button>

        <h2>Topic: {{ topic.title }}</h2>

        <div class="row">
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
                <div class="panel panel-default topic topic--in-list">
                    <div class="panel-body">
                        {{ topic.description }}
                    </div>
                </div>
            </div>
            <div class="col-xs-9 col-sm-10 col-md-11" v-if="! loadingComments">
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

                <form method="post" @submit.prevent="postComment" class="comment-form">
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
    import Topics from './../topics.js';

    export default {
        data() {
            return {
                topic: {},
                comments: [],
                loadingComments: true,
                newComment: {}
            }
        },
        created() {
            Topics.find(this.$route.params.topic_id)
                .then(
                    topic => {
                        this.topic = topic;
                        this.storeComments();
                    },
                    request => {
                        console.log('error', request);
                    }
            );
        },
        methods: {
            storeComments() {
                var url = 'topics/' + this.topic.id + '/comments';

                this.$http.get(url, (data, status, request) => {

                    this.comments = data.map(comment => {
                        comment.created_at = moment.utc(comment.created_at.date).fromNow();

                        return comment;
                    });

                    this.loadingComments = false;
                }).error((data, status, request) => {
                    console.log('error', request);
                });
            },

            postComment() {
                var url = 'topics/' + this.topic.id + '/comments';

                this.$http.post(url, this.newComment, (data, status, request) => {
                    data.created_at = moment(data.created_at.date).fromNow();

                    this.comments.push(data);
                    this.newComment = {};
                }).error((data, status, request) => {
                    if (request.status == 422) {
                        alert('Please fill in all fields.');
                    }

                    console.log('error', request);
                });
            },

            voteFor(topic) {
                Topics.voteFor(topic);
            },
        }
    };
</script>

<style scoped>
.comment-form {
    margin-top: 2em;
}

.media-object {
    max-width: 50px;
}
</style>
