<style>
</style>

<template>
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <h2>Create an Episode</h2>

            <create-episode-form :accepted-topics="acceptedTopics" :episodes="episodes"></create-episode-form>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            episodes: {
                sync: true
            },
            acceptedTopics: {
                sync: true
            }
        }
    };
</script>
