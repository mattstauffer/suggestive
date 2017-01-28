<style>
</style>

<template>
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <h2>{{ verb }} a Topic</h2>

            <form @submit.prevent="suggestTopic">
                <label>Title</label><br>
                <input type="text" v-model="topic.title" class="form-control" length="255" autofocus ref="topicTitleInput" required><br>

                <label>Description</label><br>
                <textarea v-model="topic.description" class="form-control"></textarea><br>

                <input type="submit" class="btn btn-primary" :value="verb">
                <router-link to="/" class="btn btn-default">Cancel</router-link>
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
                Topics.add(this.topic)
                    .then(() => {
                        this.topic.title = '';
                        this.topic.description = '';
                        this.$router.push('/');
                    })
                    .catch(err => console.log('error', err));
            }
        },
        computed: {
            verb() {
                return Suggestive.isAdmin ? 'Add' : 'Suggest';
            }
        },
        ready() {
            this.$refs.topicTitleInput.focus();
        }
    };
</script>
