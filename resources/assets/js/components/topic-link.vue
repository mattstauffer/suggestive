<template>
    <input v-show="canEditLink" v-model="topic.url" type="text" />
    <span v-else>{{topic.url}}</span>
    <button v-show="canEditLink" class="btn btn-success" type="button" @click="saveLink">Save</button>
    <button v-else class="btn btn-default" type="button" @click="toggleCanEditLink">Edit</button>
</template>

<script>
module.exports = {
    props: {
        topic: {}
    },
    data: function(){
        return {
            canEditLink: false
        }
    },
    methods: {
        toggleCanEditLink: function() {
            this.canEditLink = ! this.canEditLink;
        },
        saveLink: function() {
            alert("TODO: Link Saved");
            this.canEditLink = false;
        }
    },
    components: {

    }
}
</script>