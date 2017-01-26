<style>
</style>

<template>
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <h2>{{ verb }} a Topic</h2>

            <form @submit.prevent="suggestTopic">
                <label>Title</label><br>
                <input type="text" v-model="topic.title" class="form-control" length="255" autofocus v-el:topic-title-input required><br>

                <label>Description</label><br>
                <textarea v-model="topic.description" class="form-control"></textarea><br>

                <input type="submit" class="btn btn-primary" :value="verb">
                <a v-link="{ path: '/' }" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>
</template>

<script>
    import Topics from './../topics.js';

    export default {
        data() {
            return {
                topic: {
                    title: '',
                    description: '',
                }
            }
        },
        methods: {
            suggestTopic() {

                Topics.add(this.topic).then(
                    response => {
                        this.topic.title = '';
                        this.topic.description = '';
                        this.$route.router.go('/');
                    },
                    response => {
                        console.log('error', response);
                    }
                );
            }
        },
        computed: {
            verb() {
                return Suggestive.isAdmin ? 'Add' : 'Suggest';
            }
        },
        ready() {
            this.$els.topicTitleInput.focus();
        }
    };
</script>
