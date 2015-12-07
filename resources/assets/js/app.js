window.Vue = require('vue');
window.VueRouter = require('vue-router');

if (Suggestive.isAdmin) {
    var Dashboard = require('./components/admin-dashboard.vue');
} else {
    var Dashboard = require('./components/user-dashboard.vue');
}
var AddTopic = require('./components/add-topic.vue');

var App = Vue.extend({
    data: function() {
        return {
            topics: [
                'alphabet'
            ]
        };
    }
});

var router = new VueRouter();

router.map({
    '/': {
        component: Dashboard
    },
    '/add-topic': {
        component: AddTopic
    }
});

router.start(App, '#app')
