<style>
</style>

<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <h2>Suggest a Topic</h2>

            <form @submit.prevent="suggestTopic">
                <label>Title</label><br>
                <input type="text" v-model="title" class="form-control" length="255" autofocus v-el:topic-title-input required><br>

                <label>Description</label><br>
                <textarea v-model="description" class="form-control"></textarea><br>

                <input type="submit" class="btn btn-primary" value="Suggest">
                <a v-link="{ path: '/' }" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                title: '',
                description: ''
            };
        },
        props: {
            topics: {
                sync: true
            }
        },
        methods: {
            suggestTopic: function () {
                var self = this;

                this.$http.post('topics', { title: this.title, description: this.description }, function (data) {
                    self.title = '';
                    self.description = '';

                    self.topics.push({
                        id: data.id,
                        title: data.title,
                        description: data.description,
                        votes: 0
                    });

                    self.$route.router.go('/');
                });
            }
        },
        ready: function () {
            this.$els.topicTitleInput.focus();
        }
    };
</script>
