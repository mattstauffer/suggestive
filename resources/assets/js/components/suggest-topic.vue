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

                <input type="submit" class="btn btn-primary" value="{{ verb }}">
                <a v-link="{ path: '/' }" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>
</template>

<script>
    import Topics from './../topics.js';

    export default {
        data: function () {
            return {
                topic: {
                    title: '',
                    description: '',
                }
            };
        },
        methods: {
            suggestTopic: function () {
                var vm = this;

                Topics.add(vm.topic).then(
                        () => {
                            vm.topic.title = '';
                            vm.topic.description = '';
                            vm.$route.router.go('/');
                        },
                        (response) => {
                            console.log('error', response);
                        }
                );
            }
        },
        computed: {
            verb: function () {
                return Suggestive.isAdmin ? 'Add' : 'Suggest';
            }
        },
        ready: function () {
            this.$els.topicTitleInput.focus();
        }
    };
</script>
