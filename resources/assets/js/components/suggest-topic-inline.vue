<style>
.suggestor-box {
    background: #fff;
    box-shadow: 0px 0px 4px 0px rgba(0, 0, 0, 0.25);
    margin-bottom: 1em;
    padding: 2em 3em;
}
    .suggestor-box__title {
        font-size: 1.25em;
        font-weight: bold;
        margin-bottom: 0.25em;
        margin-top: -0.5em;
        text-align: center;
    }
</style>

<template>
    <div class="suggestor-box">
        <div class="suggestor-box__title">{{ verb }} a Topic</div>

        <form @submit.prevent="suggestTopic">
            <input type="text" v-model="topic.title" class="form-control" length="255" autofocus ref="topicTitleInput" required placeholder="Topic" style="margin-bottom: 0.5em">

            <textarea v-model="topic.description" class="form-control" placeholder="Description" style="margin-bottom: 0.5em"></textarea>

            <input type="submit" class="btn btn-primary" :value="verb" style="width: 100%;">
        </form>
    </div>
</template>

<script>
    import Topics from '../topics.js';

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
            this.$refs.topicTitleInput.focus();
        }
    };
</script>
