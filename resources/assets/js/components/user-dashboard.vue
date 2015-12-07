<style>
</style>

<template>
    <div>
        I am the user dashboard!

        <a v-link="{ path: '/add-topic' }" class="btn btn-primary pull-right">Add topic</a>

        <ul>
            <li v-for="topic in topics">
                {{ topic }}
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: {
            topics: {
                sync: true
            }
        },
        ready: function () {
        }
    };
</script>
