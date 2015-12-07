<style>
</style>

<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <h2>Add Topic</h2>

            <form @submit.prevent="addTopic">
                <label>Title</label><br>
                <input type="text" v-model="title" class="form-control" autofocus v-el:add-topic-input><br>
                <input type="submit" class="btn btn-primary" value="Add topic">
                <a v-link="{ path: '/' }" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                title: ''
            };
        },
        props: {
            topics: {
                sync: true
            }
        },
        methods: {
            addTopic: function () {
                var self = this;

                this.$http.post('topics', { title: this.title }, function (data) {
                    self.title = '';

                    self.topics.push({
                        id: data.id,
                        title: data.title,
                        votes: 0
                    });

                    self.$route.router.go('/');
                });
            }
        },
        ready: function () {
            this.$els.addTopicInput.focus();
        }
    };
</script>
