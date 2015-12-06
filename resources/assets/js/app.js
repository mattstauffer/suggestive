window.Vue = require('vue');

var UserDashboard = require('./components/user-dashboard.vue');

new Vue({
    el: '#app',
    components: {
        UserDashboard: UserDashboard
    }
});
