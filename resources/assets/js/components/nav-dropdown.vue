<template>
    <li v-bind:class="['dropdown', isOpen ? 'open' : '']" @click="handleClick">
        <slot></slot>
    </li>
</template>

<script>
    export default {
        data: function () {
            return {
                'isOpen': false,
            };
        },
        methods: {
            handleClick: function (event) {
                if (this.isOpen) {
                    return;
                }

                event.stopPropagation();
                this.bindListeners();
                this.isOpen = true;
            },
            bindListeners: function () {
                var vm = this,
                    body = document.getElementsByTagName('body')[0];

                body.addEventListener('click', function dropdownDismiss () {
                    body.removeEventListener('click', dropdownDismiss);
                    vm.isOpen = false;
                });
            }
        }
    };
</script>
