<template>
    <li v-bind:class="['dropdown', isOpen ? 'open' : '']" @click="handleClick">
        <slot></slot>
    </li>
</template>

<script>
    export default {
        data () {
            return {
                'isOpen': false,
            };
        },
        methods: {
            handleClick: function (event) {
                if (this.isOpen) return;

                event.stopPropagation();
                this.bindListeners();
                this.isOpen = true;
            },
            bindListeners: function () {
                var vm = this,
                    body = document.querySelector('body');

                body.addEventListener('click', function dropdownDismiss(){
                    body.removeEventListener('click', dropdownDismiss);
                    vm.isOpen = false;
                });
            }
        }
    };
</script>
