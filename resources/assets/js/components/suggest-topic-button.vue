<template>
<a v-link="{ path: '/suggest-topic' }" class="btn btn-primary add-button pull-right">
    {{ verb }} topic
    <svg class="icon icon-plus" style=""><use xlink:href="#icon-plus"></use></svg>
</a>
</template>
<script>
    export default {
        props:{
            verb: {
                default: 'Suggest'
            }
        }
    };
</script>
