<style>
</style>

<template>
    <div style="margin:1em 0;">
        <div id="editor" v-model="editorValue"></div>
    </div>
</template>

<script>
    import Quill from 'quill';

    export default {
        data() {
            return {
                editorValue: this.value
            }
        },

        mounted: function() {
            var editor = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Description',
            });

            editor.root.innerHTML = this.value;

            var self = this;
            editor.on('text-change', function(eventName, args) {
                 self.$emit('input', editor.root.innerHTML);
            });
        },

        props: ['value']
    };
</script>