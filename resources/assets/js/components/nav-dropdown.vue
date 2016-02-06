<style>
</style>

<template>
    <li v-bind:class="['dropdown', isToggled ? 'open' : '']" @click="toggle">
        <slot></slot>
    </li>
</template>

<script>
    export default {
        data: function () {
            return {
                'isToggled': null,
            };
        },
        methods: {
            toggle: function () {
                this.isToggled = ! this.isToggled;
            }
        }
    };
</script>
