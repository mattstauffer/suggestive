<style>
</style>

<template>
    <h2>Add Topic</h2>

    <form @submit.prevent="addTopic">
        <label>Title</label><br>
        <input type="text" v-model="title" class="form-control"><br>
        <input type="submit" class="btn btn-primary">
    </form>
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
            addTopic: function ()
            {
                this.topics.push({
                    title: this.title,
                    votes: 0
                });
                this.title = '';
                this.$route.router.go('/');
            }
        }
    };
</script>
